import {ScheduleRenderer as scheduleRenderer} from '../schedule/schedulerenderer';
import {Course, Section, Schedule, Meeting} from '../schedule/interfaces';
import {settingbar} from "../schedule/settingbar";
import onSaveClicked = settingbar.onSaveClicked;

module  Schedule {
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
            fp(schedules);

            settingbar.onSelectSchedule = onSelectSchedule.bind(Schedule);
            settingbar.onEditClicked = onEditBegin.bind(Schedule);
            settingbar.onSaveClicked = onSaveClicked.bind(Schedule);
            settingbar.init(schedules);
            renderCurrentSchedule();
        }

        function fp(schedules: Schedule[]) {
            const filtered = schedules.filter((s) => s.selected == 1);
            primarySchedule = filtered.length > 0 ? filtered[0] : null;

            viewingSchedule = schedules.length > 0 ? schedules[0] : null;
        }

        function onSelectSchedule(schedule: Schedule): void {
            viewingSchedule = schedule;
            settingbar.updateCurrent(schedule);
            // console.log(viewingSchedule.name);
        }

        function onEditBegin(): boolean {
            return primarySchedule != null && viewingSchedule != null && primarySchedule.id == viewingSchedule.id;
        }

        function onSaveClicked(newValue: string, starred: boolean, deleted: boolean): void {
            if (viewingSchedule != null) {
                if (!deleted) {
                    const params = {
                        student_id: window['student_id'],
                        schedule_id: viewingSchedule.id,
                        name: newValue,
                    };
                    if (starred) {
                        params['selected'] = starred;
                    }
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': (window['Laravel'])['csrfToken']
                        },
                        method: 'POST',
                        url: '/api/schedule',
                        data: params,
                        success (data: any) {
                            afterSave(data.data);
                        },
                        error(jq: JQueryXHR, status: string, error: string) {
                            alert(status);
                            location.reload();
                        }
                    })
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': (window['Laravel'])['csrfToken']
                        },
                        method: 'delete',
                        url: '/api/schedule',
                        data: {
                            student_id: window['student_id'],
                            schedule_id: viewingSchedule.id
                        },
                        success(data: any){
                            afterSave(data.data);
                        },
                        error(jq: JQueryXHR, status: string, error: string) {
                            alert(status);
                            location.reload();
                        }
                    });
                }
            }
        }

        function afterSave(schedules: Schedule[]): void {
            if (schedules == null || schedules.length == 0) {
                settingbar.off();
                scheduleRenderer.off();
            } else {
                const filtered = schedules.filter((s) => s.selected == 1);
                primarySchedule = filtered.length > 0 ? filtered[0] : null;

                if (viewingSchedule != null) {
                    const foundViewing = schedules.filter(s => viewingSchedule != null && s.id === viewingSchedule.id);
                    if (foundViewing.length == 0) {
                        viewingSchedule = schedules[0];
                    }
                } else {
                    viewingSchedule = schedules[0];
                }

                if (viewingSchedule != null) {
                    settingbar.update(schedules, viewingSchedule);
                    renderCurrentSchedule();
                } else {
                    scheduleRenderer.off();
                    throw 'Null viewing schedule';
                }
            }
        }

        function failedLoading(): void {
            alert('Failed to load data from the server');
        }

        function renderCurrentSchedule() {
            if (viewingSchedule != null) {
                scheduleRenderer.render(viewingSchedule);
            }
        }

    });

}




