import {Schedule} from "../data/interfaces";
import {ScheduleToolbar} from "../schedule/scheduleToolbar";

export class ScheduleProgram {

    private toolBar: ScheduleToolbar;

    constructor() {
        this.toolBar = new ScheduleToolbar($('#scheduleToolbarParent'), this.onEnterEdit.bind(this),
            this.onExitEdit.bind(this), this.onScheduleSelectionChange.bind(this));
    }

    private onEnterEdit(): void {

    }

    private onExitEdit(): void {

    }

    private onScheduleSelectionChange(schedule: Schedule): void {

    }

}

$(document).ready(() => {
    new ScheduleProgram();
});