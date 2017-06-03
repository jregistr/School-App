import {Component} from "../data/component";
import {Schedule} from "../data/interfaces";
import {headers} from "../common/functions";

export class ScheduleToolbarComponent implements Component {

    parent: JQuery;
    private outer: JQuery;
    private confirmDeleteModal: JQuery;
    private confirmCreateModal: JQuery;

    private isEditMode: boolean = false;
    private defaultView: JQuery;
    private editView: JQuery;
    private btnGroup: JQuery;
    private trashBtn: JQuery;
    private addNewCourseBtn: JQuery;
    private nameInput: JQuery;
    private starInput: JQuery;

    private dropDownOuter: JQuery;
    private dropDownText: JQuery;
    private dropDownList: JQuery;
    private dropDownSearch: JQuery;

    private onSelectionChange: (schedule: Schedule | null) => void;
    private onEnterEdit: (onComplete: () => void) => void;
    private onExitEdit: (saveChanges: boolean, onComplete: () => void) => void;

    private selected: Schedule | null;
    private schedules: Schedule[] = [];

    constructor(parent: JQuery, modal: JQuery, onSelectionChange: (schedule: Schedule | null) => void,
                onEnterEdit: (onComplete: () => void) => void,
                onExitEdit: (saveChanges: boolean, onComplete: () => void) => void) {
        this.parent = parent;
        this.confirmDeleteModal = modal;
        this.confirmCreateModal = modal.clone();
        modal.parent().append(this.confirmCreateModal);

        this.confirmCreateModal.find('strong').empty().append('<b>Create</b> new empty schedule?');
        const confirmBtn = this.confirmCreateModal.find('button[class="btn btn-danger"]');
        confirmBtn.removeClass('btn-danger');
        confirmBtn.addClass('btn btn-success');
        confirmBtn.on('click', this.onCreateSchedule.bind(this));

        this.onSelectionChange = onSelectionChange;
        this.onEnterEdit = onEnterEdit;
        this.onExitEdit = onExitEdit;

        this.outer = $(`<div></div>`);
        this.parent.append(this.outer);

        this.defaultView = this.makeDefaultView();
        this.editView = this.makeEditModeView();

        this.outer.append(this.defaultView);
        this.outer.append(this.editView);
        this.outer.hide();
        this.setSchedulesFromServer();
    }

