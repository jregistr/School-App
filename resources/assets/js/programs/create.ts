import {ViewCoursesComponent} from "../create/viewcourses";
import {Course} from "../data/interfaces";
import {GeneratorListComponent} from "../create/generatorList";
import {ViewSectionsComponent} from "../create/viewSections";

class CreateProgram {

    private static _instance: CreateProgram;

    // private coursesTab = $('a[href="#courses"]');
    private sectionsTab = $('a[href="#sections"]');
    // private generateTab = $('a[href="#added"]');

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
        this.generatorList = new GeneratorListComponent($('#generate-candidates'),
            $('#addNew'), $('#clearAll'), $('#genSch'), modal, this.onGenerateClicked.bind(this));

        this.viewCourses = new ViewCoursesComponent($('#courses').find('div[class="view-course-table"]'),
            this.onViewCourseRowClicked.bind(this), modal,
            'viewCourseToolbar');

        this.viewSections = new ViewSectionsComponent($('#sections'));
    }

    private onGenerateClicked(): void {
        alert('Not yet implemented');
    }

    private onViewCourseRowClicked(course: Course): void {
        this.sectionsTab.tab('show');
        this.viewSections.course = course;
    }

}

$(document).ready(() => {
    CreateProgram.instance.init();
});