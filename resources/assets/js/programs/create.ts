import {AddCourseComponent} from '../create/addcourse';

$(document).ready(() => {

    const addCourseForm = new AddCourseComponent($('#add-class'), afterCourseSubmit, 1);

    function afterCourseSubmit() {

    }
});