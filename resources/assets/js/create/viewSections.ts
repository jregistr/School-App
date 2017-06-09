import {Component} from "../data/component";
import {Course, ScheduledCourse, Section} from "../data/interfaces";
import {headers} from "../common/functions";
import {renderMeetDaysDisplay} from "./renderMeetDisplay";
import * as moment from "moment";
import {transcribe} from "../common/transcribe";

export class ViewSectionsComponent implements Component {

    parent: JQuery;
    private _course: Course;
    private table: JQuery;
    private toolbar: JQuery;

    constructor(parent: JQuery, toolbarId: string,
                addToGenerator: (scheduledCourse: ScheduledCourse) => void) {
        this.parent = parent;

        this.table = ViewSectionsComponent.createTableElem();
        this.parent.append(this.table);
        const self = this;
        window['addSectionToGenListWindowFunction'] = (function (sectionId: number, meetingId: number) {
            const data: any = (self.table.bootstrapTable('getData'));
            const sections: Section[] = data as Section[];
            const section = (sections.find(s => s.course_id == self._course.id && s.id == sectionId))!!;
            const meeting = (section.meetings.find(m => m.id == meetingId))!!;

            const temp: Section = Object.assign({}, section);
            temp.meetings = [meeting];

            const trans = transcribe(self._course, [temp]);
            addToGenerator(trans);
        });
        const toolbar = $(`
            <div id="${toolbarId}" class="text">
                
            </div>
        `);
        this.parent.append(toolbar);
        this.toolbar = toolbar;
        this.initTable(toolbarId);
    }

    set course(course: Course) {
        this._course = course;
        this.toolbar.empty();
        this.toolbar.text(course.name);
        this.table.bootstrapTable('refresh');
    }

    render(): void {
        this.parent.show();
    }

    hide(): void {
        this.parent.hide();
    }

    private initTable(toolbarId: string): void {
        const self = this;

        function locationFormat(value: any, row: Section) {
            const outer = $(`<div style="display: block;"></div>`);
            console.log(row.meetings[0].location);
            row.meetings.forEach(meeting => {
                outer.append($(`<span>${meeting.location}</span>`));
            });
            return outer.html();
        }

        function meetingTime(value: any, section: Section) {
            const temp = $('<div></div>');
            const outer = $(`<div class="display-meeting-group"></div>`);
            const meetings = section.meetings;

            meetings.forEach(meeting => {
                const week = meeting.week;
                const noDays = week.sunday == 0 && week.monday == 0 && week.tuesday == 0 && week.wednesday == 0
                    && week.thursday == 0 && week.friday == 0 && week.saturday == 0;
                const row = $('<div class="row display-meeting" style="width: 90%!important; margin-left: 10px"></div>');
                const daysOuter = $(`<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>`);
                outer.append(row);
                row.append(daysOuter);
                if (noDays == true) {
                    daysOuter.append($('<h5>ONLINE</h5>'))
                } else {
                    renderMeetDaysDisplay(meeting.week, 'btn-group-xs', daysOuter);
                }

                if (!noDays) {
                    row.append($(`<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 "></div>`).append(`
                          <h6>
                            <strong>${moment(meeting.start, ["HH:mm"]).format("h:mm A")}</strong>
                            to <strong>${moment(meeting.end, ["HH:mm"]).format("h:mm A")}</strong>
                            at ${meeting.location}
                          </h6>`
                    ));
                }

                const onC = `addSectionToGenListWindowFunction(${section.id}, ${meeting.id})`;
                const addBtn = $(`<button onclick="${onC}" 
                        class="btn btn-info">Add To List</button>`);
                row.append($(`<div class="${!noDays ? 'col-lg-3 col-md-3 col-sm-3' : 'col-lg-12 col-md-12 col-sm-12'} 
                        col-xs-12"></div>`)
                    .append(addBtn));
            });

            temp.append(outer);
            return temp.html();
        }

        window['locationFormat'] = locationFormat.bind(this);
        window['meetingTime'] = meetingTime.bind(this);

        function ajax(params: any) {
            if (self._course != null) {
                $.ajax({
                    url: '/api/course/section',
                    method: "GET",
                    headers,
                    data: {
                        course_id: self._course.id
                    },
                    success(response) {
                        const sections: Section[] = response.data.sections;
                        params.success(sections);
                    },
                    error(xhr, status) {
                        alert('There was an error loading sections data.')
                    }
                });
            } else {
                params.success([]);
            }
        }

        this.table.bootstrapTable({
            striped: true,
            pagination: true,
            pageSize: 20,
            showRefresh: true,
            search: true,
            cardView: true,
            toolbar: `#${toolbarId}`,
            ajax,
            rowStyle: () => {
                return {
                    classes: '',
                    css: {"cursor": "pointer"}
                }
            }
        });
    }

    private static createTableElem(): JQuery {
        return $(`
         <table class="view-sections-table">
             <thead>
                 <tr>
                     <th data-field="instructors">Instructor(s)</th>
                     <th data-formatter="meetingTime">Meeting(s)</th>
                 </tr>
             </thead>
         </table>
     `);
    }

}