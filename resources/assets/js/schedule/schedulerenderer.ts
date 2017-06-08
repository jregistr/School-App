import {Component} from "../data/component";
import {Course, Meeting, Schedule, ScheduledCourse, ScheduledSection, Section, Week} from "../data/interfaces";
import {headers} from "../common/functions";
import {default as moment, Moment} from "moment";
import {sendScheduleUpdates} from "./updater";
import {AddCourseComponent} from "../create/addcourse";
import {transcribe} from "../common/transcribe";
import {ViewCoursesComponent} from "../create/viewcourses";
import {ViewSectionsComponent} from "../create/viewSections";
import {renderMeetDaysDisplay} from "../create/renderMeetDisplay";

interface Event {
    title: string,
    start: string,
    end: string,
    allDay: boolean,
    courseId: number,
    sectionId: number,
    meetingId: number,
    location?: string,
    professors?: string
}

interface Changes {
    newCourses: ScheduledCourse[],
    deletedCourses: ScheduledCourse[],
    changedCourses: ScheduledCourse[],
    renderList: ScheduledCourse[]
}

enum ClickMode {
    NONE,
    EDIT,
    DELETE
}

export class ScheduleRendererComponent implements Component {

    parent: JQuery;
    private editBarParent: JQuery;
    private confirmModal: JQuery;
    private addEditModal: JQuery;

    private _schedule: Schedule | null;
    private courses: ScheduledCourse[] = [];
    private changes: Changes = {newCourses: [], deletedCourses: [], changedCourses: [], renderList: []};
    private editMode: boolean = false;
    private clickMode: ClickMode = ClickMode.NONE;
    private conflictModal: JQuery;
    private courseInfoModal: JQuery;

    private alert: JQuery;

    constructor(parent: JQuery, editBarParent: JQuery, confirmModal: JQuery, addEditModal: JQuery, listModal: JQuery,
                conflictModal: JQuery, cInfoModal: JQuery, editable: boolean = true) {
        this.parent = parent;
        this.confirmModal = confirmModal;
        this.addEditModal = addEditModal;
        this.conflictModal = conflictModal;
        this.courseInfoModal = cInfoModal;

        const confBtn = this.confirmModal.find('button[class="btn btn-danger"]');
        confBtn.on('click', () => {
            this.onDeleteCourse(
                parseInt(this.confirmModal.attr('cId')),
                parseInt(this.confirmModal.attr('sId')),
                parseInt(this.confirmModal.attr('mId'))
            );
        });

        if (editable) {
            this.initAddFromList(listModal);

            const editBar = ScheduleRendererComponent.makeEditBar((action: number) => {
                if (action == 0) {//add new
                    this.hideAlert();
                    this.clickMode = ClickMode.NONE;
                    const mBody = this.addEditModal.find('div[class="modal-body"]');
                    mBody.empty();
                    new AddCourseComponent(mBody, (course, sections) => {
                        this.addEditModal.modal('hide');
                        this.addCourseToSchedule(course, sections);
                    }, 1, {
                        commit: false
                    });
                    addEditModal.modal('show');
                } else if (action == 1) {//from list
                    this.hideAlert();
                    this.clickMode = ClickMode.NONE;
                    listModal.modal('show');
                } else if (action == 2) {//edit
                    this.clickMode = ClickMode.EDIT;
                    this.showAlert('Click on a course to edit it.');
                } else {//delete
                    this.clickMode = ClickMode.DELETE;
                    this.showAlert('Click on a course to delete it');
                }
            });

            editBarParent.append(editBar);
        }

        this.editBarParent = editBarParent;
        this.editBarParent.hide();

        const alrt = this.alert = $(`
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <text><strong>Info!</strong> Click on a course to edit it.</text>
            </div>
        `);
        alrt.hide();

        const alertOuter = this.editBarParent.find('div[data-tag="1"]');
        alertOuter.append(alrt);

        this.render();
    }

