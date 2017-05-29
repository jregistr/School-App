import {AddCourseComponent} from './addcourse';
import {Course, GeneratorEntry, GeneratorList, Meeting, ScheduledCourse, Section} from "../data/interfaces";
import {MeetingDaysComponent} from "./meetdays";

export class GeneratorListComponent {

    private genListTable: JQuery;
    private addNewBtn: JQuery;
    private clearBtn: JQuery;
    private genBtn: JQuery;
    private courseInfoModal: JQuery;


    constructor(genListTable: JQuery, addNewBtn: JQuery, clearBtn: JQuery, genBtn: JQuery, courseInfoModal: JQuery) {
        this.genListTable = genListTable;
        this.addNewBtn = addNewBtn;
        this.clearBtn = clearBtn;
        this.genBtn = genBtn;
        this.courseInfoModal = courseInfoModal;

        this.clearBtn.on('click', this.clearBtnClicked.bind(this));
        this.addNewBtn.on('click', this.showAddCourseToGeneratorForm.bind(this));

        const self = this;
        GeneratorListComponent.getGeneratorList((list: GeneratorList) => {
            self.renderGenerator(list);
        });
    }

    private showAddCourseToGeneratorForm(): void {
        const title = this.courseInfoModal.find('h4[class="modal-title"]');
        const mBody = this.courseInfoModal.find('div[class="modal-body"]');
        const self = this;
        mBody.empty();
        title.empty();
        title.append('Add new Course');
        new AddCourseComponent(mBody, (course, sections) => {
            if (sections.length > 0) {
                const s = sections[0];
                const scheduledSection = {
                    id: s.id,
                    course_id: s.course_id,
                    instructors: s.instructors,
                    meeting: s.meetings[0]
                };

                const scheduledCourse: ScheduledCourse = {
                    id: course.id,
                    name: course.name,
                    crn: course.crn,
                    credits: course.credits,
                    school_id: course.school_id,
                    section: scheduledSection
                };
                self.courseInfoModal.modal('hide');
                this.addToGeneratorList(scheduledCourse);
            } else {
                alert('No sections received');
            }
        }, 1);
        this.courseInfoModal.modal('show');
    }

    private clearBtnClicked(): void {

    }

    private addToGeneratorList(course: ScheduledCourse): void {
        const self = this;
        $.ajax({
            url: '/api/schedule/generator',
            method: 'put',
            data: {
                section_id: course.section.id,
                meeting_id: course.section.meeting.id
            },
            success(response) {
                const generatorObj = response.data.generator;
                self.renderGenerator(generatorObj);
            },
            error() {
                alert('There was an error.')
            }
        })
    }

    private updateGeneratorEntry(course: ScheduledCourse): void {

    }

    private deleteGeneratorEntry(course: ScheduledCourse): void {

    }

    private renderGenerator(generatorListObj: GeneratorList): void {
        const entries: GeneratorEntry[] = generatorListObj.entries;

        const tbody = this.genListTable.find('tbody');
        tbody.empty();

        if (entries.length > 0) {
            entries.forEach(entry => {
                this.addEntryToGeneratorHtml(entry);
            });
        } else {
            tbody.append($(`<tr><td colspan="3"><strong class="lead">No items added yet. Add new courses to get started.</strong></td></tr>`));
        }
    }

    private addEntryToGeneratorHtml(entry: GeneratorEntry): void {
        const table = this.genListTable;
        const course = entry.course;
        const sec = course.section;
        const meeting:Meeting = sec.meeting;
        const self = this;
        const a = $(`
            <a href="#"><strong>${course.name} - ${sec.instructors != null ? sec.instructors : 'Section'}</strong></a>
        `);

        const required = $(`<input type="checkbox" ${entry.required ? 'checked' : ''}>`);
        const del = $(`<a class="" href="#"><span class="glyphicon glyphicon-remove"></span></a>`);
        const tr = $('<tr></tr>');

        del.on('click', e => {
            e.preventDefault();
            self.deleteGeneratorEntry(course);
        });

        required.on('click', () => {
            self.updateGeneratorEntry(course);
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
                    <td>${meeting.location != null ? meeting.location : 'Not specified'}</td>
                </tr>
                <tr>
                    <td>Start</td>
                    <td>${meeting.start}</td>
                </tr>
                <tr>
                    <td>End</td>
                    <td>${meeting.end}</td>
                </tr>
            `));
            const tr = $('<tr></tr>');
            new MeetingDaysComponent(false, tr, meeting.week);
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
    }

    private static getGeneratorList(onComplete: (generatorList: GeneratorList) => void): void {
        $.ajax({
            url: '/api/schedule/generator',
            method: 'GET',
            success(response) {
                const data = response.data;
                const generatorList = GeneratorListComponent.transmuteGeneratorReponseData(data);
                if (generatorList == null) {
                    alert('There was an error retrieving list');
                    throw new Error('Error getting data');
                } else {
                    onComplete(generatorList);
                }
            },
            error(xhr: JQueryXHR, status) {
                alert('There was an error retrieving data.<br>' + status);
            }
        });
    }

    private static transmuteGeneratorReponseData(data: any): GeneratorList | null {
        const respGen = data.generator;
        if (respGen != null) {
            return {
                id: respGen.id,
                student_id: respGen.student_id,
                entries: respGen['entries']
            }
        } else {
            return null;
        }
    }

}