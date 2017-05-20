import {Section} from "../data/interfaces";
import {MeetingDaysComponent} from "./meetdays";

export class AddedSectionComponent {

    private selfObj: JQuery;
    private _section: Section;
    private onDelete: (data: AddedSectionComponent) => void;

    private meetDays: MeetingDaysComponent;

    constructor(section: Section, parent: JQuery | string, id: string, onDelete: (data: AddedSectionComponent) => void) {
        this._section = section;
        this.onDelete = onDelete;

        const parentEl = typeof (parent) == "string" ? $('#' + parent) : parent;

        const outer = $('<div class="panel panel-default"></div>');
        this.selfObj = outer;
        const removeBtn = $('<a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>');

        removeBtn.on('click', e => {
            e.preventDefault();
            this.deleteSelf();
            this.onDelete(this);
        });

        const title = $(`
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#${id}">
                    ${section.instructors !== null ? section.instructors : 'Added section'}
                </a>
            </h4>
        `);

        const heading = $(`
            <div class="panel-heading"></div>
        `);

        const bodyOuter = $(`<div id="${id}" class="panel-collapse collapse"> </div>`),
            body = $(`<div class="panel-body"></div>`),
            table = $(`<table class="table table-condensed"></table>`),
            tbody = $(`<tbody></tbody>`);

        tbody.append($(`
            <tr>
                <td>Instructors</td>
                <td>${section.instructors}</td>
            </tr>
            <tr>
                <td>Location</td>
                <td>${section.meetings[0].location}</td>
            </tr>
            <tr>
                <td>Start</td>
                <td>${section.meetings[0].start}</td>
            </tr>
            <tr>
                <td>End</td>
                <td>${section.meetings[0].end}</td>
            </tr>
        `));

        const meetTr = $('<tr></tr>');
        tbody.append(meetTr);

        this.meetDays = new MeetingDaysComponent(false, meetTr, section.meetings[0].week);

        title.append(removeBtn);
        heading.append(title);
        outer.append(heading);

        table.append(tbody);
        body.append(table);
        bodyOuter.append(body);
        outer.append(bodyOuter);
        parentEl.append(outer);
    }

    public get section(): Section {
        return this._section;
    }

    private deleteSelf(): void {
        this.selfObj.remove();
    }

}