    public render(): void {
        this.parent.show();
        this.parent.empty();
        const outer = ScheduleRendererComponent.makeOuter();
        this.parent.append(outer);

        if (this._schedule == null) {
            const config = ScheduleRendererComponent.basicConfig();
            outer.fullCalendar(config);
        } else {
            const config: any = ScheduleRendererComponent.basicConfig();
            config.events = this.makeEvents();
            config.eventClick = this.onEventClick.bind(this);

            const courseList = this.editMode ? this.changes.renderList : this.courses;

            const min: Moment | null = ScheduleRendererComponent.findExtreme(
                moment.min, -1, 0,
                courseList.map(c => moment(c.section.meeting.start, ['HH:mm']))
            );

            let max: Moment | null = ScheduleRendererComponent.findExtreme(
                moment.max, 1, 0,
                courseList.map(c => moment(c.section.meeting.end, ['HH:mm']))
            );

            if (min && max) {
                const duration = moment.duration(max.diff(min)).asHours();
                if (duration < 8) {
                    max = moment(min).add(8, 'h');
                }
                config.minTime = min.format('HH:mm');
                config.maxTime = max.format('HH:mm');
            }

            outer.fullCalendar(config);
        }
    }

    public hide(): void {
        this.parent.hide();
    }

    get schedule(): Schedule | null {
        return this._schedule;
    }

    setCurrentSchedule(schedule: Schedule | null, onComplete?: () => void) {
        const self = this;
        this._schedule = schedule;
        if (schedule == null) {
            this.courses = [];
        } else {
            $.ajax({
                url: '/api/schedule/course',
                method: 'GET',
                headers,
                data: {
                    schedule_id: schedule.id
                },
                success(response: JQueryAjaxSettings) {
                    self.courses = response.data.courses;
                    self.render();
                    if (onComplete) {
                        onComplete();
                    }
                },
                error(xhr, status) {
                    console.log(xhr, status);
                    alert('There was an error retrieving the courses.')
                }
            });
        }
    }

    enterEdit(onComplete: () => void): void {
        this.editBarParent.show();
        this.editMode = true;
        this.clickMode = ClickMode.NONE;
        this.changes.renderList = JSON.parse(JSON.stringify(this.courses));
        onComplete();
    }

    onCancel(onComplete: () => void): void {
        this.editMode = false;
        this.clickMode = ClickMode.NONE;
        this.editBarParent.hide();
        this.changes.renderList = [];
        this.changes.changedCourses = [];
        this.changes.newCourses = [];
        this.changes.deletedCourses = [];
        this.render();
        onComplete();
    }

    onSave(onComplete: () => void): void {
        const changes = this.changes;
        if (changes.changedCourses.length == 0 && changes.newCourses.length == 0 && changes.deletedCourses.length == 0) {
            this.onCancel(onComplete);
        } else {
            sendScheduleUpdates(this.schedule!!.id, changes.newCourses,
                changes.deletedCourses,
                changes.changedCourses, () => {
                    this.editMode = false;
                    this.setCurrentSchedule(this.schedule, () => {
                        onComplete();
                    });
                });
        }
    }

