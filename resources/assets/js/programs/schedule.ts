import {ScheduleRenderer} from '../schedule/schedulerenderer';
import {Course, Section, Schedule, Meeting} from '../schedule/interfaces';
import {settingbar} from "../schedule/settingbar";
import onSaveClicked = settingbar.onSaveClicked;

$(document).ready(function () {

    loadSchedules();
    function loadSchedules(): void {
        $.ajax({
            url: '/api/schedule',
            data: {
                student_id: window['student_id']
            },

            success: function (data: any) {
                if (data['success']) {
                    init(data.data);
                } else {
                    failedLoading();
                }
            },

            error(jq: JQueryXHR, status: string, error: string) {
                alert(status);
                failedLoading();
            }

        });
    }

    let primarySchedule: Schedule | null = null;
    let viewingSchedule: Schedule | null = null;


    function init(schedules: Schedule[]): void {
        const filtered = schedules.filter((s) => s.selected == 1);
        primarySchedule = filtered.length > 0 ? filtered[0] : null;

        viewingSchedule = schedules.length > 0 ? schedules[0] : null;

        settingbar.onSelectSchedule = onSelectSchedule;
        settingbar.onEditClicked = onEditBegin;
        settingbar.onSaveClicked = onSaveClicked;
        settingbar.init(schedules);
    }

    function onSelectSchedule(schedule: Schedule): void {
        console.log(schedule.name);
    }

    function onEditBegin(): boolean {
        return primarySchedule != null && primarySchedule == viewingSchedule;
    }

    function onSaveClicked(newValue: string, starred: boolean): void {

    }

    function failedLoading(): void {
        alert('Failed to load data from the server');
    }

});




