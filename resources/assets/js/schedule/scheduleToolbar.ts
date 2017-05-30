import {Component} from "../data/component";

export class ScheduleToolbar implements Component {

    parent: JQuery;

    constructor(parent: JQuery) {
        this.parent = parent;
    }

    public render(): void {
    }

    public hide(): void {
    }

}