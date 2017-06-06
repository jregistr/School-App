import {Component} from "../data/component";
import {Course, Meeting, Schedule, ScheduledCourse, ScheduledSection, Section} from "../data/interfaces";
import {headers} from "../common/functions";
import {default as moment, Moment} from "moment";
import {sendScheduleUpdates} from "./updater";
import {AddCourseComponent} from "../create/addcourse";

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

    private al1: JQuery;
    private al2: JQuery;

    constructor(parent: JQuery, editBarParent: JQuery, confirmModal: JQuery, addEditModal: JQuery) {
        this.parent = parent;
        this.confirmModal = confirmModal;
        this.addEditModal = addEditModal;
        const confBtn = this.confirmModal.find('button[class="btn btn-danger"]');
        confBtn.on('click', () => {
            this.onDeleteCourse(
                parseInt(this.confirmModal.attr('cId')),
                parseInt(this.confirmModal.attr('sId')),
                parseInt(this.confirmModal.attr('mId'))
            );
        });

        const editBar = ScheduleRendererComponent.makeEditBar((action: number) => {
            if (action == 0) {//add new
                const mBody = this.addEditModal.find('div[class="modal-body"]');
                mBody.empty();
                new AddCourseComponent(mBody, (course, sections) => {
                    this.onCourseFormSubmit(course, sections, true);
                }, 1, {
                    commit: false
                });
                addEditModal.modal('show');
            } else if (action == 1) {//from list

            } else if (action == 2) {//edit
                this.clickMode = ClickMode.EDIT;
                this.showAlert('Click on a course to edit it.');
            } else {//delete
                this.clickMode = ClickMode.DELETE;
                this.showAlert('Click on a course to delete it');
            }
        });

        editBarParent.append(editBar);
        this.editBarParent = editBarParent;
        this.editBarParent.hide();

        const alert = $(`
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <text><strong>Info!</strong> Click on a course to edit it.</text>
            </div>
        `);

        const alerts = [alert, alert.clone()];
        this.al1 = alerts[0];
        this.al2 = alerts[1];
        this.al1.hide();
        this.al2.hide();

        const alertOuters = this.editBarParent.find('div[data-tag="1"]');
        alertOuters.each((i, a) => {
            $(a).append(alerts[i])
        });

        alert.on('click', e => {
            console.log('BALLLOCKS')
        });

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
        onComplete();
        this.render();
    }

    onSave(onComplete: () => void): void {
        const changes = this.changes;
        if (changes.changedCourses.length == 0 && changes.newCourses.length == 0) {
            this.onCancel(onComplete);
        } else {
            sendScheduleUpdates(changes.newCourses,
                changes.deletedCourses,
                changes.changedCourses, () => {
                    this.setCurrentSchedule(this.schedule, onComplete);
                });
        }
    }

    private onEventClick(event: Event): void {//event: Event, jsEvent: any, view: any
        if (this.editMode) {
            if (this.clickMode == ClickMode.EDIT) {
                console.log('i am here');
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
                        self.onCourseFormSubmit(course, sections, false);
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
        }
    }

    private onCourseFormSubmit(course: Course, sections: Section[], addNewCourse: boolean): void {
        const trans = ScheduleRendererComponent.transcribe(course, sections);
        if (addNewCourse) {
            this.onAddCourse(trans);
        } else {
            this.onEditCourse(trans);
        }
    }

    private showAlert(message: string): void {
        this.al1.show();
        this.al2.show();
        [this.al1.find('text'), this.al2.find('text')].forEach(t => {
            t.empty();
            t.append(`<strong>Info!</strong> ${message}`);
        });
    }

    private hideAlert(): void {
        this.al1.hide();
        this.al2.hide();
    }

    private onAddCourse(course: ScheduledCourse): void {
        console.log(course);
    }

    private onEditCourse(course: ScheduledCourse): void {
        console.log(course);
    }

    private onDeleteCourse(cId: number, sId: number, mId: number): void {
        const courseIndex = this.changes.renderList.findIndex(c => c.id == cId && c.section.id == sId &&
        c.section.meeting.id == mId);

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
        const outer = $(`<div style="" 
            class="schedule-render-edit-group"></div>`);
        outer.append($(`<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>`));
        const alertOuter1 = $(`<div data-tag="1" style="padding-right: 5px!important;" 
            class="col-lg-6 col-md-6 col-sm-6 hidden-xs"></div>`);
        const btnOuter = $(`<div class="col-lg-2 col-md-2 col-sm-2 col-xs-8 schedule-render-col"></div>`);
        const alertOuter2 = $(`<div data-tag="1" class="hidden-lg hidden-md hidden-sm col-xs-12"></div>`);

        outer.append(alertOuter1);
        outer.append(btnOuter);
        outer.append(alertOuter2);

        const dropOuter = $(`<div class="dropdown"></div>`);
        const dropBtn = $(`<button style="width: 100%!important;" class="btn btn-default dropdown-toggle" type="button" 
            data-toggle="dropdown">
            <span>Edit Actions</span>
            <span class="pull-right"><span class="caret"></span></span>
        </button>`);

        const createNew = $(`<a style="cursor:pointer;" >Add <b>New</b> course</a>`);
        const fromList = $(`<a style="cursor:pointer;">Add from <b>course list</b></a>`);
        const editSchedule = $(`<a style="cursor:pointer;">Edit a course</a>`);
        const deleteSchedule = $(`<a style="cursor:pointer;">Delete a course</a>`);

        dropOuter.append(dropBtn);
        const ul = $(`<ul style="width: 100%!important;" class="dropdown-menu" role="menu"></ul>`);
        [createNew, fromList, editSchedule, deleteSchedule].forEach((c, i) => {
            ul.append($(`<li></li>`).append(c));
            c.on('click', () => {
                onDropdownClick(i);
            })
        });
        dropOuter.append(ul);
        btnOuter.append(dropOuter);
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

    private static transcribe(course: Course, sections: Section[]): ScheduledCourse {
        throw new Error('not implemented');
    }

}