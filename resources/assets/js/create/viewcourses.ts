import {headers, student_id} from "../common/functions";
import {Course} from "../data/interfaces";
import {Component} from "../data/component";
import {SearchDropdownComponent} from "../common/searchdropdown";

export class ViewCoursesComponent implements Component {

    parent: JQuery;
    private table: JQuery;
    private categories: string[];
    private courses: Course[];
    private searchMenu: SearchDropdownComponent;

    constructor(parent: JQuery, toolbarId: string = 'tableToolbar') {
        this.parent = parent;
        this.table = ViewCoursesComponent.createTableElem();

        this.parent.append($(`<div></div>`).append(this.table));
        const tb = ViewCoursesComponent.createToolbar(toolbarId);
        this.parent.append(tb);
        this.initTable(toolbarId);
        this.searchMenu = new SearchDropdownComponent(tb, this.onSubjectSelect.bind(this),
            [], 'All subjects');
        this.loadData();

        const self = this;
    }

    render(): void {
        this.parent.hide();
    }

    hide(): void {
        this.parent.show();
    }

    private loadData():void {
        const self = this;
        self.table.bootstrapTable('showLoading');
        $.ajax({
            headers,
            url: '/api/course',
            method: 'GET',
            data: {
                student_id
            }
        }).done(r => {
            const data = r.data;
            self.table.bootstrapTable('hideLoading');
            self.categories = data.subjects.sort((a: string, b: string) => a.localeCompare(b));
            self.courses = data.courses;
            self.searchMenu.data = self.categories;
        }).fail((xhr, status) => {
            alert('Fail to load course data');
        });
    }

    private onSubjectSelect(subj: string): void {
        if (this.courses != null && this.courses.length > 0) {
            const filtered = subj === 'All subjects' ? this.courses :
                this.courses.filter(course => course.name.toLowerCase().indexOf(subj.toLowerCase()) != -1);
            this.table.bootstrapTable('load', filtered);
        }
    }

    private initTable(toolbarId: string): void {
        const self = this;
        this.table.bootstrapTable({
            striped: true,
            pagination: true,
            pageSize: 20,
            toolbar: '#' + toolbarId,
            showRefresh: true,
            showToggle: true,
            showColumns: true,
            onRefresh:function () {
                self.loadData();
            }
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

}