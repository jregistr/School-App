import {Course, Meeting, Schedule, Section} from "./interfaces";
import * as moment from 'moment';
export module ScheduleRenderer {

    let calendarDom = $('#calendar');
    const parentDom = $('#parentDom');

    function viewConfig() {
        return {
            header: {
                left: '',
                center: '',
                right: '',
            },
            firstDay: 1,
            editable: false,
            defaultView: 'agendaWeek',
            allDaySlot: false,
            columnFormat: 'ddd',
            height: 800
        };
    }

    function makeEvent(course: Course, section: Section, meeting: Meeting, day: any) {
        const eventTemplate = {
            courseId: course.id,
            sectionId: section.id,
            title: course.name,
            location: meeting.location,
            professors: section.instructors,
            start: '',
            end: ''
        };

        const startSplit = meeting.start.split(':');
        const endSplit = meeting.end.split(':');

        const startHour = parseInt(startSplit[0]);
        const startMinute = parseInt(startSplit[1]);
        const endHour = parseInt(endSplit[0]);
        const endMinute = parseInt(endSplit[1]);

        const start = moment(day);
        const end = moment(day);

        start.hour(startHour);
        start.minute(startMinute);
        end.hour(endHour);
        end.minute(endMinute);

        eventTemplate.start = start.format();
        eventTemplate.end = end.format();

        return eventTemplate;
    }

    function makeEvents(courses: Course[]): any[] {
        const events: any[] = [];

        const begin = moment();
        courses.forEach((course) => {
            const section = course.section;
            const meeting = section.meeting;

            [
                meeting.sunday,
                meeting.monday,
                meeting.tuesday,
                meeting.wednesday,
                meeting.thursday,
                meeting.friday,
                meeting.saturday
            ].forEach(function (value: number, index: number) {
                if (value === 1) {
                    const day = begin.isoWeekday(index);
                    events.push(makeEvent(course, section, meeting, day));
                }
            });
        });

        return events;
    }

    function getMinMaxTimes(courses: Course[]): { min: string, max: string } | null {
        let minHour: string = '24';
        // let minMinute: string = '00';

        let maxHour: string = '00';
        // let maxMinute: string = '00';

        courses.forEach((course: Course) => {
            const meeting = course.section.meeting;
            const startSplit = meeting.start.split(':');
            const endSplit = meeting.end.split(':');

            const startHour = parseInt(startSplit[0]);
            const endHour = parseInt(endSplit[0]);

            if (startHour < parseInt(minHour)) {
                minHour = startSplit[0];
            }

            if (endHour > parseInt(maxHour)) {
                maxHour = endSplit[0];
            }
        });

        if (minHour !== '24' && maxHour !== '00') {
            const maxConverted = parseInt(maxHour) + 1;
            let maxAsString = maxConverted.toString();
            if (maxAsString.length < 2) {
                maxAsString = '0'.concat(maxAsString);
            }

            return {
                min: minHour.concat(':00'),
                max: maxAsString.concat(':00')
            }
        } else {
            return null;
        }
    }

    export function init() {
        calendarDom.fullCalendar(viewConfig());
    }

    export function off() {
        parentDom.empty();
        parentDom.append($('<div id="calendar"></div>'));
        calendarDom = $('#calendar');
        calendarDom.fullCalendar(viewConfig());
    }

    export function render(schedule: Schedule) {
        const courses = schedule.courses;
        if (courses.length > 0) {
            parentDom.empty();
            parentDom.append($('<div id="calendar"></div>'));
            calendarDom = $('#calendar');

            const config = viewConfig();

            const minMax = getMinMaxTimes(schedule.courses);
            if (minMax != null) {
                config['minTime'] = minMax.min;
                config['maxTime'] = minMax.max;
            }

            config['events'] = makeEvents(schedule.courses);
            calendarDom.fullCalendar(config);
        } else {
            off();
        }
    }

}
