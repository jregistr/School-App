import {AddCourseComponent} from '../create/addcourse';
import {ViewCoursesComponent} from "../create/viewcourses";

$(document).ready(() => {

    const addCourseForm = new AddCourseComponent($('#add-class'), afterCourseSubmit, 1);
    const viewCoursesTable = new ViewCoursesComponent($('#courses'));

    function afterCourseSubmit() {

    }

});