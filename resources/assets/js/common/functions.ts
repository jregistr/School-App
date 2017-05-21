export function clearInputs(inputs: JQuery[]) {
    inputs.forEach(i => i.val(''));
}

export const  headers = {
    'X-CSRF-TOKEN': (window['Laravel'])['csrfToken']
};

export const student_id = window['student_id'];