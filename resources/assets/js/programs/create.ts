import {AddCourseComponent} from '../create/addcourse';
import {ViewCoursesComponent} from "../create/viewcourses";
import {Course, Meeting, ScheduledCourse, Section} from "../data/interfaces";
import {MeetingDaysComponent} from "../create/meetdays";

class CreateProgram {

    private static _instance: CreateProgram;

    private genListTable = $('#generate-candidates');
    private coursesTab = $('a[href="#courses"]');
    private sectionsTab = $('a[href="#sections"]');
    private generateTab = $('a[href="#added"]');
    private courseInfoModal: JQuery = $('#courseInfoModal');

    private addNewBtn = $('#addNew');
    private clearBtn = $('#clearAll');
    private genBtn = $('#genSch');

    private genList: { course: ScheduledCourse, element: JQuery }[] = [];

    private constructor() {

    }

    static get instance(): CreateProgram {
        if (CreateProgram._instance == null) {
            CreateProgram._instance = new CreateProgram();
        }
        return CreateProgram._instance;
    }

    init(): void {

        this.clearBtn.on('click', () => {
            this.genList.forEach((gen) => {
                gen.element.remove();
            });
            this.genList = [];
        });

        const self = this;
        this.addNewBtn.on('click', () => {
            const title = this.courseInfoModal.find('h4[class="modal-title"]');
            const mBody = this.courseInfoModal.find('div[class="modal-body"]');
            mBody.empty();
            title.empty();
            title.append('Add new Course');
            new AddCourseComponent(mBody, (course, sections) => {
                if (sections.length > 0) {
                    const scheduledCourse: ScheduledCourse = {
                        id: course.id,
                        name: course.name,
                        crn: course.crn,
                        credits: course.credits,
                        school_id: course.school_id,
                        section: sections[0]
                    };
                    self.courseInfoModal.modal('hide');
                    CreateProgram.addToGenList(scheduledCourse, self.genListTable, self.genList);
                } else {
                    alert('No sections received');
                }
            }, 1);
            this.courseInfoModal.modal('show');
        });

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
        CreateProgram.addToGenList(c, this.genListTable, this.genList);
        CreateProgram.addToGenList(c, this.genListTable, this.genList);
    }

    private static addToGenList(course: ScheduledCourse, table: JQuery, array: { course: ScheduledCourse, element: JQuery }[]): void {
        const sec = course.section;
        const a = $(`
            <a href="#"><strong>${course.name} - ${sec.instructors != null ? sec.instructors : 'Section'}</strong></a>
        `);

        const required = $(`<input type="checkbox" checked>`);
        const del = $(`<a class="" href="#"><span class="glyphicon glyphicon-remove"></span></a>`);
        const tr = $('<tr></tr>');

        del.on('click', e => {
            e.preventDefault();
            const index = array.findIndex(v => v.course == course);
            const item = array[index];
            item.element.remove();
            array.splice(index, 1);
        });

        a.on('click', e => {
            e.preventDefault();
            const modal = $('#courseInfoModal');
            const mBody = modal.find('div[class="modal-body"]');
            mBody.empty();
            const table = $('<table class="table table-condensed"></table>');
            const tbody = $('<tbody></tbody>');
            table.append(tbody);
            mBody.append(table);

            tbody.append($(`
                <tr>
                    <td>Instructors</td>
                    <td>${sec.instructors != null ? sec.instructors : 'Not specified'}</td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td>${sec.meetings[0].location != null ? sec.meetings[0].location : 'Not specified'}</td>
                </tr>
                <tr>
                    <td>Start</td>
                    <td>${sec.meetings[0].start}</td>
                </tr>
                <tr>
                    <td>End</td>
                    <td>${sec.meetings[0].end}</td>
                </tr>
            `));
            const tr = $('<tr></tr>');
            new MeetingDaysComponent(false, tr, sec.meetings[0].week);
            tbody.append(tr);

            const title = modal.find('h4[class="modal-title"]');
            title.empty();
            title.append($(`<strong>Course: ${course.name}, credits: ${course.credits}</strong>`));
            modal.modal('show');
        });

        table.find('tbody').append(
            tr.append($('<td></td>').append(a))
                .append($('<td></td>').append(required))
                .append($('<td></td>').append(del))
        );
        array.push({course, element: tr});
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