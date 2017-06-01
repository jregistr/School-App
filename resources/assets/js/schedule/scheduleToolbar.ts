import {Component} from "../data/component";
import {Schedule} from "../data/interfaces";
import {SearchDropdownComponent} from "../common/searchdropdown";
import {headers} from "../common/functions";

export class ScheduleToolbar implements Component {

    parent: JQuery;
    private isEditMode: boolean = false;
    private onEnterEdit: () => void;
    private onExitEdit: (save: boolean) => void;
    private onSelectionChange: (schedule: Schedule) => void;

    private defaultView: JQuery;
    private editView: JQuery;

    private dropDown: SearchDropdownComponent<Schedule>;
    private btnGroup: JQuery;
    private trashBtn: JQuery;
    private addNewCourseBtn: JQuery;
    private schedules: Schedule[] = [];
    private selected: Schedule | null = null;

    constructor(parent: JQuery, onEnterEdit: () => void, onExitEdit: (save: boolean) => void,
                onSelectionChange: (schedule: Schedule) => void) {
        this.parent = parent;
        this.onEnterEdit = onEnterEdit;
        this.onExitEdit = onExitEdit;
        this.onSelectionChange = onSelectionChange;

        this.defaultView = this.makeDefaultView();
        this.editView = this.makeEditModeView();
        this.parent.append(this.defaultView);
        this.parent.append(this.editView);
        this.render();
        this.querySchedules();
    }

    public render(): void {
        this.renderView();
    }

    private renderView() {
        if (this.isEditMode) {
            this.editView.show();
            this.defaultView.hide();
        } else {
            if (this.schedules.length == 0) {
                this.dropDown.hide();
                this.btnGroup.hide();
                this.trashBtn.hide();
                this.addNewCourseBtn.show();
            } else {
                this.dropDown.render();
                this.btnGroup.show();
                this.trashBtn.show();
                this.addNewCourseBtn.hide();
            }
            this.editView.hide();
            this.defaultView.show();
        }
    }

    public hide(): void {
        this.parent.hide();
    }

    public setSchedules(schedules: Schedule[], selected: Schedule | null): void {
        if (schedules.length > 0) {
            if (selected != null) {
                this.schedules = schedules;
                this.selected = selected;
                this.onSelectionChange(this.selected);
            } else {
                this.schedules = [];
                this.selected = null;
                throw new Error('schedules must be provided a selection.');
            }
        } else {
            this.selected = null;
            this.schedules = [];
        }
        this.dropDown.setData(this.schedules, this.selected);
        this.render();
    }

    private querySchedules(selected: Schedule | null = null): void {
        const self = this;
        $.ajax({
            url: '/api/schedule',
            method: 'GET',
            headers,
            success(response: JQueryAjaxSettings) {
                const data = response.data;
                self.processGetResult(data, selected);
            },
            error() {
                alert('There was an error retrieving schedules');
            }
        });
    }

    private processGetResult(data: any, selected: Schedule | null = null): void {
        console.log(selected);
        const raw = data.schedules;
        const primary: Schedule = raw.primary;
        const schedules: Schedule[] = raw.schedules;

        if (primary != null && selected == null) {
            selected = primary;
        } else {
            if (selected == null && schedules.length > 0) {
                selected = schedules[0];
                schedules.splice(0, 1);
            }
        }
        this.setSchedules(schedules, selected);
    }

    private makeDefaultView(): JQuery {
        const self = this;
        const outer = $(`<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 schedule-toolbar-outer"></div>`);
        const trash = $(`<a href="#"><span class="glyphicon glyphicon-trash"></span></a>`);
        this.trashBtn = trash;

        const confirmDeleteModal = $('#confirmModal');
        const confirmBtn = confirmDeleteModal.find('button[class="btn btn-danger"]');
        confirmBtn.on('click', this.onDeleteSchedule.bind(this));
        trash.on('click', e => {
            e.preventDefault();
            if (self.selected != null) {
                confirmDeleteModal.modal('show');
            }
        });

        outer.append($(`<div class="pull-left schedule-toolbar-trash-outer"></div>`).append($(`<ul></ul>`)
            .append($(`<li></li>`).append(trash))));

        const addNew = $(`<button class="btn btn-primary">Add new Schedule</button>`);
        addNew.on('click', self.onAddScheduleClicked.bind(self));

        outer.append($(`<div style="margin: 3px" class="pull-right"></div>`).append(addNew));
        this.addNewCourseBtn = addNew;

        const rightOuter = $(`<div class="pull-right schedule-toolbar-right"></div>`);

        const selectOuter = $(`<div style="width: 180px;" class="pull-right"></div>`);
        const othersOuter = $(`<div style="margin-top: 3px" class="pull-left btn-group btn-group-sm"></div>`);
        this.btnGroup = othersOuter;

        this.dropDown = new SearchDropdownComponent(selectOuter, (selected: Schedule) => {
                self.selected = selected;
                self.onSelectionChange(selected);
            }
            , {
                renderMenuItem(schedule: Schedule): string {
                    return schedule.is_primary ?
                        `<span style="color: gold;" class="glyphicon glyphicon-star"></span>${schedule.name}` :
                        schedule.name
                }
            });
        this.dropDown.render();

        rightOuter.append(selectOuter);
        rightOuter.append(othersOuter);
        outer.append(rightOuter);

        const editBtn = $(`<button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></button>`);
        const addBtn = $(`<button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button>`);
        addBtn.on('click', self.onAddScheduleClicked.bind(self));
        editBtn.on('click', self.onEditClicked.bind(self));
        othersOuter.append(addBtn);
        othersOuter.append(editBtn);
        return outer;
    }

    private makeEditModeView(): JQuery {
        return $(`<h3>Not yet implemented</h3>`);
    }

    private onEditClicked(): void {
        if (this.selected != null && this.schedules.length > 0) {
            this.isEditMode = true;
            this.render();
            this.onEnterEdit();
        }
    }

    private onSaveInEditClicked(): void {

    }

    private onCancelInEditClicked(): void {

    }

    private onAddScheduleClicked(): void {
        const self = this;
        $.ajax({
            url: '/api/schedule',
            method: 'PUT',
            headers,
            data: {
                name: 'New Schedule'
            },
            success(response: JQueryAjaxSettings) {
                const data = response.data;
                const schedule: Schedule = data.schedule;
                self.isEditMode = true;
                self.querySchedules(schedule);
            },
            error(xhr, status) {
                console.log(xhr);
                console.log(status);
            }
        });
    }

    private onDeleteSchedule(): void {
        const schedule: Schedule = this.selected!!;
        const self = this;
        $.ajax({
            url: '/api/schedule',
            method: 'DELETE',
            headers,
            data: {
                schedule_id: schedule.id
            },
            success(response: JQueryAjaxSettings) {
                self.processGetResult(response.data);
            },
            error(xhr, status) {
                console.log(xhr);
                console.log(status);
            }
        })
    }


}