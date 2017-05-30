import {Week} from "../data/interfaces";

export function renderMeetDaysDisplay(week: Week, btnGroupClass: string = 'btn-group-sm', parent?: JQuery): JQuery {
    const elem = $(`
        <div class="meet-days-group btn-group ${btnGroupClass}">
            <a style="cursor: default" class="btn ${week.sunday == 1 ? 'btn-primary' : 'btn-default'}"><span>S</span></a>
            <a style="cursor: default" class="btn ${week.monday == 1 ? 'btn-primary' : 'btn-default'}"><span>M</span></a>
            <a style="cursor: default" class="btn ${week.tuesday == 1 ? 'btn-primary' : 'btn-default'}"><span>T</span></a>
            <a style="cursor: default" class="btn ${week.wednesday == 1 ? 'btn-primary' : 'btn-default'}"><span>W</span></a>
            <a style="cursor: default" class="btn ${week.thursday == 1 ? 'btn-primary' : 'btn-default'}"><span>T</span></a>
            <a style="cursor: default" class="btn ${week.friday == 1 ? 'btn-primary' : 'btn-default'}"><span>F</span></a>
            <a style="cursor: default" class="btn ${week.saturday == 1 ? 'btn-primary' : 'btn-default'}"><span>S</span></a>
        </div>
    `);

    if (parent != null) {
        parent.append(elem);
    }
    return elem;
}