    render(): void {
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
                this.dropDownOuter.hide();
                this.btnGroup.hide();
                this.trashBtn.hide();
                this.addNewCourseBtn.show();
            } else {
                this.dropDownOuter.show();
                this.btnGroup.show();
                this.trashBtn.show();
                this.addNewCourseBtn.hide();
            }
            this.editView.hide();
            this.defaultView.show();
        }
        this.outer.show();
    }

    hide(): void {
        this.outer.hide();
    }

    private makeDefaultView(): JQuery {
        const self = this;
        const outer = $(`<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 schedule-toolbar-outer"></div>`);
        const trash = $(`<a href="#"><span class="glyphicon glyphicon-trash"></span></a>`);
        this.trashBtn = trash;

        const confirmDeleteModal = this.confirmDeleteModal;
        const confirmBtn = confirmDeleteModal.find('button[class="btn btn-danger"]');

        confirmBtn.on('click', () => {
            self.onDeleteSchedule(self.selected!!)
        });
        trash.on('click', e => {
            e.preventDefault();
            if (self.selected != null) {
                confirmDeleteModal.modal('show');
            }
        });

        outer.append($(`<div class="pull-left schedule-toolbar-trash-outer"></div>`).append($(`<ul></ul>`)
            .append($(`<li></li>`).append(trash))));

        const addNew = $(`<button class="btn btn-primary">Add new Schedule</button>`);

        addNew.on('click', () => {
            self.confirmCreateModal.modal('show');
        });

        outer.append($(`<div style="margin: 3px" class="pull-right"></div>`).append(addNew));
        this.addNewCourseBtn = addNew;

        const rightOuter = $(`<div class="pull-right schedule-toolbar-right"></div>`);

        const selectOuter = $(`<div class="pull-right schedule-toolbar-select-outer"></div>`);
        const othersOuter = $(`<div style="margin-top: 3px" class="pull-left btn-group btn-group-sm"></div>`);
        this.btnGroup = othersOuter;
        selectOuter.append(self.makeDropDown());

        rightOuter.append(selectOuter);
        rightOuter.append(othersOuter);
        outer.append(rightOuter);

        const editBtn = $(`<button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></button>`);
        const addBtn = $(`<button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button>`);

        // addBtn.on('click', self.onAddScheduleClicked.bind(self));
        editBtn.on('click', self.enterEditMode.bind(self));

        addBtn.on('click', () => {
            self.confirmCreateModal.modal('show');
        });

        othersOuter.append(addBtn);
        othersOuter.append(editBtn);
        return outer;
    }

    private makeEditModeView(): JQuery {
        const outer = $(`<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 schedule-toolbar-outer 
            schedule-toolbar-edit"></div>`);
        const actionsOuter = $(`<div class="pull-right btn-group"></div>`);


        const saveBtn = $(`<button class="btn btn-success"><span class="glyphicon glyphicon-floppy-save"></span></button>`);
        const cancelBtn = $(`<button class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>`);
        cancelBtn.on('click', this.onCancelEdit.bind(this));
        saveBtn.on('click', this.onSaveEdit.bind(this));

        actionsOuter
            .append(saveBtn)
            .append(cancelBtn);

        const formOut = $(`<div class="pull-right"></div>`);
        const nameInput = this.nameInput = $(`<input type="text" class="schedule-toolbar-input" />`);
        const star = this.starInput = $(`
            <button style="margin-right: 5px" class="btn btn-default">
                
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

    private makeDropDown(): JQuery {
        const self = this;
        const outer = this.dropDownOuter = $(`
            <div class="dropdown">
            
            </div>
        `);

        const dropBtn = $(`
            <button type="button" style="width: 100%!important;"
            class="btn btn-default dropdown-toggle searchdrop-picker" data-toggle="dropdown">
            </button>
        `);

        const currentSpan = this.dropDownText = $(`<span class="pull-left filter-option"></span>`);

        dropBtn
            .append(currentSpan)
            .append($(`<span class="pull-right"><span class="caret"></span></span>`));

        outer.append(dropBtn);

        const menuOuter = $(`
            <div class="dropdown-menu 
            open searchdrop-menu"></div>
        `);

        const inputBox = this.dropDownSearch = $(`<input type="text" class="form-control" />`);
        menuOuter.append($(`<div class="searchdrop-box"></div>`).append(inputBox));

        const ul = this.dropDownList = $(`
            <ul style="width: 100%!important;" class="dropdown-menu inner" role="menu">
            </ul>
        `);

        menuOuter.append(ul);

        inputBox.keyup(() => {
            const val = inputBox.val();
            const filtered = val.length == 0 ? this.schedules :
                this.schedules.filter(s => s.name.toLowerCase().indexOf(inputBox.val()) != -1);
            self.renderScheduleList(filtered);
        });

        outer.append(menuOuter);
        return outer;
    }

    private renderScheduleList(schedules: Schedule[]): void {
        const list = this.dropDownList;
        list.empty();
        const self = this;
        schedules.forEach(schedule => {
                const a = $(`<a>
                ${!schedule.is_primary ? schedule.name :
                    `<span class="glyphicon glyphicon-star gold"></span> ${schedule.name}`}
                 </a>`);
                list.append($('<li></li>').append(a));
                a.on('click', () => {
                    self.updateCurrentSelected(schedule);
                    self.onSelectionChange(schedule);
                    self.dropDownSearch.val('');
                    self.dropDownSearch.trigger('keyup');
                });
            }
        );
    }

    private updateCurrentSelected(schedule: Schedule | null): void {
        this.selected = schedule;
        const sp = this.dropDownText;
        sp.empty();
        if (schedule != null) {
            sp.append(`${!schedule.is_primary ? schedule.name :
                `<span class="glyphicon glyphicon-star gold"></span> ${schedule.name}`}`);
        }
    }

    private setSchedulesFromServer(toUpdate: number = -1, onComplete?: () => void) {
        const self = this;
        $.ajax({
            url: '/api/schedule',
            method: 'GET',
            headers,
            success(response: JQueryAjaxSettings) {
                const data = response.data;
                const outer = data.schedules;

                const toSelect = self.processListFromServer(outer, toUpdate);
                self.updateCurrentSelected(toSelect);
                self.onSelectionChange(toSelect);
                self.render();
                if (onComplete) {
                    onComplete();
                }
            },
            error() {
                alert('There was an error retrieving schedules');
            }
        });
    }


    private onDeleteSchedule(schedule: Schedule): void {
        const self = this;
        $.ajax({
            url: '/api/schedule',
            method: 'DELETE',
            headers,
            data: {
                schedule_id: schedule.id
            },
            success(response: JQueryAjaxSettings) {
                const data = response.data;
                const outer = data.schedules;

                const toSelect = self.processListFromServer(outer);
                self.updateCurrentSelected(toSelect);
                self.onSelectionChange(toSelect);
                self.render();
            },
            error(xhr, status) {
                console.log(xhr);
                console.log(status);
            }
        })
    }

    private onCreateSchedule(): void {
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
                self.setSchedulesFromServer(schedule.id, () => {
                    if (self.selected !== null && self.selected.id == schedule.id) {
                        self.enterEditMode();
                    } else {
                        alert('Something went wrong while creating your schedule.');
                    }
                });
            }
        });
    }


    private querySaveUpdate(schedule: Schedule, starCheck: boolean, inputCheck: boolean,
                            nameIn: JQuery, starIn: JQuery): void {
        const self = this;
        const data: any = {schedule_id: schedule.id};

        if (starCheck) {
            data.is_primary = starIn.hasClass('glyphicon-star') ? 1 : 0;
            console.log(data);
        }

        if (inputCheck) {
            data.name = nameIn.val();
        }

        $.ajax({
            url: '/api/schedule',
            method: 'POST',
            headers,
            data,
            success(response: JQueryAjaxSettings) {
                const data = response.data;
                const outer = data.schedules;
                const updated = self.processListFromServer(outer, schedule.id);
                self.updateCurrentSelected(updated);
                self.isEditMode = false;
                self.render();
            },
            error(xhr, status) {
                alert('There was an error during update');
                console.log(xhr);
                console.log(status);
            }
        });
    }

    private processListFromServer(outerObj: any, toUpdateId: number = -1): Schedule | null {
        const primary: Schedule = outerObj.primary;
        const schedules: Schedule[] = outerObj.schedules;
        this.schedules = schedules;

        let res: Schedule | null = null;

        if (primary != null) {
            schedules.unshift(primary);
        }

        this.renderScheduleList(this.schedules);

        if (toUpdateId == -1) {
            if (primary != null) {
                res = primary;
            } else {
                if (schedules.length > 0) {
                    res = schedules[0];
                }
            }
        } else {
            res = schedules.find(s => s.id == toUpdateId) || null;
        }

        return res;
    }

    private enterEditMode(): void {
        this.onEnterEdit(() => {
            this.isEditMode = true;
            this.render();
        });
    }

    private onCancelEdit(): void {
        this.onExitEdit(false, () => {
            this.isEditMode = false;
            this.render();
        });
    }

    private onSaveEdit(): void {
        const self = this;
        this.onExitEdit(true, () => {
            const selected = this.selected!!;
            const nameInput = this.nameInput;
            const starInput = this.starInput;
            const sp = starInput.find('span');

            const starCheck = (sp.hasClass('glyphicon-star') && selected.is_primary == 0) ||
                (sp.hasClass('glyphicon-star-empty') && selected.is_primary == 1);
            const inCheck = nameInput.val() !== selected.name;

            if (starCheck || inCheck) {
                self.querySaveUpdate(selected, starCheck, inCheck, nameInput, sp);
            } else {
                this.isEditMode = false;
                this.render();
            }

        });
    }

}