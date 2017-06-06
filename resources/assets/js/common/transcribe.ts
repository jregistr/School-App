import {Course, ScheduledCourse, ScheduledSection, Section} from "../data/interfaces";

export function transcribe(course: Course, sections: Section[]): ScheduledCourse {
    const s = sections[0];
    const meeting = s.meetings[0];
    const section: ScheduledSection = {
        id: s.id,
        course_id: s.course_id,
        instructors: s.instructors,
        meeting: meeting
    };
    return {
        id: course.id,
        name: course.name,
        school_id: course.school_id,
        credits: course.credits,
        crn: course.crn,
        section: section
    }
}