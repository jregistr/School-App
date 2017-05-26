import {headers, student_id} from "../common/functions";
import {Course} from "../data/interfaces";
import {Component} from "../data/component";

export class ViewCoursesComponent implements Component {

    parent: JQuery;
    private table: JQuery;
    private categories: string[];
    private courses: Course[];
    private subjectParent: JQuery;

    constructor(parent: JQuery, toolbarId: string = 'tableToolbar') {
        this.parent = parent;
        this.table = ViewCoursesComponent.createTableElem();

        this.parent.append($(`<div></div>`).append(this.table));
        const tb = this.subjectParent = ViewCoursesComponent.createToolbar(toolbarId);
        this.parent.append(tb);
        this.initTable(toolbarId);
    }

    render(): void {
        this.parent.hide();
    }

    hide(): void {
        this.parent.show();
    }

    private initTable(toolbarId: string): void {
        const self = this;

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
                self.categories = data.subjects;
                const courses = data.courses;
                self.courses = courses;
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

    private static createToolbar(toolbarId: string): JQuery {
        return $(`<div id="${toolbarId}"></div>`);
    }

    // private static addSelect(parent:JQuery):void {
    //     parent.append($(`
    //         <div class="pull-right search form-group">
    //             <!--<label>Search</label>-->
    //             <!--<input class="form-control" type="text" placeholder="Searchies">-->
    //             <select class="form-control">
    //                 <option>Hello</option>
    //                 <option>Hello 2</option>
    //                 <option>Hello 3</option>
    //             </select>
    //         </div>
    //     `));
    // }

    /* private static createToolbar(id: String): JQuery {
     return $(`
     <div id="${id}">
     <!--<label class="pull-right">Subjects</label>-->
     </div>
     `);
     // const div = $(`<div class="toolbar-group" id="${id}"></div>`);
     // const fg = $(`
     //     <div class="form-group">
     //         <span><strong>Subjects</strong></span>
     //     </div>
     // `);
     // const sel = $(`
     //     <select class="toolbar-select">
     //          <option>Hello</option>
     //          <option>Hello 2</option>
     //          <option>Hello 3</option>
     //     </select>
     // `);
     // fg.append(sel);
     //
     //
     // div.append(fg);
     // return div;
     }*/

}