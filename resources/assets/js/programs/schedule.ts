import {Schedule} from "../data/interfaces";
import {ScheduleToolbar} from "../schedule/scheduleToolbar";

export class ScheduleProgram {

    private toolBar:ScheduleToolbar;

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