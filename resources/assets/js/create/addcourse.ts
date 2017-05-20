import {Course, Meeting, Section} from "../data/interfaces";
import * as moment from 'moment';
import {MeetingDaysComponent} from "./meetdays";
import {AddedSectionComponent} from "./addedSection";
import {clearInputs} from "../common/functions";

/**
 * Re-usable add course form component.
 */
export class AddCourseComponent {

    private postSubmit: (course: Course) => void;
    private courseForm: JQuery;
    private sectionsLabel: JQuery;
    private sectionsParentEl: JQuery;
    private addSectionForm: JQuery;
    private addSectionBtnForm: JQuery;
    private days: MeetingDaysComponent;
    private sectionLimit: number | null = null;
    private increment: number = 0;

    private sections: AddedSectionComponent[] = [];

    /**
     * Add course re-usable component constructor.
     * @param parent - The parent object to house the elements this class generates.
     * @param postSubmit - Callback after a course has been successfully created.
     * @param sectionLimit - The max allowed number of sections.
     */
    constructor(parent: JQuery | string, postSubmit: (course: Course) => void, sectionLimit?: number) {
        this.postSubmit = postSubmit;
        if (sectionLimit != null) {
            this.sectionLimit = sectionLimit;
        }

        const parentEl = typeof (parent) == "string" ? $('#' + parent) : parent;
        this.courseForm = AddCourseComponent.createCourseForm();

        parentEl.append(this.courseForm);
        this.sectionsLabel = $(`<h3>Sections</h3>`);
        parentEl.append(this.sectionsLabel);
        this.sectionsParentEl = $('<div class="panel-group"></div>');
        parentEl.append(this.sectionsParentEl);
        this.addSectionForm = AddCourseComponent.createAddSectionForm();
        parentEl.append(this.addSectionForm);

        const daysParent = $('<div class="form-group row"></div>');
        this.addSectionForm.append(daysParent);
        this.days = new MeetingDaysComponent(true, daysParent);

        this.addSectionBtnForm = AddCourseComponent.createAddSectionBtnForm(this.addSectionBtnClicked.bind(this));
        parentEl.append(this.addSectionBtnForm);
        parentEl.append(AddCourseComponent.createSubmitCourseBtn(this.onSubmitCourse.bind(this),
            this.onClearedAllClicked.bind(this)));

        this.addSectionFormState();

    }

    /**
     * Determines render state for form elements.
     */
    private addSectionFormState(): void {
        if (this.sectionLimit != null && this.sections.length >= this.sectionLimit) {
            this.addSectionForm.hide();
            this.addSectionBtnForm.hide();
        } else {
            this.addSectionForm.show();
            this.addSectionBtnForm.show();
        }

        if (this.sections.length == 0) {
            this.sectionsLabel.hide();
        } else {
            this.sectionsLabel.show();
        }
    }

    /**
     * Call back function for add section button.
     */
    private addSectionBtnClicked(): void {
        const form = this.addSectionForm;
        const insInput = form.find('input[name="inst"]');
        const locInput = form.find('input[name="loc"]');
        const startInput = form.find('input[name="start"]');
        const endInput = form.find('input[name="end"]');

        const ins = insInput.val() || 'Not specified';
        const loc = locInput.val() || 'Not specified';
        const start: string | null = startInput.val() || null;
        const end: string | null = endInput.val() || null;
        if (start != null && end != null) {
            const startTime = moment(start, ["h:mm A"]).format("HH:mm");
            const endTime = moment(end, ['h:mm A']).format('HH:mm');
            const sec: Section = {
                id: 0,
                course_id: 0,
                instructors: ins,
                meetings: [
                    {
                        id: 0,
                        location: loc,
                        start: startTime,
                        end: endTime,
                        week: Object.assign({}, this.days.week)
                    }
                ]
            };

            this.sections.push(new AddedSectionComponent(sec, this.sectionsParentEl, `section${++this.increment}`,
                this.onDeleteSection.bind(this)));

            this.addSectionFormState();
            clearInputs([insInput, locInput, startInput, endInput]);
        } else {
            AddCourseComponent.renderMissingFields(form);
        }
    }

