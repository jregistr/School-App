import {Component} from "../data/component";
import {Schedule} from "../data/interfaces";
import {SearchDropdownComponent} from "../common/searchdropdown";

export class ScheduleToolbar implements Component {

    parent: JQuery;
    private isEditMode: boolean = false;
    private onEnterEdit: () => void;
    private onExitEdit: () => void;
    private onSelectionChange: (schedule: Schedule) => void;

    private defaultView: JQuery;
    private editView: JQuery;

    private dropDown: SearchDropdownComponent<Schedule>;
    private schedules: Schedule[] = [];
    private selected: Schedule | null = null;
    private primStar: JQuery = $(`<span class="glyphicon glyphicon-star"></span>`);

    constructor(parent: JQuery, onEnterEdit: () => void, onExitEdit: () => void,
                onSelectionChange: (schedule: Schedule) => void) {
        this.parent = parent;
        this.onEnterEdit = onEnterEdit;
        this.onExitEdit = onExitEdit;
        this.onSelectionChange = onSelectionChange;

        this.defaultView = this.makeDefaultView();
        this.editView = this.makeEditModeView();
        this.primStar.hide();
        this.render();
    }

    public render(): void {
        const p = this.parent;
        const ap = this.isEditMode ? this.editView : this.defaultView;
        p.empty();
        p.append(ap);
        p.show();
        if (this.selected != null && this.selected.is_primary) {

        }
    }

    public hide(): void {
        this.parent.hide();
    }

    private makeDefaultView(): JQuery {
        const outer = $(`<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 schedule-toolbar-outer"></div>`);
        const trash = $(`<a href="#"><span class="glyphicon glyphicon-trash"></span></a>`);
        outer.append($(`<div class="pull-left schedule-toolbar-trash-outer"></div>`).append($(`<ul></ul> `)
            .append($(`<li></li>`).append(trash))));

        const rightOuter = $(`<div class="pull-right schedule-toolbar-right"></div>`);

        const selectOuter = $(`<div class="pull-right"></div>`);
        const othersOuter = $(`<div style="margin-top: 3px" class="pull-left btn-group btn-group-sm"></div>`);

        this.dropDown = new SearchDropdownComponent(selectOuter, this.onSelectionChange, {leftAlign: false});
        this.dropDown.render();

        rightOuter.append(selectOuter);
        rightOuter.append($(`<div style="margin-top: 7px; margin-right: 10px" class="pull-left"></div>`)
            .append(this.primStar));
        rightOuter.append(othersOuter);
        outer.append(rightOuter);

        const editBtn = $(`<button class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></button>`);
        othersOuter.append(editBtn);
        return outer;
    }

    private makeEditModeView(): JQuery {
        return $(`<h3>Not yet implemented</h3>`);
    }

}