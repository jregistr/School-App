import {ScheduledCourse} from "../data/interfaces";
import {headers} from "../common/functions";

const url = '/api/schedule/course';

export function sendScheduleUpdates(scheduleId: number, newCourses: ScheduledCourse[], deletedCourses: ScheduledCourse[],
                                    changes: ScheduledCourse[], onComplete: () => void) {
    function courseToData(course: ScheduledCourse): {} {
        return {
            schedule_id: scheduleId,
            course: JSON.stringify(course)
        }
    }

    function onError(operation: string) {
        return function (reason: any) {
            alert(`Failure on ${operation}. <br> Reason: ${typeof reason == 'string' ? reason : JSON.stringify(reason)}`)
            onComplete();
        }
    }

    sendCourses<boolean>(deletedCourses, 'DELETE', (c: ScheduledCourse) => {
        return {
            schedule_id: scheduleId,
            section_id: c.section.id,
            meeting_id: c.section.meeting.id
        }
    }, (response) => {
        return response.data.deleted as boolean;
    })
        .then(
            () => {
                sendCourses<ScheduledCourse>(newCourses, 'PUT', courseToData, response => {
                    return response.data.course as ScheduledCourse;
                })
                    .then(() => {
                        sendCourses(changes, 'POST', courseToData, response => {
                            return response.data.course as ScheduledCourse;
                        })
                            .then(() => {
                                onComplete();
                            }, onError('Edited courses'));
                    }, onError('New courses'));
            },
            // delete error
            onError('delete'));
}

function sendCourses<T>(values: ScheduledCourse[], method: string,
                        makeData: (c: ScheduledCourse) => {},
                        process: (response: JQueryAjaxSettings) => T): Promise<T[]> {

    return new Promise<T[]>((resolve, reject) => {
        const couriers: Promise<T>[] = [];
        values.forEach((value) => {
            const data = makeData(value);
            couriers.push(sendQuery(url, method, data, process));
        });

        Promise.all(couriers).then((value => {
            resolve(value);
        }), (reason => {
            reject(reason);
        }));
    });
}

function sendQuery<T>(url: string, method: string, data: {},
                      processResponse: (response: JQueryAjaxSettings) => T): Promise<T> {
    return new Promise((resolve, reject) => {
        $.ajax({
            url,
            method,
            headers,
            data,
            success(data: JQueryAjaxSettings) {
                const trans: T = processResponse(data);
                resolve(trans);
            },
            error(xhr, status) {
                reject(status);
            }
        });
    });
}