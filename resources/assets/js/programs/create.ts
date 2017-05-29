import {AddCourseComponent} from '../create/addcourse';
import {ViewCoursesComponent} from "../create/viewcourses";
import {Course, Meeting, ScheduledCourse, Section} from "../data/interfaces";
import {MeetingDaysComponent} from "../create/meetdays";
import {GeneratorListComponent} from "../create/generatorList";

class CreateProgram {

    private static _instance: CreateProgram;

    private coursesTab = $('a[href="#courses"]');
    private sectionsTab = $('a[href="#sections"]');
    private generateTab = $('a[href="#added"]');

    private generatorList: GeneratorListComponent;

    private constructor() {
    }

    static get instance(): CreateProgram {
        if (CreateProgram._instance == null) {
            CreateProgram._instance = new CreateProgram();
        }
        return CreateProgram._instance;
    }

    init(): void {
        this.generatorList = new GeneratorListComponent($('#generate-candidates'),
            $('#addNew'), $('#clearAll'), $('#genSch'), $('#courseInfoModal'));
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