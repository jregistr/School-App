import {headers, student_id} from "../common/functions";
export class ViewCoursesComponent {

    private parent: JQuery;
    private table: JQuery;
    private categories: String[];

    constructor(parent: JQuery, toolbarId?: string) {
        this.parent = parent;
        this.table = ViewCoursesComponent.createTableElem();

        const tid = toolbarId != null ? toolbarId : 'tableToolbar';
        const toolbaar = ViewCoursesComponent.createToolbar(tid);

        this.parent.append(toolbaar);
        this.parent.append(this.table);

        this.initTable(tid);
    }

    private initTable(toolbarId: string): void {
        function ajax(this: ViewCoursesComponent, params: any) {
            $.ajax({
                headers,
                url: '/api/course',
                method: 'GET',
                data: {
                    student_id
                }
            }).done(r => {
                const data = r.data;
                this.categories = data.subjects;
                const courses = data.courses;
                params.success(courses);
            }).fail((xhr, status) => {
                alert('Fail to load course data');
            });
        }

        this.table.bootstrapTable({
            striped: true,
            pagination: true,
            pageSize: 20,
            ajax,
            toolbar: '#' + toolbarId,
            showRefresh: true,
            showToggle: true,
            showColumns: true,
            // search: true
        });
    }

    private static createTableElem(): JQuery {
        return $(`
            <table data-sort-order="asc">
                <thead>
                    <tr>
                        <th data-field="name" data-sortable="true" class="col-lg-6 col-md-6 col-sm-7 col-xs-7">Name</th>
                        <th data-field="crn" class="col-lg-4 col-md-4 col-sm-5 col-xs-5">Crn</th>
                        <th data-field="credits" class="col-lg-2 col-md-2 col-sm-1 col-xs-1">Credits</th>
                    </tr>
                </thead>
            </table>
        `);
    }

    private static createToolbar(id: String): JQuery {
        const div = $(`<div class="toolbar-group" id="${id}"></div>`);
        const fg = $(`
            <div class="form-group">
                <span><strong>Subjects</strong></span>
            </div>
        `);
        const sel = $(`
            <select class="toolbar-select" multiple="multiple">
                 <option>Hello</option>
                 <option>Hello 2</option>
                 <option>Hello 3</option>
            </select>
        `);
        fg.append(sel);

        sel.select2();

        div.append(fg);
        return div;
    }

}