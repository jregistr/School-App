import {AddCourseComponent} from '../create/addcourse';
import {ViewCoursesComponent} from "../create/viewcourses";
import {Course, Meeting, ScheduledCourse, Section} from "../data/interfaces";
import {MeetingDaysComponent} from "../create/meetdays";

class CreateProgram {

    private static _instance: CreateProgram;

    private genListElement = $('#generate-candidates-list');
    private coursesTab = $('a[href="#courses"]');
    private sectionsTab = $('a[href="#sections"]');
    private generateTab = $('a[href="#added"]');

    private genList: { course: ScheduledCourse, required: boolean }[] = [];

    private constructor() {

    }

    static get instance(): CreateProgram {
        if (CreateProgram._instance == null) {
            CreateProgram._instance = new CreateProgram();
        }
        return CreateProgram._instance;
    }

    init(): void {
        const m: Meeting = {
            id: 0,
            start: "10:20",
            end: "12:30",
            location: 'shineman',
            week: {
                sunday: 0,
                monday: 1,
                tuesday: 0,
                wednesday: 1,
                thursday: 0,
                friday: 1,
                saturday: 0
            }
        };

        const s: Section = {
            id: 0,
            course_id: 0,
            instructors: 'instruc',
            meetings: [m]
        };

        const c: ScheduledCourse = {
            id: 0,
            school_id: 0,
            name: 'CSC 244',
            crn: 23442,
            credits: 4,
            section: s
        };

        this.addToGenList(c, 0);
        this.addToGenList(c, 1);
    }

    private addToGenList(course: ScheduledCourse, trCount: number): void {
        const id = `addedGenItem${trCount}`;
        const section = course.section;

        const outer = $('<div class="panel panel-default" style="margin-bottom: 0; padding: 0;"></div>');
        const remove = $(`
                <button style="padding: 0" href="#" class="pull-right btn btn-danger">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>`);

        const title = $(`
            <a class="panel-title">
                <span class="active" data-toggle="collapse" data-parent="#accordion" href="#${id}" >
                    ${course.name} - ${section.instructors != null ? section.instructors : ''}
                </span>
            </a>
        `).append(remove)
            .append(`
                <div class="pull-right" style="margin-right: 10px">
                    <input style="" class="" type="checkbox" />
                    <label class="" style="padding-right: 10px">
                        Required
                    </label>
                </div>`);

        const heading = $(`
            <div class="panel-heading" style="padding: 2px 10px 0 10px"></div>
        `);

        const bodyOuter = $(`<div id="${id}" class="panel-collapse collapse"> </div>`),
            body = $(`<div class="panel-body" style="padding: 0"></div>`),
            table = $(`<table class="table table-condensed" style="margin-bottom: 0"></table>`),
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

        new MeetingDaysComponent(false, meetTr, section.meetings[0].week);

        heading.append(title);
        outer.append(heading);

        table.append(tbody);
        body.append(table);
        bodyOuter.append(body);
        outer.append(bodyOuter);

        const self = this;
        remove.on('click', () => {
            console.log('HELLO');
            outer.remove();
            const index = self.genList.findIndex(e => e.course == course);
            self.genList.splice(index, 1);
        });

        this.genListElement.append(outer);
    }

}

$(document).ready(() => {

    CreateProgram.instance.init();


    //
    // const addCourseForm = new AddCourseComponent($('#add-class'), afterCourseSubmit, 1);
    // const viewCoursesTable = new ViewCoursesComponent($('#courses'), onViewCourseRowClicked);
    //
    // function afterCourseSubmit() {
    //
    // }
    //
    // function onViewCourseRowClicked(course:Course) {
    //    sectionsTab.tab('show');
    // }

    // function addToGenerateCandidates(section: Section): void {
    //
    // }


});