    private onEventClick(event: Event): void {//event: Event, jsEvent: any, view: any
        if (this.editMode) {
            if (this.clickMode == ClickMode.EDIT) {
                const self = this;
                const mBody = this.addEditModal.find('div[class="modal-body"]');
                mBody.empty();
                const toEdit: ScheduledCourse | null = self.changes.renderList.find(
                        c => c.id == event.courseId
                        && c.section.id == event.sectionId && c.section.meeting.id == event.meetingId
                    ) || null;
                if (toEdit != null) {
                    const section = toEdit.section;
                    const meeting = section.meeting;
                    const prefiled = {
                        name: toEdit.name,
                        credits: toEdit.credits,
                        crn: toEdit.crn,
                        instructor: section.instructors,
                        location: meeting.location,
                        start: meeting.start,
                        end: meeting.end,
                        week: Object.assign({}, meeting.week)
                    };
                    new AddCourseComponent(mBody, (course, sections) => {
                        this.addEditModal.modal('hide');
                        self.submitEditCourse(course, sections, event.courseId, event.sectionId, event.meetingId);
                    }, 1, {
                        commit: false,
                        preFiled: prefiled
                    });
                } else {
                    alert('There is an error, that course in not in the list');
                }
                this.addEditModal.modal('show');
            } else if (this.clickMode == ClickMode.DELETE) {
                this.confirmModal.attr('cId', event.courseId);
                this.confirmModal.attr('sId', event.sectionId);
                this.confirmModal.attr('mId', event.meetingId);
                this.confirmModal.modal('show');
            }
            this.hideAlert();
            this.clickMode = ClickMode.NONE;
        } else {
            this.showCourseInfoModal(this.courses.find(
                c => c.id == event.courseId
                && c.section.id == event.sectionId && c.section.meeting.id == event.meetingId
            )!!);
        }
    }

    private showCourseInfoModal(course: ScheduledCourse) {
        const sec = course.section;
        const meeting = sec.meeting;
        const modal = this.courseInfoModal;
        const mBody = modal.find('div[class="modal-body"]');
        mBody.empty();
        const table = $(`
                <table class="table table-condensed">
                    <thead style="display: none;">
                        <tr>
                            <th>Name</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                </table>
            `);
        const tbody = $('<tbody></tbody>');
        table.append(tbody);
        mBody.append(table);

        tbody.append($(`
                <tr>
                    <td>Instructors</td>
                    <td>${sec.instructors != null ? sec.instructors : 'Not specified'}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td>${meeting.location != null ? meeting.location : 'Not specified'}</td>
                </tr>
                <tr>
                    <td>Start</td>
                    <td>${moment(meeting.start, ["HH:mm"]).format("h:mm A")}</td>
                </tr>
                <tr>
                    <td>End</td>
                    <td>${moment(meeting.end, ["HH:mm"]).format("h:mm A")}</td>
                </tr>
            `));
        const tr = $('<tr></tr>');
        const td = $(`<td colspan="2"></td>`);
        tr.append(td);

        const week = meeting.week;
        td.append(renderMeetDaysDisplay(week));

        tbody.append(td);

        const title = modal.find('h4[class="modal-title"]');
        title.empty();
        title.append($(`<strong>Course: ${course.name}, credits: ${course.credits}</strong>`));
        modal.modal('show');
    }

    private addCourseToSchedule(course: Course, sections: Section[]): void {
        const trans = transcribe(course, sections);
        this.onAddCourse(trans);
    }

    private submitEditCourse(course: Course, sections: Section[], cId: number, sId: number, mId: number): void {
        const trans = transcribe(course, sections);
        trans.id = cId;
        trans.section.id = sId;
        trans.section.meeting.id = mId;
        this.onEditCourse(trans);
    }

    private showAlert(message: string): void {
        this.parent.addClass('clickMode');
    }

    private hideAlert(): void {
        this.parent.removeClass('clickMode');
    }

    private onAddCourse(course: ScheduledCourse): void {
        const c = this.changes;

        const found: ScheduledCourse | null =
            c.renderList.find(f => f.id == course.id && f.section.id == course.section.id &&
            f.section.meeting.id == course.section.meeting.id) || null;
        if (!found) {
            const conflict = ScheduleRendererComponent.findConflict(course, c.renderList);
            if (conflict == null) {
                c.renderList.push(course);
                c.newCourses.push(course);
                this.render();
            } else {
                ScheduleRendererComponent.showTimeConflict(conflict.name, this.conflictModal);
            }
        }
    }

