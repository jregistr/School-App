import {Component} from "../data/component";

export class SearchDropdownComponent implements Component {

    parent: JQuery;
    private onSelect: (selected: string) => void;
    private outer: JQuery;
    private _data: string[];
    private defaultSelected: string | null;
    private ul: JQuery;
    private currentSpan: JQuery;
    private inputBox:JQuery;

    constructor(parent: JQuery, onSelect: (selected: string) => void, data: string[] = [],
                defaultSelected: string | null = null) {

        this.parent = parent;
        this.onSelect = onSelect;
        this.defaultSelected = defaultSelected;

        const create = SearchDropdownComponent.init();
        this.outer = create.outer;
        this.ul = create.ul;
        this.currentSpan = create.currentSpan;
        this.inputBox = create.inputBox;
        this.parent.append(this.outer);

        create.inputBox.keyup(() => {
            this.onInputBoxChange(create.inputBox);
        });

        this.data = data;
    }

    set data(items: string[]) {
        this._data = items;
        this.renderItems(items);

        let value: string | null = null;

        if (this.defaultSelected != null) {
            value = this.defaultSelected;
        } else {
            if (this._data.length > 0) {
                value = this._data[0];
            }
        }

        if (value != null) {
            this.onItemClicked(value);
        }
    }

    render(): void {
        this.outer.show();
    }

    hide(): void {
        this.outer.hide();
    }

    private onItemClicked(value: string): void {
        this.currentSpan.text(value);
        this.onSelect(value);
        this.renderItems(this._data);
        this.inputBox.val('');
    }

    private renderItems(items: string[]): void {
        const ul = this.ul;
        const self = this;
        ul.empty();

        function render(item: string) {
            const a = $(`<a>${item}</a>`);
            a.on('click', () => {
                self.onItemClicked(item);
            });

            ul.append($('<li></li>').append(a));
        }

        if (this.defaultSelected != null) {
            render(this.defaultSelected);
        }

        items.forEach(item => render(item));
    }

    private onInputBoxChange(inputBox: JQuery): void {
        const input = inputBox.val();
        if (input != null && input.length > 0) {
            const filtered = this._data
                .filter(value => value.toLocaleLowerCase().indexOf(input.toLocaleLowerCase()) != -1);
            this.renderItems(filtered);
        } else {
            this.renderItems(this._data);
        }
    }

    private static init(): { outer: JQuery, currentSpan: JQuery, ul: JQuery, inputBox: JQuery } {
        const outer = $(`
            <div class="btn-group searchdrop">
            
            </div>
        `);

        const dropBtn = $(`
            <button type="button" class="btn btn-default dropdown-toggle searchdrop-picker" data-toggle="dropdown">
                
            </button>
        `);

        const currentSpan = $(`<span class="pull-left filter-option"></span>`);

        dropBtn
            .append(currentSpan)
            .append($(`<div class="pull-right"><span class="caret"></span></div>`));

        outer.append(dropBtn);

        const menuOuter = $(`
            <div class="dropdown-menu open searchdrop-menu"></div>
        `);

        const inputBox = $(`<input type="text" class="form-control" />`);
        menuOuter.append($(`<div class="searchdrop-box"></div>`).append(inputBox));

        const ul = $(`
            <ul class="dropdown-menu inner searchdrop-picker" role="menu">
            </ul>
        `);

        menuOuter.append(ul);

        outer.append(menuOuter);
        return {outer, currentSpan, ul, inputBox};
    }

}