import {Component} from "../data/component";

export class SearchDropdownComponent<T extends { name: string }> implements Component {

    parent: JQuery;
    private onSelect: (selected: T) => void;
    private outer: JQuery;
    private _data: T[] = [];
    private defaultSelected: T | null;
    private ul: JQuery;
    private currentSpan: JQuery;
    private inputBox: JQuery;
    private options: any | null;

    constructor(parent: JQuery, onSelect: (selected: T) => void,
                options?: {
                    renderMenuItem: (item: T) => string
                }) {
        this.parent = parent;
        this.onSelect = onSelect;
        this.options = options;
        // this.defaultSelected = defaultSelected;

        const create = SearchDropdownComponent.init();
        this.outer = create.outer;
        this.ul = create.ul;
        this.currentSpan = create.currentSpan;
        this.inputBox = create.inputBox;
        this.parent.append(this.outer);

        create.inputBox.keyup(() => {
            this.onInputBoxChange(create.inputBox);
        });

        // this.data = data;
    }

    public setData(data: T[], defaultSelected: T | null): void {
        this._data = data;
        this.defaultSelected = data.length > 0 ? defaultSelected : null;
        if (defaultSelected == null) {
            this.defaultSelected = data[0];
        }
        this.renderItems(this._data);
        this.setSelected(this.defaultSelected);
    }

    render(): void {
        this.parent.show();
    }

    hide(): void {
        this.parent.hide();
    }

    private onItemClicked(value: T): void {
        this.setSelected(value);
        this.renderItems(this._data);
        this.inputBox.val('');
    }

    private setSelected(value: T | null): void {
        if (value != null) {
            this.currentSpan.empty();
            // const i = value.name.lastIndexOf('>');
            // const text = i == -1 ? value.name : value.name.substring(i + 1, value.name.length);
            this.currentSpan.append(value.name);
            this.onSelect(value);
        } else {
            this.currentSpan.empty();
        }
    }

    private renderItems(items: T[]): void {
        const ul = this.ul;
        const self = this;
        ul.empty();

        function render(item: T) {
            const a = $(`<a>
                ${self.options != null && self.options.renderMenuItem != null ?
                self.options.renderMenuItem(item)
                : item.name
                }
                </a>`);
            a.on('click', () => {
                self.onItemClicked(item);
            });

            ul.append($('<li></li>').append(a));
        }

        if (this.defaultSelected != null) {
            render(this.defaultSelected);
            items.forEach(item => render(item));
        }
    }

    private onInputBoxChange(inputBox: JQuery): void {
        console.log('HELLO');
        const input = inputBox.val();
        if (input != null && input.length > 0) {
            const filtered = this._data
                .filter(value => value.name.toLocaleLowerCase().indexOf(input.toLocaleLowerCase()) != -1);
            this.renderItems(filtered);
        } else {
            this.renderItems(this._data);
        }
    }

    private static init(): { outer: JQuery, currentSpan: JQuery, ul: JQuery, inputBox: JQuery } {
        const outer = $(`
            <div class="dropdown">
            
            </div>
        `);

        const dropBtn = $(`
            <button type="button" style="width: 100%!important;"
            class="btn btn-default dropdown-toggle searchdrop-picker" data-toggle="dropdown">
            </button>
        `);

        const currentSpan = $(`<span class="pull-left filter-option"></span>`);

        dropBtn
            .append(currentSpan)
            .append($(`<span class="pull-right"><span class="caret"></span></span>`));

        outer.append(dropBtn);

        const menuOuter = $(`
            <div class="dropdown-menu 
            open searchdrop-menu"></div>
        `);

        const inputBox = $(`<input type="text" class="form-control" />`);
        menuOuter.append($(`<div class="searchdrop-box"></div>`).append(inputBox));

        const ul = $(`
            <ul style="width: 100%!important;" class="dropdown-menu inner" role="menu">
            </ul>
        `);

        menuOuter.append(ul);

        outer.append(menuOuter);
        return {outer, currentSpan, ul, inputBox};
    }

}