    private onEditCourse(course: ScheduledCourse): void {
        const conflict = ScheduleRendererComponent.findConflict(course, this.changes.renderList);
        if (conflict == null) {
            const predicate: (c: ScheduledCourse) => boolean = (c => c.id == course.id
            && c.section.id == course.section.id &&
            c.section.meeting.id == course.section.meeting.id);


            const indexInRender = this.changes.renderList.findIndex(predicate);
            this.changes.renderList[indexInRender] = course;

            const indexInNew = this.changes.newCourses.findIndex(predicate);
            if (indexInNew != -1) {
                this.changes.newCourses[indexInNew] = course;
            } else {
                const indexInEdit = this.changes.changedCourses.findIndex(predicate);
                if (indexInEdit != -1) {
                    this.changes.changedCourses[indexInEdit] = course;
                } else {
                    this.changes.changedCourses.push(course);
                }
            }
            this.render();
        } else {
            ScheduleRendererComponent.showTimeConflict(course.name, this.conflictModal);
        }
    }

    private onDeleteCourse(cId: number, sId: number, mId: number): void {
        const predicate: (c: ScheduledCourse) => boolean = (c => c.id == cId && c.section.id == sId &&
        c.section.meeting.id == mId);

        const courseIndex = this.changes.renderList.findIndex(predicate);
        const indexInEdits = this.changes.changedCourses.findIndex(predicate);
        const indexInNew = this.changes.newCourses.findIndex(predicate);

        if (indexInEdits != -1) {
            this.changes.changedCourses.splice(indexInEdits, 1);
        }

        if (indexInNew != -1) {
            this.changes.newCourses.splice(indexInNew, 1);
        }

        const course = this.changes.renderList[courseIndex];
        this.changes.renderList.splice(courseIndex, 1);
        this.changes.deletedCourses.push(course);

        this.render();
    }

    private makeEvents(): Event[] {
        const courses = this.editMode ? this.changes.renderList : this.courses;
        const events: Event[] = [];
        courses.forEach(course => {
            const begin = moment().startOf('week').isoWeekday(7);
            const section: ScheduledSection = course.section;
            const meeting: Meeting = section.meeting;
            const week = meeting.week;
            [
                week.sunday,
                week.monday,
                week.tuesday,
                week.wednesday,
                week.thursday,
                week.friday,
                week.saturday
            ].forEach(value => {
                if (value == 1) {
                    events.push(ScheduleRendererComponent.makeEvent(course, moment(begin)));
                }
                begin.add(1, 'd');
            });
        });
        return events;
    }

    private initAddFromList(listModal: JQuery): void {
        const tabContent = listModal.find('div[class="tab-content"]');
        const sectionsTabBody = tabContent.children().eq(1);
        const self = this;

        const sectionsTabActiv = listModal.find('a[href="#sectionsScheduleView"]');

        function onCourseRowClick(course: Course): void {
            sectionsTabActiv.tab('show');
            viewSections.course = course;
        }

        function onAddSectionClick(scheduledCourse: ScheduledCourse): void {
            listModal.modal('hide');
            self.onAddCourse(scheduledCourse);
        }

        new ViewCoursesComponent(listModal.find('div[class="view-course-table"]'),
            onCourseRowClick.bind(this), this.courseInfoModal, 'viewCoursesToolbar', {
                addNew: false,
                perPage: 5
            });

        const viewSections = new ViewSectionsComponent(sectionsTabBody, 'viewSectionsToolbar',
            onAddSectionClick.bind(this));
    }

    private static makeEvent(course: ScheduledCourse, refDay: Moment): Event {
        const section: ScheduledSection = course.section;
        const meeting: Meeting = section.meeting;

        const start = moment(refDay);
        const end = moment(refDay);
        const meetingStart = moment(meeting.start, ['HH:mm']);
        const meetingEnd = moment(meeting.end, ['HH:mm']);

        start.hour(meetingStart.hour());
        start.minute(meetingStart.minute());
        end.hour(meetingEnd.hour());
        end.minute(meetingEnd.minute());

        return {
            title: course.name,
            start: start.format(),
            end: end.format(),
            allDay: false,
            courseId: course.id,
            sectionId: section.id,
            meetingId: meeting.id,
            professors: section.instructors,
            location: meeting.location
        };
    }

