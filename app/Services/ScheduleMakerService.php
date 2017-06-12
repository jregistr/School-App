<?php

namespace App\Services;


use App\Models\GeneratorList;
use App\Models\Course;
use App\Models\GeneratorListEntry;
use App\Models\MeetingTime;
use App\Models\Schedule;
use App\Models\Section;
use App\Util\C;
use Illuminate\Support\Facades\DB;
use \DateTime;

class Selection
{

    /**
     * @var Course
     */
    public $course;

    /**
     * @var Section
     */
    public $section;

    /**
     * @var MeetingTime
     */
    public $meeting;

    /**
     * @var boolean
     */
    public $required = false;

    /**
     * Selection constructor.
     * @param Course $course
     * @param Section $section
     * @param MeetingTime $meeting
     * @param boolean $required
     */
    public function __construct(Course $course, Section $section, MeetingTime $meeting, $required)
    {
        $this->course = $course;
        $this->section = $section;
        $this->meeting = $meeting;
        $this->required = $required;
    }

}

class ScheduleMakerService
{
    private $scheduleService;

    /**
     * ScheduleMakerService constructor.
     * @param ScheduleService $scheduleService
     */
    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * @param $studentId - Id of the student to perform operations for.
     * @return Schedule[] - The generated schedules;
     */
    public function generateSchedules($studentId)
    {
        $this->clearCurrentGenerated($studentId);
        $generatorListObj = GeneratorList::where(C::STUDENT_ID, $studentId)->first();
        if ($generatorListObj != null) {
            $creditLimit = $generatorListObj->credit_limit;

            $selections = $this->getGeneratorSelections($generatorListObj);
            $requiredSelections = array_filter($selections, function (Selection $selection) {
                return $selection->required;
            });

            $optionalSelections = array_filter($selections, function (Selection $selection) {
                return !$selection->required;
            });

            $generatedSchedules = [];

            $createdRequiredSchedules = $this->processRequiredSelections($requiredSelections, $creditLimit);

            if (count($createdRequiredSchedules) > 0) {
                foreach ($createdRequiredSchedules as $createdRequiredSchedule) {
                    $newSchedules = $this->processOptionalSections($createdRequiredSchedule, $optionalSelections, $creditLimit);
                    foreach ($newSchedules as $newSchedule) {
                        array_push($generatedSchedules, $newSchedule);
                    }
                }
            } else {
                $newSchedules = $this->processOptionalSections([], $optionalSelections, $creditLimit);
                foreach ($newSchedules as $newSchedule) {
                    array_push($generatedSchedules, $newSchedule);
                }
            }
            error_log(count($generatedSchedules));
            $uniques = [];
            foreach ($generatedSchedules as $generatedSchedule) {
                if ($this->isUnique($generatedSchedule, $uniques)) {
                    array_push($uniques, $generatedSchedule);
                }
            }

            error_log(count($uniques));
            $schedules = $this->querySaveSelections($uniques, $studentId);
            return $schedules;
        } else {
            return [];
        }
    }

    /**
     * @param Selection[] $schedule - The schedule to check for uniqueness.
     * @param Selection[][] $list - The list to check against.
     * @return  boolean - If the given schedule is unique.
     */
    private function isUnique($schedule, $list)
    {
        $result = true;
        foreach ($list as $otherSchedule) {
            $count = 0;
            foreach ($schedule as $selection) {
                if ($this->existsIn($selection, $otherSchedule)) {
                    $count += 1;
                }
            }
            if ($count == count($otherSchedule)) {
                $result = false;
                break;
            }
        }
        return $result;
    }

