import {ViewCoursesComponent} from "../create/viewcourses";
import {Course, Schedule, ScheduledCourse} from "../data/interfaces";
import {GeneratorListComponent} from "../create/generatorList";
import {ViewSectionsComponent} from "../create/viewSections";
import {GeneratedSchedulesComponent} from "../create/generatedSchedules";
import {headers} from "../common/functions";

class CreateProgram {

    private static _instance: CreateProgram;
    private sectionsTab = $('a[href="#sections"]');
    private generatedTab = $('a[href="#added"]');

    private generatorList: GeneratorListComponent;
    private viewCourses: ViewCoursesComponent;
    private viewSections: ViewSectionsComponent;
    private generatedRenderer: GeneratedSchedulesComponent;

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
            $('#creditLimitOuter'),
            $('#addNew'), $('#clearAll'), $('#genSch'), modal, $('#confirmModal'), this.onGenerateClicked.bind(this));

        this.viewCourses = new ViewCoursesComponent($('#courses').find('div[class="view-course-table"]'),
            this.onViewCourseRowClicked.bind(this), modal,
            'viewCourseToolbar');

        this.viewSections = new ViewSectionsComponent($('#sections'), 'viewSectionsToolbar',
            this.addToGenerate.bind(this));

        const generatedTabBody = $('#added');
        this.generatedTab.on('shown.bs.tab', () => {
            const outers = generatedTabBody.find('div[class*="schedule-render-outer"]');
            outers.each((i, val) => {
                $(val).fullCalendar('render');
            })
        });

        this.generatedRenderer = new GeneratedSchedulesComponent(generatedTabBody, $('#sch-confirmClearGens'),
            $('#sch-confirmAddRem'));
        this.generatedRenderer.updateSchedules();
    }

    private onGenerateClicked(): void {
        this.generatedRenderer.clearGeneratedSchedules((continu: boolean) => {
            if (continu) {
                CreateProgram.generateNewSchedules().then(value => {
                    this.generatedRenderer.updateSchedules(value);
                    this.generatedTab.tab('show');
                }, reason => {
                    console.log(reason);
                    alert('An error occurred!!');
                });
            }
        });
    }

    private onViewCourseRowClicked(course: Course): void {
        this.sectionsTab.tab('show');
        this.viewSections.course = course;
    }

    private addToGenerate(scheduledCourse: ScheduledCourse): void {
        this.generatorList.addToGenList(scheduledCourse);
    }

    private static generateNewSchedules(): Promise<Schedule[]> {
        return new Promise<Schedule[]>((resolve, reject) => {
            $.ajax({
                url: '/api/schedule/generator/generate',
                method: 'POST',
                headers,
                success(resp: JQueryAjaxSettings) {
                    const data = resp.data;
                    const schedules: Schedule[] = data.schedules;
                    resolve(schedules);
                },
                error(xhr, status) {
                    reject(xhr);
                }
            });
        });
    }

}

$(document).ready(() => {
    CreateProgram.instance.init();
});