    private static findExtreme(compare: (moments: Moment[]) => Moment,
                               addHour: number, addMinute: number, times: Moment[]): Moment | null {
        let result: Moment | null = null;
        if (times.length > 0) {
            const extreme = compare(times);
            extreme.add(addHour, 'h');
            extreme.add(addMinute, 'm');
            // result = extreme.format('HH:mm');
            result = extreme;
        }
        return result;
    }

    private static makeOuter(): JQuery {
        return $(`<div class="schedule-render-outer"></div>`);
    }

    private static makeEditBar(onDropdownClick: (action: number) => void): JQuery {
        const outer = $(`<div class="schedule-render-edit-group"></div>`);
        outer.append($(`<div class="hidden-lg hidden-md col-sm-7 hidden-xs"></div>`));

        const alertOuter = $(`<div data-tag="1" class="col-lg-5 col-md-5 col-sm-5 col-xs-12"></div>`);
        const btnGroupOuter = $(`<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12"></div>`);
        outer.append(alertOuter);
        outer.append(btnGroupOuter);

        const btnGroup = $(`<div class="btn-group pull-right"></div>`);
        btnGroupOuter.append(btnGroup);

        const createNew = $(`<button class="btn btn-default">Add new</button>`);
        const fromList = $(`<button class="btn btn-default">Add from <b>List</b></button>`);
        const edit = $(`<button class="btn btn-default">Edit</button>`);
        const deleteCourse = $(`<button class="btn btn-default">Delete</button>`);

        [createNew, fromList, edit, deleteCourse].forEach((c, i) => {
            btnGroup.append(c);
            c.on('click', () => {
                onDropdownClick(i);
            })
        });

        return outer;
    }

    private static basicConfig(): any {
        return {
            header: {
                left: '',
                center: '',
                right: '',
            },
            firstDay: 0,
            editable: false,
            // theme:true,
            defaultView: 'agendaWeek',
            allDaySlot: false,
            columnFormat: 'ddd',
            contentHeight: 'auto'
        };
    }

    private static findConflict(course: ScheduledCourse, list: ScheduledCourse[]): ScheduledCourse | null {
        const form = 'HH:mm';
        let result: ScheduledCourse | null = null;
        const bMeeting = course.section.meeting;
        const bStart: Moment = moment(bMeeting.start, form);
        const bEnd: Moment = moment(bMeeting.end, form);

        for (let i = 0; i < list.length; i++) {
            const compareCourse = list[i];
            const cMeeting = compareCourse.section.meeting;
            if (bMeeting.id !== cMeeting.id) {
                const cStart = moment(cMeeting.start, form);
                const cEnd = moment(cMeeting.end, form);

                if (bStart.isBetween(cStart, cEnd) || bEnd.isBetween(cStart, cEnd) || bEnd.isSame(cEnd)) {
                    if (ScheduleRendererComponent.dayMatch(cMeeting.week, bMeeting.week)) {
                        result = compareCourse;
                        break;
                    }
                }
            }
        }
        return result;
    }

    private static dayMatch(a: Week, b: Week): boolean {
        const a1 = a.sunday == 1 && b.sunday == 1,
            a2 = a.monday == 1 && b.monday == 1,
            a3 = a.tuesday == 1 && b.tuesday == 1,
            a4 = a.wednesday == 1 && b.wednesday == 1,
            a5 = a.thursday == 1 && b.thursday == 1,
            a6 = a.friday == 1 && b.friday == 1,
            a7 = a.saturday == 1 && b.saturday == 1;
        return a1 || a2 || a3 || a4 || a5 || a6 || a7;
    }

    private static showTimeConflict(name: string, modal: JQuery): void {
        const body = modal.find('div[class="modal-body"]');
        body.empty();
        body.append($(`<strong class="text-danger lead">There is a time conflict with ${name}</strong>`));
        modal.modal('show');
    }

}