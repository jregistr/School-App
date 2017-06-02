import {Component} from "../data/component";
import {Schedule, ScheduledCourse} from "../data/interfaces";
export class ScheduleRendererComponent implements Component {

    parent: JQuery;
    private _schedule: Schedule | null;
    private courses: ScheduledCourse[] = [];

    constructor(parent: JQuery) {
        this.parent = parent;
    }

    public render(): void {
        this.parent.show();
    }

    public hide(): void {
        this.parent.hide();
    }

    set schedule(schedule: Schedule) {
        this._schedule = schedule;
    }

    get schedule(): Schedule {
        return this._schedule;
    }


}