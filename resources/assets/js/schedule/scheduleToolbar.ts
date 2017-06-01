import {Component} from "../data/component";
import {Schedule} from "../data/interfaces";
import {SearchDropdownComponent} from "../common/searchdropdown";

export class ScheduleToolbar implements Component {

    parent: JQuery;
    private isEditMode: boolean = false;
    private onEnterEdit: () => void;
    private onExitEdit: (save: boolean) => void;
    private onSelectionChange: (schedule: Schedule) => void;

    private defaultView: JQuery;
    private editView: JQuery;

    private dropDown: SearchDropdownComponent<Schedule>;
    private schedules: Schedule[] = [];
    private selected: Schedule | null = null;
    private primStar: JQuery = $(`<span class="glyphicon glyphicon-star"></span>`);

    constructor(parent: JQuery, onEnterEdit: () => void, onExitEdit: (save: boolean) => void,
                onSelectionChange: (schedule: Schedule) => void) {
        this.parent = parent;
        this.onEnterEdit = onEnterEdit;
        this.onExitEdit = onExitEdit;
        this.onSelectionChange = onSelectionChange;

        this.defaultView = this.makeDefaultView();
        this.editView = this.makeEditModeView();
        this.primStar.hide();
        this.parent.append(this.defaultView);
        this.parent.append(this.editView);
        this.render();
    }

    public render(): void {
        this.renderView();
        this.primStarState();
    }

    private renderView() {
        if (this.isEditMode) {
            this.editView.show();
            this.defaultView.hide();
        } else {
            this.editView.hide();
            this.defaultView.show();
        }
    }

    private primStarState() {
        if (!this.isEditMode) {
            if (this.selected != null && this.selected.is_primary) {
                this.primStar.show();
            } else {
                this.primStar.hide();
            }
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

    private makeDefaultView(): JQuery {
        const outer = $(`<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 schedule-toolbar-outer"></div>`);
        const trash = $(`<a href="#"><span class="glyphicon glyphicon-trash"></span></a>`);

        const confirmDeleteModal = $('#confirmModal');
        const confirmBtn = confirmDeleteModal.find('button[class="btn btn-danger"]');
        confirmBtn.on('click', this.onDeleteSchedule.bind(this));
        trash.on('click', e => {
            e.preventDefault();
            confirmDeleteModal.modal('show');
        });

        outer.append($(`<div class="pull-left schedule-toolbar-trash-outer"></div>`).append($(`<ul></ul> `)
            .append($(`<li></li>`).append(trash))));

        const rightOuter = $(`<div class="pull-right schedule-toolbar-right"></div>`);

        const selectOuter = $(`<div style="width: 200px;" class="pull-right"></div>`);
        const othersOuter = $(`<div style="margin-top: 3px" class="pull-left btn-group btn-group-sm"></div>`);

        const self = this;
        this.dropDown = new SearchDropdownComponent(selectOuter, (selected: Schedule) => {
                self.selected = selected;
                self.primStarState();
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
        rightOuter.append($(`<div style="margin-top: 7px; margin-right: 10px" class="pull-left"></div>`)
            .append(this.primStar));
        rightOuter.append(othersOuter);
        outer.append(rightOuter);

        const editBtn = $(`<button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></button>`);
        editBtn.on('click', self.onEditClicked.bind(self));
        othersOuter.append(editBtn);
        return outer;
    }

    private makeEditModeView(): JQuery {
        return $(`<h3>Not yet implemented</h3>`);
    }

    private onEditClicked(): void {
        this.isEditMode = true;
        this.render();
        this.onEnterEdit();
    }

    private onSaveInEditClicked(): void {

    }

    private onCancelInEditClicked(): void {

    }

    private onDeleteSchedule(): void {
        console.log('DELETE schedule:' + this.selected!!.name);
    }


}