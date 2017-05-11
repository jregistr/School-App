import {Schedule} from "./interfaces";
export module ScheduleRenderer {

    const calendarDom = $('#calendar');

    function viewConfig() {
        return {
            header: {
                left: '',
                center: '',
                right: '',
            },
            firstDay: 1,
            editable: true,
            defaultView: 'agendaWeek',
            allDaySlot: false,
            columnFormat: 'ddd',
        };
    }

    export function off() {
        calendarDom.empty();
        calendarDom.fullCalendar(viewConfig());
    }

    export function render(schedule: Schedule) {
        calendarDom.empty();
        const config = viewConfig();

    }

}
