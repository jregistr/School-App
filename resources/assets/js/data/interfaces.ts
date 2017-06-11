export interface Schedule {
    id: number;
    student_id: number;
    is_primary: number;
    added: number;
    name: string;
    scheduledCourses: ScheduledCourse[];
}

interface _course {
    id: number;
    school_id: number;
    name: string;
    crn: number;
    credits: number;
}

export interface Course extends _course {
    sections: Section[];
}

export interface ScheduledCourse extends _course {
    section: ScheduledSection;
}

interface _section {
    id: number;
    course_id: number;
    instructors: string;
}

export interface Section extends _section {
    meetings: Meeting[];
}

export interface ScheduledSection extends _section {
    meeting: Meeting
}

export interface Meeting {
    id: number;
    start: string;
    end: string;
    location: string;
    week: Week;
}

export interface Week {
    sunday: number;
    monday: number;
    tuesday: number;
    wednesday: number;
    thursday: number;
    friday: number;
    saturday: number;
}

export interface GeneratorList {
    id: number,
    student_id: number,
    credit_limit:number,
    entries: GeneratorEntry[]
}

export interface GeneratorEntry {
    required: boolean,
    course: ScheduledCourse
}




