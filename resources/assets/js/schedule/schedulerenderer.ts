import {Component} from "../data/component";
import {Schedule, ScheduledCourse} from "../data/interfaces";
import {headers} from "../common/functions";
export class ScheduleRendererComponent implements Component {

    parent: JQuery;
    private _schedule: Schedule | null;
    private courses: ScheduledCourse[] = [];
    private editMode: boolean = false;

    constructor(parent: JQuery) {
        this.parent = parent;
        this.render();
    }

    public render(): void {
        this.parent.show();
        this.parent.empty();
        const outer = ScheduleRendererComponent.makeOuter();
        this.parent.append(outer);
        if (this._schedule == null) {
            const config = ScheduleRendererComponent.basicConfig();
            outer.fullCalendar(config);
        } else {

        }
    }

    public hide(): void {
        this.parent.hide();
    }

    get schedule(): Schedule | null {
        return this._schedule;
    }

    set schedule(schedule: Schedule | null) {
        console.log('SET SCHEDULE:' + (schedule != null ? schedule.name : ''));
        const self = this;
        this._schedule = schedule;
        if (schedule == null) {
            this.courses = [];
        } else {
            $.ajax({
                url: '/api/schedule/course',
                method: 'GET',
                headers,
                data: {
                    schedule_id: schedule.id
                },
                success(response: JQueryAjaxSettings) {
                    self.courses = response.data.courses;
                    self.render();
                },
                error(xhr, status) {
                    console.log(xhr, status);
                    alert('There was an error retrieving the courses.')
                }
            });
        }
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

    private static makeOuter(): JQuery {
        return $(`<div class="schedule-render-outer"></div>`);
    }

    private static basicConfig(): any {
        return {
            header: {
                left: '',
                center: '',
                right: '',
            },
            firstDay: 1,
            editable: false,
            // theme:true,
            defaultView: 'agendaWeek',
            allDaySlot: false,
            columnFormat: 'ddd',
            contentHeight: 'auto'
        };
    }

}