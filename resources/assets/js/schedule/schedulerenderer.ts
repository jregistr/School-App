export module ScheduleRenderer {

    $('#calendar').fullCalendar({
        header: {
            left: '',
            center: '',
            right: '',
        },
        firstDay: 1,
        editable:true,
        defaultView : 'agendaWeek',
        allDaySlot : false,
        columnFormat : 'ddd'
    });

}
