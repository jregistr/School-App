
export interface Course {
    id:number;
    school_id:number;
    name:string;
    crn:number;
    credits:number;
    section:Section;
}

export interface Schedule {
    id:number;
    student_id:number;
    selected:number;
    name:string;
    courses:Course[];
}

export interface Section {
    id:number;
    course_id:number;
    instructors:string;
    meeting:Meeting;
}

export interface Meeting {
    id:number;
    start:string;
    end:string;
    location:string;
    sunday:number;
    monday:number;
    tuesday:number;
    wednesday:number;
    thursday:number;
    friday:number;
    saturday:number;
}