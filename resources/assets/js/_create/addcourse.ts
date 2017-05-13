import {Section} from "./interfaces";
export module AddCourse {
    const courseForm = $('#course-form');
    const sectionForm = $('#section-form');
    const addSection = $('#addSectionBtn');
    const submitBtn = $('#submitClassBtn');
    const accordion = $('#accordion');

    const sections: Section[] = [];
    let idCounter: number = 0;

    function renderSectionAdd(section: Section, index: number): void {
        const a = $('<a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>');
        const outer = $('<div class="panel panel-default"></div>');
        const id = `section${++idCounter}`;

        a.on('click', (e: JQueryEventObject) => {
            console.log('Clicked');
            e.preventDefault();
            sections.splice(index, 1);
            outer.remove();
        });

        const heading = $(`
        
            <div class="panel-heading"> 
               
            </div>
        `);

        const h4 = $(`
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#${id}">Section ${index}</a>
                </h4>
        `);

        h4.append(a);
        heading.append(h4);

        outer.append(heading);

        outer.append(
            `
            
            
            <div id="${id}" class="panel-collapse collapse">
                <div class="panel-body">
                    <table class="table table-condensed">
                        <tbody>
                            <tr>
                                <td>Instructors</td>
                                <td>${section.instructors}</td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td>${section.location}</td>
                            </tr>
                            <tr>
                                <td>Start</td>
                                <td>${section.start}</td>
                            </tr>
                            <tr>
                                <td>End</td>
                                <td>${section.end}</td>
                            </tr>
                            <tr>
                                <td>Days</td>
                                <td>
                                    <div class="col-lg-12">
                                        <label class="checkbox-inline"><input disabled type="checkbox" 
                                            checked="${section.sunday == 1 ? 'checked' : 'none'}" >S</label>
                                        <label class="checkbox-inline"><input disabled type="checkbox" 
                                            checked="${section.monday == 1 ? 'checked' : 'none'}">M</label>
                                        <label class="checkbox-inline"><input disabled type="checkbox" 
                                            checked="${section.tuesday == 1 ? 'checked' : 'none'}">T</label>
                                        <label class="checkbox-inline"><input disabled type="checkbox"
                                            checked="">W</label>
                                             <label class="checkbox-inline"><input disabled type="checkbox" name="">R</label>
                                        <label class="checkbox-inline"><input disabled type="checkbox" name="">F</label>
                                        <label class="checkbox-inline"><input disabled type="checkbox" name="">St</label>
                                    </div>
                                     <!--<div class="col-lg-12">-->
                                       <!---->
                                     <!--</div>-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            `
        );

        accordion.append(outer);
    }

    function initSubmitBtn() {
        submitBtn.on('click', function (e:JQueryEventObject) {
            e.preventDefault();

            const ins = courseForm.find('input[name="subj"]').val();
            const num = courseForm.find('input[name="number"]').val();
            const crn = courseForm.find('input[name="crn"]').val();
            const credits = courseForm.find('input[name="credits"]').val();

            if (ins.length > 0 && num.length > 0) {

            } else {
                alert('Incomplete form');
            }
        })
    }

    export function init() {
        addSection.on('click', function (e: JQueryEventObject) {
            e.preventDefault();
            const ins = sectionForm.find('input[name="inst"]').val();
            const loc = sectionForm.find('input[name="loc"]').val();

            const start = sectionForm.find('input[name="start"]').val();
            const end = sectionForm.find('input[name="end"]').val();

            if (start.length > 0) {
                const instructors = ins.length == 0 ? 'Not Specified' : ins;
                const location = loc.length == 0 ? 'Not Specified' : loc;

                const sun = sectionForm.find('input[name="sun"]').prop('checked') ? 1 : 0;
                const mon = sectionForm.find('input[name="mon"]').prop('checked') ? 1 : 0;
                const tue = sectionForm.find('input[name="tue"]').prop('checked') ? 1 : 0;
                const wed = sectionForm.find('input[name="wed"]').prop('checked') ? 1 : 0;
                const thu = sectionForm.find('input[name="thur"]').prop('checked') ? 1 : 0;
                const fri = sectionForm.find('input[name="fri"]').prop('checked') ? 1 : 0;
                const sat = sectionForm.find('input[name="sat"]').prop('checked') ? 1 : 0;
                const s = {
                    instructors: instructors,
                    location: location,
                    start: start,
                    end: end,
                    sunday: sun,
                    monday: mon,
                    tuesday: tue,
                    wednesday: wed,
                    thursday: thu,
                    friday: fri,
                    saturday: sat
                };
                const index = sections.push(s);
                renderSectionAdd(s, index);
            } else {
                alert('Fill out the missing information for section.')
            }

        });
    }

}