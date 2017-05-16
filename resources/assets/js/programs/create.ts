import {AddCourse} from '../create/addcourse';

$(document).ready(() => {

    const addCourseForm = new AddCourse($('#add-class'), afterCourseSubmit, 1);

    function afterCourseSubmit() {

    }
});