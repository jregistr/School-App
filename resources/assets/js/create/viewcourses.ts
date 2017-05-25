import {headers, student_id} from "../common/functions";
import {Course} from "../data/interfaces";

export class ViewCoursesComponent {

    private parent: JQuery;
    private table: JQuery;
    private categories: string[];
    private courses: Course[];
    private subjectParent: JQuery;

    constructor(parent: JQuery) {
        this.parent = parent;
        this.table = ViewCoursesComponent.createTableElem();

        this.parent.append($(`<div></div>`).append(this.table));
        this.initTable();
        const toolbar = ViewCoursesComponent.createToolbar(this.table);
        this.subjectParent = toolbar.find('div[class="pull-left form-horizontal"]');
        this.parent.prepend(toolbar);
    }

    private initTable(): void {
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
                self.renderSubjects();
            }).fail((xhr, status) => {
                alert('Fail to load course data');
            });
        }

        this.table.bootstrapTable({
            striped: true,
            pagination: true,
            pageSize: 20,
            ajax
        });
    }

    private renderSubjects(): void {
        if (this.categories.length > 0) {
            this.categories.sort((a, b) => {
                return a.localeCompare(b);
            });
            const outer = $(`
                <div class="form-group" style="margin-bottom: 0!important;">
                    <label class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top: 5px">Subjects</label>
                </div>
            `);
            const sel = $(`
                <select class="form-control">
                    <option value="-1">All Subjects</option>
                </select>
            `);

            this.categories.forEach(category => {
                sel.append($(`<option value="${category}">${category}</option>`));
            });

            sel.on('change', () => {
                this.table.bootstrapTable('filterBy', {name : sel.val()});
            });

            outer.append($(`<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"></div>`).append(sel));
            this.subjectParent.empty();
            this.subjectParent.append(outer);


        }
        // <div class="form-group" style="margin-bottom: 0!important;">
        // <label class="col-xs-4" style="margin-top: 5px">Subjects</label>
        // <div class="col-xs-8">
        // <select class=" form-control">
        //     <option>Hello</option>
        //     <option>Hello 1</option>
        // <option>Hello 2</option>
        // </select>
        // </div>
        // </div>
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

    private static createToolbar(table: JQuery): JQuery {
        const outer = $(`<div class=""><div class="" style="padding: 5px"></div></div>`);
        const refreshBtn = $(`
            <button type="button" class="btn btn-default" style=";">
                <span class="glyphicon glyphicon-refresh"></span>
            </button>
        `);

        const listViewBtn = $(`
            <button type="button" class="btn btn-default">
                <span class="glyphicon glyphicon-list-alt"></span>
            </button>
        `);

        outer.append(
            $(`<div class="btn-group pull-right"></div>`)
                .append(refreshBtn)
                .append(listViewBtn)
        );

        const selOuter = $(`
            <div class="pull-left form-horizontal">
               
            </div>
        `);

        listViewBtn.on('click', () => {
            table.bootstrapTable('toggleView');
        });

        refreshBtn.on('click', () => {
            table.bootstrapTable('refresh')
        });

        outer.append(selOuter);

        return outer.append($(`<div class="row" style="padding: 5px;"></div>`));
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