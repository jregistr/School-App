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
    private nameInput: JQuery;
    private starInput: JQuery;

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
            const selected = this.selected!!;
            const sp = this.starInput.find('span');

            if (selected.is_primary) {
                sp.removeClass('glyphicon-star-empty');
                sp.addClass('glyphicon-star');
                sp.addClass('gold');
            } else {
                sp.removeClass('glyphicon-star');
                sp.addClass('glyphicon-star-empty');
                sp.removeClass('gold');
            }

            this.nameInput.val(selected.name);
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
        const raw = data.schedules;
        const primary: Schedule = raw.primary;
        const schedules: Schedule[] = raw.schedules;

        if (selected == null) {
            if (primary !== null) {
                selected = primary;
            } else {
                if (schedules.length > 0) {
                    selected = schedules[0];
                    schedules.splice(0, 1);
                }
            }
        } else {
            if (primary !== null && primary.id != selected.id) {
                schedules.unshift(primary);
            }

            const ind = schedules.findIndex(i => i.id == selected!!.id);
            if(ind !== -1) {
                schedules.splice(ind, 1);
            }
            const check = (primary != null && primary.id == selected.id) ? primary
                : schedules.find(i => i.id == selected!!.id);
            if (check) {
                selected = check;
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
        const outer = $(`<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 schedule-toolbar-outer 
            schedule-toolbar-edit"></div>`);
        const actionsOuter = $(`<div class="pull-right btn-group btn-group-sm"></div>`);


        const saveBtn = $(`<button class="btn btn-success"><span class="glyphicon glyphicon-floppy-save"></span></button>`);
        const cancelBtn = $(`<button class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>`);
        saveBtn.on('click', this.onSaveInEditClicked.bind(this));
        cancelBtn.on('click', this.onCancelInEditClicked.bind(this));

        actionsOuter
            .append(saveBtn)
            .append(cancelBtn);

        const formOut = $(`<div class="pull-right"></div>`);
        const nameInput = this.nameInput = $(`<input type="text" class="input-sm schedule-toolbar-input" />`);
        const star = this.starInput = $(`
            <button style="margin-right: 5px" class="btn btn-sm btn-default">
                
            </button>`);
        const sp = $(`<span class="glyphicon glyphicon-star-empty"></span>`);
        star.append(sp);

        star.on('click', () => {
            if (sp.hasClass('gold')) {
                sp.removeClass('gold');
                sp.removeClass('glyphicon-star');
                sp.addClass('glyphicon-star-empty');
            } else {
                sp.addClass('gold');
                sp.removeClass('glyphicon-star-empty');
                sp.addClass('glyphicon-star');
            }
        });

        formOut.append(star);

        formOut.append(nameInput);

        outer.append(actionsOuter);
        outer.append(formOut);

        return outer;
    }

    private onEditClicked(): void {
        if (this.selected != null && this.schedules.length > 0) {
            this.isEditMode = true;
            this.render();
            this.onEnterEdit();
        }
    }

    private onSaveInEditClicked(): void {
        this.onExitEdit(true);
        this.isEditMode = false;
        const self = this;
        const selected = this.selected!!;
        const nameInput = this.nameInput;
        const starInput = this.starInput;
        const sp = starInput.find('span');

        const starCheck = (sp.hasClass('glyphicon-star') && selected.is_primary == 0) ||
            (sp.hasClass('glyphicon-star-empty') && selected.is_primary == 1);
        const inCheck = nameInput.val() !== selected.name;
        const data = {schedule_id: selected.id};

        if (starCheck || inCheck) {
            if (starCheck) {
                data['is_primary'] = sp.hasClass('glyphicon-star') ? 1 : 0
            }

            if (inCheck) {
                data['name'] = nameInput.val();
            }

            $.ajax({
                url: '/api/schedule',
                method: 'POST',
                headers,
                data,
                success() {
                    self.querySchedules(selected);
                },
                error(xhr, status) {
                    alert('There was an error during update');
                    console.log(xhr);
                    console.log(status);
                }
            });
        }
    }

    private onCancelInEditClicked(): void {
        this.isEditMode = false;
        this.querySchedules(this.selected!!);
        this.onExitEdit(false);
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