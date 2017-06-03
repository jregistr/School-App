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

export class ScheduleRendererComponent implements Component {

    parent: JQuery;
    private _schedule: Schedule | null;
    private courses: ScheduledCourse[] = [];
    private editMode: boolean = false;

    constructor(parent: JQuery) {
        this.parent = parent;
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