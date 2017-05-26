import {Component} from "../data/component";

export class SearchDropdownComponent implements Component {

    parent: JQuery;
    private outer: JQuery;
    private _data: string[];

    constructor(parent: JQuery, onSelect: (selected: string) => void, data: string[] = []) {
        this.parent = parent;
        this.data = data;
        this.outer = SearchDropdownComponent.init();
        this.parent.append(this.outer);
    }

    set data(items: string[]) {
        this._data = items;
    }

    render(): void {
        this.outer.show();
    }

    hide(): void {
        this.outer.hide();
    }

    private static init():JQuery {
        const outer = $(`
            <div class="btn-group">
            
            </div>
        `);

        return outer;
    }

}