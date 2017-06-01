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

    }

}

$(document).ready(() => {
    new ScheduleProgram();
});