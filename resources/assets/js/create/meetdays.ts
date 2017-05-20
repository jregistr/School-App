import {Week} from "../data/interfaces";

/**
 * Reusable component to render and
 */
export class MeetingDaysComponent {

    private editable: boolean;
    private parent: JQuery;
    private _week: Week;

    constructor(editable: boolean, parent: JQuery | string, week?: Week) {
        this.editable = editable;
        this.parent = typeof (parent) == "string" ? $('#' + parent) : parent;
        if (week != null) {
            this.week = week;
        } else {
            this.week = {
                sunday: 0,
                monday: 0,
                tuesday: 0,
                wednesday: 0,
                thursday: 0,
                friday: 0,
                saturday: 0
            };
        }
    }

    public get week(): Week {
        return this._week;
    }

    public set week(week: Week) {
        this._week = week;
        this.render();
    }

    public render(): void {
        this.parent.empty();
        this.parent.append(this.jqueryOut());
    }

    public clear(): void {
        this.parent.find('input[type="checkbox"]').each((index, input) => {
            $(input).prop('checked', false)
                .trigger('change');
        });
    }

    private jqueryOut(): JQuery {
        const week = this.week;
        const outer = $('<div class="col-lg-12"></div>');

        Object.keys(week).forEach(day => {
            const val = week[day];
            const f = day.charAt(0);
            outer.append(this.makeCheckbox(day, f, val));
        });
        return outer;
    }

    private makeCheckbox(fullLabel: string, label: string, value: number): JQuery {
        const input = $(`
            <input ${!this.editable ? 'disabled' : ''} type="checkbox"  ${value == 1 ? 'checked="checked"' : ''} />
        `);

        if (this.editable) {
            input.on('change', () => {
                this.setDayValue(fullLabel, input.prop('checked') ? 1 : 0);
            });
        }
        return $(`<label class="checkbox-inline"> </label>`)
            .append(input)
            .append(label);
    }

    private setDayValue(fullLabel: string, value: number): void {
        this.week[fullLabel] = value;
    }

}