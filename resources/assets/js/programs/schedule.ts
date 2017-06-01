import {Schedule} from "../data/interfaces";
import {ScheduleToolbar} from "../schedule/scheduleToolbar";
import {headers} from "../common/functions";

export class ScheduleProgram {

    private toolBar: ScheduleToolbar;

    constructor() {
        this.toolBar = new ScheduleToolbar($('#scheduleToolbarParent'), this.onEnterEdit.bind(this),
            this.onExitEdit.bind(this), this.onScheduleSelectionChange.bind(this));
        this.setSchedules();
    }

    private onEnterEdit(): void {

    }

    private onExitEdit(save: boolean): void {

    }

    private onScheduleSelectionChange(schedule: Schedule): void {

    }

    private setSchedules() {
        const self = this;
        $.ajax({
            url: '/api/schedule',
            method: 'GET',
            headers,
            success(response: JQueryAjaxSettings) {
                const data = response.data;
                const raw = data.schedules;
                const primary: Schedule = raw.primary;
                const schedules: Schedule[] = raw.schedules;

                let selected: Schedule | null = null;
                if (primary != null) {
                    selected = primary;
                    schedules.push(primary);
                } else {
                    if (schedules.length > 0) {
                        selected = schedules[0];
                    }
                }
                self.toolBar.setSchedules(schedules, selected);
            },
            error() {
                alert('There was an error retrieving schedules');
            }
        })
    }

}

$(document).ready(() => {
    new ScheduleProgram();
});