import {ViewCoursesComponent} from "../create/viewcourses";
import {Course, ScheduledCourse} from "../data/interfaces";
import {GeneratorListComponent} from "../create/generatorList";
import {ViewSectionsComponent} from "../create/viewSections";

class CreateProgram {

    private static _instance: CreateProgram;
    private sectionsTab = $('a[href="#sections"]');

    private generatorList: GeneratorListComponent;
    private viewCourses: ViewCoursesComponent;
    private viewSections: ViewSectionsComponent;

    private constructor() {

    }

    static get instance(): CreateProgram {
        if (CreateProgram._instance == null) {
            CreateProgram._instance = new CreateProgram();
        }
        return CreateProgram._instance;
    }

    init(): void {
        const modal = $('#courseInfoModal');
        this.generatorList = new GeneratorListComponent($('.generate-list-box'), $('#generate-candidates'),
            $('#addNew'), $('#clearAll'), $('#genSch'), modal, $('#confirmModal'), this.onGenerateClicked.bind(this));

        this.viewCourses = new ViewCoursesComponent($('#courses').find('div[class="view-course-table"]'),
            this.onViewCourseRowClicked.bind(this), modal,
            'viewCourseToolbar');

        this.viewSections = new ViewSectionsComponent($('#sections'), 'viewSectionsToolbar',
            this.addToGenerate.bind(this));
    }

    private onGenerateClicked(): void {
        alert('Not yet implemented');
    }

    private onViewCourseRowClicked(course: Course): void {
        this.sectionsTab.tab('show');
        this.viewSections.course = course;
    }

    private addToGenerate(scheduledCourse:ScheduledCourse): void {
        this.generatorList.addToGenList(scheduledCourse);
    }

}

$(document).ready(() => {
    CreateProgram.instance.init();
});