    /**
     * @param Selection $selection
     * @param Selection[] $list
     * @return boolean - if it is found.
     */
    private function existsIn($selection, $list)
    {
        $result = false;
        foreach ($list as $item) {
            if ($item->course->id == $selection->course->id &&
                $item->section->id == $selection->section->id &&
                $item->meeting->id == $selection->meeting->id
            ) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    /**
     * Creates schedules with the required selections.
     * @param Selection[] $requiredSelects - The required selections.
     * @param int $creditLimit - The credit limit for this generation procedure.
     * @return Selection[][] - The possible schedules as a 2D array of selections.
     * @throws \Exception
     */
    private function processRequiredSelections($requiredSelects, $creditLimit)
    {
        $generatedSchedules = [];

        foreach ($requiredSelects as $requiredSelect) {
            $currentGeneratedSchedule = [];
            array_push($currentGeneratedSchedule, $requiredSelect);
            $totalCredits = 0;

            foreach ($requiredSelects as $possibleAdd) {
                if (($possibleAdd->course->credits + $totalCredits) <= $creditLimit) {
                    if ((!$this->hasTimeConflictWithAny($possibleAdd, $currentGeneratedSchedule))
                        && $this->passedCourseCondition($possibleAdd, $currentGeneratedSchedule)
                    ) {
                        $totalCredits += $possibleAdd->course->credits;
                        array_push($currentGeneratedSchedule, $possibleAdd);
                    }
                }
            }

            array_push($generatedSchedules, $currentGeneratedSchedule);
        }

        return $generatedSchedules;
    }

    /**
     * Generates multiple probable schedules given a schedule with the required courses in it and
     * a list of optional selections.
     * @param Selection[] $requiredBaseSchedule - The schedule of the required courses.
     * @param Selection[] $optionalSelections - The optional selections.
     * @param int $creditLimit - The credit limitation.
     * @return Selection[][] - The newly generated schedules.
     */
    private function processOptionalSections($requiredBaseSchedule, $optionalSelections, $creditLimit)
    {
        $optionals = $this->copyScheduleData($optionalSelections);
        $generatedSchedules = [];

        {
            $totalCredits = $this->countCredits($requiredBaseSchedule);
            $toRemove = [];
            foreach ($optionals as $optionalSelection) {
                if (($optionalSelection->course->credits + $totalCredits) > $creditLimit) {
                    //remove it
                    array_push($toRemove, $optionalSelection);
                } else {
                    if (($this->hasTimeConflictWithAny($optionalSelection, $requiredBaseSchedule))
                        || (!$this->passedCourseCondition($optionalSelection, $requiredBaseSchedule))
                    ) {
                        //remove it
                        array_push($toRemove, $optionalSelection);
                    }
                }
            }

            foreach ($toRemove as $temp) {
                $index = array_search($temp, $optionals);
                if ($index !== false) {
                    array_splice($optionals, $index, 1);
                }
            }
        }

        if (count($optionals) == 0) {
            array_push($generatedSchedules, $requiredBaseSchedule);
        } else {
            foreach ($optionals as $possibleAdd) {
                $totalCredits = $this->countCredits($requiredBaseSchedule);
                $currentGeneratedSchedule = $this->copyScheduleData($requiredBaseSchedule);
                array_push($currentGeneratedSchedule, $possibleAdd);

                foreach ($optionals as $optional) {
                    if (($optional->course->credits + $totalCredits) <= $creditLimit) {
                        if ((!$this->hasTimeConflictWithAny($optional, $currentGeneratedSchedule))
                            && $this->passedCourseCondition($optional, $currentGeneratedSchedule)
                        ) {
                            $totalCredits += $optional->course->credits;
                            array_push($currentGeneratedSchedule, $optional);
                        }
                    }
                }
                array_push($generatedSchedules, $currentGeneratedSchedule);
            }
        }
        return $generatedSchedules;
    }

    /**
     * @param Selection[] $scheduleData - The schedule data.
     * @return int - The total.
     */
    private function countCredits($scheduleData)
    {
        $total = 0;
        foreach ($scheduleData as $selection) {
            $total += $selection->course->credits;
        }
        return $total;
    }

    /**
     * @param Selection[] $selections - The selections to copy.
     * @return Selection[] - A copy of the provided array.
     */
    private function copyScheduleData($selections)
    {
        $result = [];
        foreach ($selections as $selection) {
            array_push($result, $selection);
        }
        return $result;
    }

    /**
     * Checks if a course with the same id is already in the list. If so, if the section is the same then it passes
     * and can be added, otherwise, no.
     * @param Selection $selection - The selection to test.
     * @param Selection[] $list - The list to check against.
     * @return boolean - If it can be added.
     */
    private function passedCourseCondition($selection, $list)
    {
        $result = true;
        foreach ($list as $item) {
            if ($item->course->id == $selection->course->id) {
                if ($item->section->id != $selection->section->id) {
                    $result = false;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @param Selection $selection - The selection to check for conflict against the list.
     * @param Selection[] $againstList - The list to compare against.
     * @return bool - If any time conflict exist by the selection against the list.
     */
    private function hasTimeConflictWithAny($selection, $againstList)
    {
        $result = false;

        $selMeeting = $selection->meeting;
        $selStart = $this->mkDateTime($selMeeting->start);
        $selEnd = $this->mkDateTime($selMeeting->end);

        foreach ($againstList as $against) {
            $againstMeeting = $against->meeting;
            $againstStart = $this->mkDateTime($againstMeeting->start);
            $againstEnd = $this->mkDateTime($againstMeeting->end);

            $checkStart = $selStart >= $againstStart && $selStart <= $againstEnd;
            $checkEnd = $selEnd >= $againstStart && $selEnd <= $againstEnd;
            if ($checkStart || $checkEnd) {
                if ($this->anyDaysMatch($selMeeting, $againstMeeting)) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * @param $time - Time string.
     * @return DateTime - A date time object.
     * @throws \Exception - thrown if converting the time failed.
     */
    function mkDateTime($time)
    {
        $dt = DateTime::createFromFormat('H:i', $time);
        if ($dt == false) {
            throw new \Exception('Time mismatch format');
        }
        return $dt;
    }

    /**
     * Checks if there is any match in meeting days for the two given meetings.
     * @param MeetingTime $a - The first meeting.
     * @param MeetingTime $b - The second meeting.
     * @return boolean - If any of the meeting days match.
     */
    private function anyDaysMatch($a, $b)
    {
        $a1 = $a->sunday == 1 && $b->sunday == 1;
        $a2 = $a->monday == 1 && $b->monday == 1;
        $a3 = $a->tuesday == 1 && $b->tuesday == 1;
        $a4 = $a->wednesday == 1 && $b->wednesday == 1;
        $a5 = $a->thursday == 1 && $b->thursday == 1;
        $a6 = $a->friday == 1 && $b->friday == 1;
        $a7 = $a->saturday == 1 && $b->saturday == 1;
        return $a1 || $a2 || $a3 || $a4 || $a5 || $a6 || $a7;
    }

    /**
     * @param GeneratorList $generator - The student's generator list.
     * @return Selection[] - schedule selections.
     */
    private function getGeneratorSelections($generator)
    {
        if ($generator != null) {
            $result = [];
            $entries = GeneratorListEntry::where(C::GENERATOR_LIST_ID, $generator->id)
                ->orderBy(C::SECTION_ID, 'DESC')
                ->orderBy(C::MEETING_ID, 'DESC')
                ->get();
            foreach ($entries as $entry) {
                array_push($result, $this->getGeneratorSelection($entry));
            }
            return $result;
        } else {
            return [];
        }
    }

    /**
     * @param GeneratorListEntry $entry - The entry to make a selection from.
     * @return Selection - A selection object.
     */
    private function getGeneratorSelection($entry)
    {
        $section = Section::find($entry->section_id);
        $meeting = MeetingTime::find($entry->meeting_id);
        $course = $section->course();
        $required = $entry->required;
        return new Selection($course, $section, $meeting, $required);
    }

    /**
     * Queries to save schedules and schedule entries.
     * @param Selection[][] $schedulesData - Schedules represented as a 2D array.
     * @param $studentId - Student id.
     * @return Schedule[] - The generated schedules.
     */
    private function querySaveSelections($schedulesData, $studentId)
    {
        $schedules = [];
        $counter = 0;
        foreach ($schedulesData as $data) {
            $counter++;
            $schedule = Schedule::create([
                C::STUDENT_ID => $studentId,
                C::ADDED => 0,
                C::IS_PRIMARY => 0,
                C::NAME => 'Generated Schedule ' . $counter
            ]);
            array_push($schedules, $schedule);

            $entries = array_map(function (Selection $selection) use ($schedule) {
                return [
                    C::SCHEDULE_ID => $schedule->id,
                    C::SECTION_ID => $selection->section->id,
                    C::MEETING_TIME_ID => $selection->meeting->id
                ];
            }, $data);

            DB::table('schedule_section')->insert($entries);
        }
        return $schedules;
    }

    /**
     * Deletes all generated schedules.
     * @param $studentId - The student's id.
     */
    private function clearCurrentGenerated($studentId)
    {
        Schedule::where([
            [C::STUDENT_ID, '=', $studentId],
            [C::ADDED, '=', 0]
        ])->delete();
    }

}