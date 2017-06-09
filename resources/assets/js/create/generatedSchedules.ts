import {Component} from "../data/component";
import {Schedule} from "../data/interfaces";
import {headers} from "../common/functions";
import {ScheduleRendererComponent} from "../schedule/schedulerenderer";

export class GeneratedSchedulesComponent implements Component {
    parent: JQuery;
    private outer: JQuery;
    private schedules: Schedule[];
    private url: string = '/api/schedule';
    private confirmClearModal: JQuery;
    private confirmAddRemove: JQuery;

    private confirmAddMessage = $(`<strong class="lead">Add this to your schedules list?</strong>`);
    private confirmRemMessage = $(`<strong class="lead">Discard this generated schedule?</strong>`);

    constructor(parent: JQuery, confirmClear: JQuery, confirmAddRemoveModal: JQuery) {
        this.parent = parent;
        this.parent.empty();
        this.outer = $(`<div class="container-fluid gs-page"></div>`);
        this.parent.append(this.outer);
        this.confirmClearModal = confirmClear;
        this.confirmAddRemove = confirmAddRemoveModal;
    }

    render(): void {
        this.outer.empty();
        if (this.schedules.length > 0) {
            this.schedules.forEach(schedule => {
                const p = $(`<div class="col-lg-12 gs-schedule-col"></div>`);
                const s = this.makeScheduleElement(schedule);
                this.outer.append(p);
                p.append(s);
            });
        } else {
            this.outer.append($(`<strong class="lead">No schedules have been loaded. Generate some new ones</strong>`));
        }
        this.outer.show();
    }

    hide(): void {
        this.outer.hide();
    }

    updateSchedules(schedules: Schedule[] | null = null): void {
        if (schedules == null) {
            this.schedules = [];
            const self = this;
            $.ajax({
                url: this.url,
                method: 'GET',
                headers,
                data: {
                    generated: true
                },
                success(response: JQueryAjaxSettings) {
                    const data: Schedule[] = response.data.schedules;
                    self.schedules = data != null ? data : [];
                    self.render();
                },
                error(xhr, status) {
                    console.log(xhr);
                    console.log(status);
                    alert('There was an error retrieving data');
                }
            });
        } else {
            this.schedules = schedules;
            this.render();
        }
    }

    clearGeneratedSchedules(onComplete: (continu: boolean) => void): void {
        if (this.schedules.length > 0) {
            const btn = this.confirmClearModal.find('button[class="btn btn-danger"]');
            const cancel = this.confirmClearModal.find('button[class="btn btn-primary"]');
            const self = this;
            btn.on('click', () => {
                self.clearGenSchedules();
                onComplete(true);
            });

            cancel.on('click', () => {
                onComplete(false);
            });
            this.confirmClearModal.modal('show');
        } else {
            onComplete(true);
        }
    }

    private clearGenSchedules(): void {
        this.schedules = [];
        this.render();
    }

    private addSchedule(schedule: Schedule): void {
        const self = this;
        $.ajax({
            url: this.url,
            method: 'POST',
            headers,
            data: {
                schedule_id: schedule.id,
                generated: 1
            },
            success() {
                self.updateSchedules();
            },
            error(xhr, status) {
                console.log(xhr);
                console.log(status);
                console.log('There was an error.');
            }
        });
    }

    private removeSchedule(schedule: Schedule): void {
        const self = this;
        $.ajax({
            url: this.url,
            method: 'DELETE',
            headers,
            data: {
                schedule_id: schedule.id
            },
            success() {
                self.updateSchedules();
            },
            error(xhr, status) {
                console.log(xhr);
                console.log(status);
                console.log('There was an error.');
            }
        });
    }

    private showConfirmAddRemModal(message: JQuery | string, schedule: Schedule,
                                   onConfirm: (schedule: Schedule) => void): void {
        const modal = this.confirmAddRemove;
        const body = modal.find('div[class="modal-body"]');
        body.empty();
        body.append(message);

        const yesBtn = modal.find('button[class="btn btn-danger"]');
        yesBtn.off('click');

        yesBtn.on('click', () => {
            onConfirm(schedule);
        });
        modal.modal('show');
    }

    private makeScheduleElement(schedule: Schedule): JQuery {
        const panelOuter = $(`
            <div class="panel panel-default">
                
            </div>`);

        const panelHeading = $(`
            <div class="panel-heading">
            </div>
        `).append($(`<strong class="pull-left">${schedule.name}</strong>`));

        const btnGroupOuter = $(`
             <div class="pull-right">
                
            </div>
        `);

        const btnGroup = $(`
            <div class="btn-group btn-group-sm" style="margin-top: -5px">
               
            </div>
        `);

        const x = $(`<button class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>`);
        const p = $(`<button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button>`);

        btnGroup.append(x);
        btnGroup.append(p);

        x.on('click', () => {
            this.showConfirmAddRemModal(this.confirmRemMessage, schedule, this.removeSchedule.bind(this));
        });

        p.on('click', () => {
            this.showConfirmAddRemModal(this.confirmAddMessage, schedule, this.addSchedule.bind(this));
        });

        panelHeading.append(btnGroupOuter);
        btnGroupOuter.append(btnGroup);
        panelOuter.append(panelHeading);
        const body = $(`<div class="panel-body"></div>`);
        panelOuter.append(body);

        const renderer = new ScheduleRendererComponent(body, $('<div></div>'),
            $('#sch-confirmModal2'), $('#sch-addEditModal'), $('#sch-addFromListModal'),
            $('#sch-timeConflict'), $('#sch-courseInfoModal'),
            false);
        renderer.setCurrentSchedule(schedule);
        return panelOuter;
    }

}