    private onDeleteSection(deleted: AddedSectionComponent): void {
        this.sections.splice(this.sections.indexOf(deleted), 1);
        this.addSectionFormState();
    }

    /**
     * Callback function for submission. Queries the server to create a course and added sections.
     */
    private onSubmitCourse(): void {
        const queryData = this.collectCourseData();
        if (queryData != null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': (window['Laravel'])['csrfToken']
                },
                url: '/api/course',
                method: 'POST',
                data: {
                    student_id: window['student_id'],
                    name: queryData.name,
                    credits: queryData.credits,
                    crn: queryData.crn
                }
            }).done(response => {
                const sections = queryData.sections;
                this.sendSections(sections, <Course>response.data['course']);
            }).fail((jqXhr, status) => {
                alert(status);
            })
        }
    }

    /**
     * Queries the server to create sections for a course.
     * @param sections - The sections to be sent to the server.
     * @param course - The course.
     */
    private sendSections(sections: Section[], course: Course) {
        const queries: JQueryXHR[] = [];
        sections.forEach(section => {
            const meeting: Meeting = section.meetings[0];
            const week = meeting.week;
            queries.push(
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': (window['Laravel'])['csrfToken']
                    },
                    url: 'api/course/section',
                    method: 'POST',
                    data: {
                        student_id: window['student_id'],
                        course_id: course.id,
                        instructors: section.instructors,
                        location: meeting.location,
                        start: meeting.start,
                        end: meeting.end,
                        days: `${week.sunday}, ${week.monday}, ${week.tuesday}, ${week.wednesday}, ${week.thursday},
                            ${week.friday}, ${week.saturday}`
                    }
                })
            );
        });

        $.when(queries).then(
            () => {
                this.postSubmit(course);
            },
            (xhr, status) => {
                alert(status);
                alert(xhr.responseText);
            }
        );
    }

    /**
     * Clears all the inputs and resets added sections.
     */
    private onClearedAllClicked(): void {
        this.days.clear();
        this.courseForm.find('input').each((index, el) => {
            $(el).val('');
        });

        this.addSectionForm.find('input').each((index, el) => {
            $(el).val('');
        });

        this.sectionsParentEl.empty();
        this.sections = [];
        this.addSectionFormState();
    }

    /**
     * Gathers data from the forms and returns a course object or null if required inputs are missing(a warning is
     * rendered to the user).
     * @returns {Course|null} - Course object containing the form data or null.
     */
    private collectCourseData(): Course | null {
        let course: Course | null = null;
        const courseForm = this.courseForm;

        const subjInput = courseForm.find('input[name="subj"]');
        const courseNumInput = courseForm.find('input[name="number"]');
        const crnInput = courseForm.find('input[name="crn"]');
        const creditsInput = courseForm.find('input[name="credits"]');

        const subj = subjInput.val() || null;
        const courseNum = courseNumInput.val() || null;
        const crn = crnInput.val() || null;
        const credits = creditsInput.val() || null;
        const sections: Section[] | null = this.sections.length >= 1 ? this.sections.map(r => r.section) : null;

        if (subj != null && courseNum != null && crn != null && credits != null && sections != null) {
            course = {
                id: 0,
                school_id: 0,
                name: `${subj} ${courseNum}`,
                crn,
                credits,
                sections
            };
            this.onClearedAllClicked();
        } else {
            AddCourseComponent.renderMissingFields(courseForm);
        }

        return course;
    }

    private static createCourseForm(): JQuery {
        return $(`
            <form class="form-horizontal" role="form">
                <div class="form-group row">
                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6">
                        <label class="col-lg-12">Subject</label>
                        <input class="form-control" type="text" name="subj" placeholder="CSC">
                    </div>
                    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6">
                        <label class="col-lg-12">Number</label>
                        <input class="form-control" type="number" min="0" max="99999" step="1" name="number" 
                            placeholder="495">
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <label class="col-lg-12">crn</label>
                        <input class="form-control col-lg-12" type="number" min="0" max="99999" step="1" name="crn"
                               placeholder="11111">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <label class="col-lg-12">Credits</label>
                        <input class="form-control col-lg-12" type="number" name="credits" min="0" max="99" value="3"
                               placeholder="3">
                    </div>
                </div>
            </form>
          `);
    }

    private static createAddSectionForm(): JQuery {
        const outer = $(`
            <form class="form-horizontal" role="form">
                <div class="form-group row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <label class="col-lg-12">Instructor</label>
                        <input class="form-control col-lg-12" type="text" name="inst" placeholder="optional">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <label class="col-lg-12">Location</label>
                        <input class="form-control col-lg-12" type="text" name="loc" placeholder="optional">
                    </div>
                </div>
            </form>
        `);

        const timeGroup = $(`
             <div class="form-group row">
             
             </div>
        `);

        const startOuter = $(
            `<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                 <label class="col-lg-12">Start Time</label>
            </div>
        `);

        const endOuter = $(
            `<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                 <label class="col-lg-12">End Time</label>
            </div>
        `);

        const startTime = $(`<input type='text' name="start" class="form-control" />`);
        const endTime = $(`<input type='text' name="end" class="form-control" />`);

        startTime.datetimepicker({
            format: 'LT'
        });
        endTime.datetimepicker({
            format: 'LT',
            useCurrent: false
        });

        startTime.on('dp.change', (e) => {
            endTime.data("DateTimePicker").minDate(e.date);
        });

        endTime.on("dp.change", function (e) {
            startTime.data("DateTimePicker").maxDate(e.date);
        });

        startOuter.append(startTime);
        endOuter.append(endTime);
        timeGroup.append(startOuter);
        timeGroup.append(endOuter);
        outer.append(timeGroup);
        return outer;
    }

    private static createAddSectionBtnForm(onAddSectionClicked: () => void): JQuery {
        const btnParent = $(`<div class="col-lg-6 col-md-6"></div>`);
        const addSectionBtn = $(`
            <button class="btn btn-success">
                <span class="glyphicon glyphicon-plus"></span>
                Add Section
            </button>
        `);

        addSectionBtn.on('click', onAddSectionClicked);
        btnParent.append(addSectionBtn);

        return $(`<div class="row" style="margin-top: -10px;"></div>`)
            .append(btnParent)
            .append($('<div class="col-lg-6 col-md-6"></div>'));
    }

    private static createSubmitCourseBtn(onSubmitClicked: () => void, onClearedClicked: () => void): JQuery {
        const submitP = $('<div class="course-btns-parent col-lg-7 col-md-7 col-sm-6 col-xs-6">');
        const cancelP = $('<div class="course-btns-parent col-lg-5 col-md-5 col-sm-6 col-xs-6">');
        const submitBtn = $('<button class="btn btn-primary form-control clearfix">Submit</button>');
        const clearAllBtn = $('<button class="btn btn-danger form-control clearfix">Clear</button>');
        submitBtn.on('click', onSubmitClicked);
        clearAllBtn.on('click', onClearedClicked);

        return $('<div class="row" style="margin-top: 10px; margin-bottom: 10px;"></div>')
            .append(submitP.append(submitBtn))
            .append(cancelP.append(clearAllBtn));
    }

    private static renderMissingFields(parent: JQuery): void {
        let findAlert = parent.find('div[class="alert alert-warning alert-dismissible"]');
        if (findAlert.length == 0) {
            findAlert = $(`
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span>&times;</span>
                    </button>
                    <strong>Warning!</strong> There are missing fields
                </div>
             `);
            parent.prepend(findAlert);
        }

        findAlert.fadeIn(200);
        window.setTimeout(() => {
            findAlert.hide()
        }, 2500);
    }

}
