import {AddCourse as addCourse} from '../create/addcourse';

export module create {
    $(document).ready(function () {
        addCourse.init();
    });
}