import {Component} from "../data/component";
import {Course, Section} from "../data/interfaces";
import {headers} from "../common/functions";

export class ViewSectionsComponent implements Component {

    parent: JQuery;
    private _course: Course;
    private table: JQuery;

    constructor(parent: JQuery) {
        this.parent = parent;
        this.table = ViewSectionsComponent.createTableElem();
        this.parent.append(this.table);
        this.initTable();
    }

    set course(course: Course) {
        this._course = course;
        this.table.bootstrapTable('refresh');
    }

    render(): void {
        this.parent.show();
    }

    hide(): void {
        this.parent.hide();
    }

    private initTable(): void {
        const self = this;

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
            showToggle: true,
            search: true,
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
            <table>
                <thead>
                    <tr>
                        <th data-field="instructors">Instructor(s)</th>
                    </tr>
                </thead>
            </table>
        `);
    }

}