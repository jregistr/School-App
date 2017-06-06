import {Schedule} from "../data/interfaces";
// import {ScheduleToolbar} from "../schedule/schedule_Toolbar";
import {ScheduleRendererComponent} from "../schedule/schedulerenderer";
import {ScheduleToolbarComponent} from "../schedule/scheduletoolbar";

export class ScheduleProgram {

    // private toolBar: ScheduleToolbar;

    private toolbar: ScheduleToolbarComponent;
    private scheduleRenderer: ScheduleRendererComponent;

    constructor() {
        this.toolbar = new ScheduleToolbarComponent($('#scheduleToolbarParent'), $('#confirmModal'),
            this.onScheduleSelectionChange.bind(this), this.onEnterEdit.bind(this), this.onExitEdit.bind(this));
        this.scheduleRenderer = new ScheduleRendererComponent($('#scheduleComponentParent'), $('#editBarParent'),
            $('#confirmModal2'), $('#addEditModal'));
    }

    private onEnterEdit(onComplete: () => void): void {
        this.scheduleRenderer.enterEdit(onComplete);
    }

    private onExitEdit(save: boolean, onComplete: () => void): void {
        if (save) {
            this.scheduleRenderer.onSave(onComplete);
        } else {
            this.scheduleRenderer.onCancel(onComplete);
        }
    }

    private onScheduleSelectionChange(schedule: Schedule | null): void {
        this.scheduleRenderer.setCurrentSchedule(schedule);
    }

}

$(document).ready(() => {
    new ScheduleProgram();
});