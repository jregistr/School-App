import {Schedule} from './interfaces';

export module settingbar {

    export let onSelectSchedule: (schedule: Schedule) => void;
    export let onEditClicked: () => boolean;
    export let onSaveClicked: (newValue: string, starred: boolean, deleted: boolean) => void;

    let schedulesUl: JQuery;
    let schedulesBtn: JQuery;
    let editBtn: JQuery;
    let saveBtn: JQuery;
    let nameText: JQuery;
    let nameTextInput: JQuery;
    let starBtn: JQuery;
    let delBtn: JQuery;

    let starred: boolean = false;
    let deleted: boolean = false;

    function populateDropdown(schedules: Schedule[]): void {
        schedulesUl.empty();
        schedules.forEach((schedule: Schedule) => {
            const a = $('<a href="#"></a>');
            a.append(schedule.name);

            a.on('click', (e: JQueryEventObject) => {
                e.preventDefault();
                onSelectSchedule(schedule);
            });

            schedulesUl.append($('<li></li>').append(a));
        });
    }

    function setText(name: string) {
        nameText.empty();
        nameText.append(name);
    }

    function starBtnStarConfig(on: boolean) {
        const span = starBtn.children().first();

        if (on) {
            span.removeClass('glyphicon-star-empty');
            span.addClass('glyphicon-star');
        } else {
            span.removeClass('glyphicon-star');
            span.addClass('glyphicon-star-empty');
        }
    }

    function editBtnSetup(): void {
        editBtn.on('click', (e: JQueryEventObject) => {
            editBtn.hide();
            saveBtn.show();
            schedulesBtn.hide();

            nameTextInput.val(nameText.text());
            nameText.hide();
            nameTextInput.show();

            deleted = false;
            delBtn.show();

            if (onEditClicked != null) {
                const isSelected = starred = onEditClicked();
                if (!isSelected) {
                    starBtnStarConfig(isSelected);
                    starBtn.show();
                }
            }

        });
    }

    function saveBtnSetup(): void {
        saveBtn.on('click', (e: JQueryEventObject) => {
            saveBtn.hide();
            editBtn.show();
            schedulesBtn.show();

            const name = nameTextInput.val();
            setText(name);

            nameTextInput.hide();
            nameText.show();

            if (onSaveClicked != null)
                onSaveClicked(name, starred, deleted);

            starred = false;
            deleted = false;
            starBtn.hide();
            delBtn.hide();
        });
    }

    export function init(schedules: Schedule[]): void {
        schedulesUl = $('#setting-bar-schedules-list');
        schedulesBtn = $('#setting-bar-schedules-btn');
        editBtn = $('#setting-bar-edit-btn');
        saveBtn = $('#setting-bar-save-btn');
        nameText = $('#setting-bar-schedule-name');
        nameTextInput = $('#setting-bar-schedule-name-edit');
        starBtn = $('#setting-bar-star');
        delBtn = $('#setting-bar-delete-btn');
        delBtn.on('click', () => {
            delBtn.toggleClass('active');
            deleted = !deleted;
        });

        if (schedules.length > 0) {
            populateDropdown(schedules);
            editBtnSetup();
            saveBtnSetup();

            setText(schedules.length > 0 ? schedules[0].name : 'No schedules found');
            starBtn.on('click', () => {
                starred = !starred;
                starBtnStarConfig(starred);
            });
        } else {
            setText('No schedules found');
            schedulesBtn.hide();
            editBtn.hide();
        }
    }

    export function update(schedules: Schedule[], selected: Schedule): void {
        populateDropdown(schedules);
        setText(selected.name);
    }

    export function updateCurrent(schedule: Schedule) {
        starred = false;
        setText(schedule.name);
    }

    export function off() {
        setText('No schedules found');
        schedulesBtn.hide();
        editBtn.hide();
    }


}