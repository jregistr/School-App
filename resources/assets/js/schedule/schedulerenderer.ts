import {Component} from "../data/component";
import {Schedule, ScheduledCourse} from "../data/interfaces";
export class ScheduleRendererComponent implements Component {

    parent: JQuery;
    private _schedule: Schedule | null;
    private courses: ScheduledCourse[] = [];
    private editMode: boolean = false;

    constructor(parent: JQuery) {
        this.parent = parent;

    }

    public render(): void {
        this.parent.show();
    }

    public hide(): void {
        this.parent.hide();
    }

    set schedule(schedule: Schedule | null) {
        console.log('SET SCHEDULE:' + (schedule != null ? schedule.name : ''));
        this._schedule = schedule;
    }

    get schedule(): Schedule | null {
        return this._schedule;
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

    // exitEdit(save: boolean, onComplete: () => void) {
    //     console.log('EXIT');
    //     if (save) {
    //         // make queries
    //         onComplete();
    //     } else {
    //         //drop changes and
    //         onComplete();
    //     }
    // }

}