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

    constructor(parent: JQuery, onEnterEdit: () => void, onExitEdit: () => void,
                onSelectionChange: (schedule: Schedule) => void) {
        this.parent = parent;
        this.onEnterEdit = onEnterEdit;
        this.onExitEdit = onExitEdit;
        this.onSelectionChange = onSelectionChange;

        this.defaultView = this.makeDefaultView();
        this.editView = this.makeEditModeView();
        this.render();
    }

    public render(): void {
        const p = this.parent;
        const ap = this.isEditMode ? this.editView : this.defaultView;
        p.empty();
        p.append(ap);
        p.show();
    }

    public hide(): void {
        this.parent.hide();
    }

    private makeDefaultView(): JQuery {
        const outer = $(`<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 schedule-toolbar-outer"></div>`);

        const trashBtn = $(`<button class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button>`);
        outer.append($(`<div class="pull-left"></div>`).append(trashBtn));

        const rightOuter = $(`<div class="form-inline pull-right"></div>`);
        this.dropDown = new SearchDropdownComponent(rightOuter, this.onSelectionChange);
        return outer;
    }

    private makeEditModeView(): JQuery {
        return $(`<h3>Not yet implemented</h3>`);
    }

}