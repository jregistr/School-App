import {Component} from "../data/component";
import {Meeting, Schedule, ScheduledCourse, ScheduledSection} from "../data/interfaces";
import {headers} from "../common/functions";
import {default as moment, Moment} from "moment";

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
    changedCourses: ScheduledCourse[],
    renderList: ScheduledCourse[]
}

export class ScheduleRendererComponent implements Component {

    parent: JQuery;
    private _schedule: Schedule | null;
    private courses: ScheduledCourse[] = [];
    private editMode: boolean = true;
    private changes: Changes = {newCourses: [], changedCourses: [], renderList: []};

    private editBar: JQuery;

    constructor(parent: JQuery) {
        this.parent = parent;
        this.editBar = ScheduleRendererComponent.makeEditBar((createNew: boolean) => {
            console.log(this);
        });
        this.render();
    }

    public render(): void {
        this.parent.show();
        this.parent.empty();
        this.parent.append(this.editBar);

        const outer = ScheduleRendererComponent.makeOuter();
        this.parent.append(outer);

        if (this.editMode) {
            this.editBar.show();
        } else {
            this.editBar.hide();
        }

        if (this._schedule == null) {
            const config = ScheduleRendererComponent.basicConfig();
            outer.fullCalendar(config);
        } else {
            const config: any = ScheduleRendererComponent.basicConfig();
            config.events = this.makeEvents();

            const min = ScheduleRendererComponent.findExtreme(
                moment.min, 0, -30,
                this.courses.map(c => moment(c.section.meeting.start, ['HH:mm']))
            );

            const max = ScheduleRendererComponent.findExtreme(
                moment.max, 1, 0,
                this.courses.map(c => moment(c.section.meeting.end, ['HH:mm']))
            );

            if (min && max) {
                config.minTime = min;
                config.maxTime = max;
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

    set schedule(schedule: Schedule | null) {
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
                },
                error(xhr, status) {
                    console.log(xhr, status);
                    alert('There was an error retrieving the courses.')
                }
            });
        }
    }

    enterEdit(onComplete: () => void): void {
        console.log('ENTER');
        this.editMode = true;
        onComplete();
    }

    onCancel(onComplete: () => void): void {
        console.log('discard');
        onComplete();
    }

    onSave(onComplete: () => void): void {
        console.log('SAVE');
        onComplete();
    }

    private makeEvents(): Event[] {
        const courses = this.courses;
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
                               addHour: number, addMinute: number, times: Moment[]): string | null {
        let result: string | null = null;
        if (times.length > 0) {
            const extreme = compare(times);
            extreme.add(addHour, 'h');
            extreme.add(addMinute, 'm');
            result = extreme.format('HH:mm');
        }
        return result;
    }

    private static makeOuter(): JQuery {
        return $(`<div class="schedule-render-outer"></div>`);
    }

    private static makeEditBar(onButtonClick: (createNew: boolean) => void): JQuery {
        const outer = $(`<div style="display: none;" class="container-fluid schedule-render-outer schedule-render-edit-group"></div>`);
        outer.append($(`<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>`));
        const alertOuter1 = $(`<div style="padding-right: 5px!important;" class="col-lg-6 col-md-6 col-sm-6 hidden-xs"></div>`);
        const btnOuter = $(`<div class="col-lg-2 col-md-2 col-sm-2 col-xs-8"></div>`);
        const alertOuter2 = $(`<div class="hidden-lg hidden-md hidden-sm col-xs-12"></div>`);

        outer.append(alertOuter1);
        outer.append(btnOuter);
        outer.append(alertOuter2);

        const alert = $(`
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Info!</strong> Click on a course to edit it.
            </div>
        `);

        const dropOuter = $(`<div class="dropdown"></div>`);
        const dropBtn = $(`<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            <span>Schedule Actions</span>
            <span class="pull-right"><span class="caret"></span></span>
        </button>`);

        const createNew = $(`<a href="#"><b>New</b> course</a>`);
        const fromList = $(`<a href="#">Add from <i>course list</i></a>`);

        // const btn = $(`<button class="btn btn-default form-control">Add a <strong>Course</strong></button>`);
        // btn.on('click', onButtonClick);
        alertOuter1.append(alert);
        alertOuter2.append(alert.clone());
        // btnOuter.append(btn);
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

}