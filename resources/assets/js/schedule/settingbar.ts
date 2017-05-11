import {Schedule} from './interfaces';

export module settingbar {

    const ID_SCHEDULE_LIST: string = 'setting-bar-schedules-list';
    const ID_SCHEDULES_BTN: string = 'setting-bar-schedules-btn';
    const ID_EDIT_BTN: string = 'setting-bar-edit-btn';
    const ID_SAVE_BTN: string = 'setting-bar-save-btn';
    const ID_NAME: string = 'setting-bar-schedule-name';
    const ID_NAME_EDIT: string = 'setting-bar-schedule-name-edit';
    const ID_STAR: string = 'setting-bar-star';

    export let onSelectSchedule: (schedule: Schedule) => void;
    export let onEditClicked: () => boolean;
    export let onSaveClicked: (newValue: string, starred: boolean) => void;

    let schedulesUl: JQuery;
    let schedulesBtn: JQuery;
    let editBtn: JQuery;
    let saveBtn: JQuery;
    let nameText: JQuery;
    let nameTextInput: JQuery;
    let starBtn: JQuery;

    let starred: boolean = false;

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

            if (onEditClicked != null) {
                const isSelected = starred = onEditClicked();
                starBtnStarConfig(isSelected);
                starBtn.show();
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
                onSaveClicked(name, starred);

            starred = false;
            starBtn.hide();
        });
    }

    export function init(schedules: Schedule[]): void {
        schedulesUl = $('#'.concat(ID_SCHEDULE_LIST));
        schedulesBtn = $('#'.concat(ID_SCHEDULES_BTN));
        editBtn = $('#'.concat(ID_EDIT_BTN));
        saveBtn = $('#'.concat(ID_SAVE_BTN));
        nameText = $('#'.concat(ID_NAME));
        nameTextInput = $('#'.concat(ID_NAME_EDIT));
        starBtn = $('#'.concat(ID_STAR));

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


}