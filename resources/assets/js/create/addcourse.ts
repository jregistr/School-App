import {Course, Section} from "../data/interfaces";
import * as moment from 'moment';
import {MeetingDaysRenderer} from "./meetdays";
import {AddedSectionRenderer} from "./addedSection";

export class AddCourse {

    private postSubmit: (course: Course) => void;
    private courseForm: JQuery;
    private sectionsLabel: JQuery;
    private sectionsParentEl: JQuery;
    private addSectionForm: JQuery;
    private addSectionBtnForm: JQuery;
    private days: MeetingDaysRenderer;
    private sectionLimit: number | null = null;
    private increment: number = 0;

    private sections: AddedSectionRenderer[] = [];

    constructor(parent: JQuery | string, postSubmit: (course: Course) => void, sectionLimit?: number) {
        this.postSubmit = postSubmit;
        if (sectionLimit != null) {
            this.sectionLimit = sectionLimit;
        }

        const parentEl = typeof (parent) == "string" ? $('#' + parent) : parent;
        this.courseForm = AddCourse.createCourseForm();

        parentEl.append(this.courseForm);
        this.sectionsLabel = $(`<h3>Sections</h3>`);
        parentEl.append(this.sectionsLabel);
        this.sectionsParentEl = $('<div class="panel-group"></div>');
        parentEl.append(this.sectionsParentEl);
        this.addSectionForm = AddCourse.createAddSectionForm();
        parentEl.append(this.addSectionForm);

        const daysParent = $('<div class="form-group row"></div>');
        this.addSectionForm.append(daysParent);
        this.days = new MeetingDaysRenderer(true, daysParent);

        this.addSectionBtnForm = AddCourse.createAddSectionBtnForm(this.addSectionBtnClicked.bind(this));
        parentEl.append(this.addSectionBtnForm);
        parentEl.append(AddCourse.createSubmitCourseBtn(this.onSubmitCourse.bind(this),
            this.onClearedAllClicked.bind(this)));

        this.addSectionFormState();

    }

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

    private addSectionBtnClicked(): void {
        const form = this.addSectionForm;
        const ins = form.find('input[name="inst"]').val() || 'Not specified';
        const loc = form.find('input[name="loc"]').val() || 'Not specified';
        const start: string | null = form.find('input[name="start"]').val() || null;
        const end: string | null = form.find('input[name="end"]').val() || null;
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
                        week: this.days.week
                    }
                ]
            };

            this.sections.push(new AddedSectionRenderer(sec, this.sectionsParentEl, `section${++this.increment}`,
                this.onDeleteSection));
        } else {
            let warn = form.find('div[class="alert alert-warning alert-dismissible"]');
            if (warn.length > 0) {
                warn.show();
                window.setTimeout(() => {
                    warn.hide()
                }, 2000);
            } else {
                let warn = $(`
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span>&times;</span>
                        </button>
                        <strong>Warning!</strong> There are missing fields
                    </div>
                `);
                form.prepend(warn);
                warn.show();
                window.setTimeout(() => {
                    warn.hide()
                }, 2000);
            }
        }
    }

    private onDeleteSection(deleted: AddedSectionRenderer): void {

    }

    private onSubmitCourse(): void {

    }

    private onClearedAllClicked(): void {

    }

    private static createCourseForm(): JQuery {
        return $(`
            <form class="form-horizontal" role="form">
                <div class="form-group row">
                    <div class="col-lg-4">
                        <label class="col-lg-12">Subject</label>
                        <input class="form-control col-lg-12" type="text" name="subj" placeholder="CSC">
                    </div>
                    <div class="col-lg-8">
                        <label class="col-lg-12">Course number</label>
                        <input class="form-control col-lg-12" type="number" name="number" placeholder="495">
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-lg-6">
                        <label class="col-lg-12">crn</label>
                        <input class="form-control col-lg-12" type="number" name="crn" value=""
                               placeholder="11111">
                    </div>
                    <div class="col-lg-6">
                        <label class="col-lg-12">Credits</label>
                        <input class="form-control col-lg-12" type="number" name="credits" value="3"
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
                    <div class="col-lg-6 col-md-6">
                        <label class="col-lg-12">Instructor</label>
                        <input class="form-control col-lg-12" type="text" name="inst" placeholder="optional">
                    </div>
                    <div class="col-lg-6 col-md-6">
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
            `<div class="col-lg-6 col-md-6 col-sm-12 col-xs-6">
                 <label class="col-lg-12">Start Time</label>
            </div>
        `);

        const endOuter = $(
            `<div class="col-lg-6 col-md-6 col-sm-12 col-xs-6">
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
}

// const ins = courseForm.find('input[name="subj"]').val();