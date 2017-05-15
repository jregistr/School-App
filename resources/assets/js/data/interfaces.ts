export interface Schedule {
    id: number;
    student_id: number;
    selected: number;
    name: string;
    scheduledCourses:ScheduledCourse[];
}

interface _course {
    id: number;
    school_id: number;
    name: string;
    crn: number;
    credits: number;
}

export interface Course extends _course {
    sections:Section[];
}

export interface ScheduledCourse extends _course {
    section:Section;
}

export interface Section {
    id: number;
    course_id: number;
    instructors: string;
    meetings: Meeting[];
}

export interface Meeting {
    id: number;
    start: string;
    end: string;
    location: string;
    week:Week;
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




