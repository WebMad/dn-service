<?php

namespace App\Operations;

use App\DnFiles;
use App\DnFilesHomework;
use App\Homework;
use App\Lesson;
use App\LessonsTeachers;
use App\Mark;
use App\Subject;
use App\User;
use App\WorkType;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class ScheduleOperation extends AbstractDnOperation
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.dnevnik.ru/v2.0/',
            'headers' => [
                'Access-Token' => Auth::user()->dn_access_token
            ],
        ]);
    }

    public function getScheduleByEduGroupAndPerson($eg_id, $person_id, $start_date, $end_date)
    {
        $params_operation = new ParamsOperation();
        $response = $this->client->get("persons/{$person_id}/groups/{$eg_id}/schedules", [
            'query' => [
                'startDate' => $start_date,
                'endDate' => $end_date
            ]
        ])->getBody()->getContents();
        $data = json_decode($response, true);

        foreach ($data['days'] as $day) {
            foreach ($day['works'] as $work) {
                Homework::updateOrCreate($params_operation->buildArray([
                    'dn_id' => $work['id'],
                    'dn_str_id' => $work['id_str'],
                    'type' => $work['type'],
                    'work_type' => $work['workType'],
                    'mark_type' => $work['markType'],
                    'mark_count' => $work['markCount'],
                    'lesson_id' => $work['lesson'],
                    'lesson_str_id' => $work['lesson_str'],
                    'display_in_journal' => $work['displayInJournal'],
                    'status' => $work['status'],
                    'eg_id' => $work['eduGroup'],
                    'eg_str_id' => $work['eduGroup_str'],
                    'text' => $work['text'],
                    'period_number' => $work['periodNumber'],
                    'period_type' => $work['periodType'],
                    'subject_dn_id' => $work['subjectId'],
                    'is_important' => $work['isImportant'],
                    'target_date' => (new \DateTime($work['targetDate']))->format('Y-m-d H:i:s'),
                    'sent_date' => (new \DateTime($work['sentDate']))->format('Y-m-d H:i:s'),
                    'created_by' => $work['createdBy'],
                ]));
                foreach ($work['files'] as $file) {
                    DnFilesHomework::updateOrCreate($params_operation->buildArray([
                        'homework_dn_id' => $work['id'],
                        'file_dn_id' => $file,
                    ]));
                }
            }

            foreach ($day['lessons'] as $lesson) {
                Lesson::updateOrCreate([
                    'dn_id' => $lesson['id']
                ], $params_operation->buildArray([
                    'dn_id' => $lesson['id'],
                    'title' => $lesson['title'],
                    'date' => $lesson['date'],
                    'number' => $lesson['number'],
                    'subject_id' => $lesson['subjectId'],
                    'status' => $lesson['status'],
                    'result_place_id' => $lesson['resultPlaceId'],
                    'building' => $lesson['building'],
                    'place' => $lesson['place'],
                    'floor' => $lesson['floor'],
                    'hours' => $lesson['hours'],
                ]));
                foreach ($lesson['teachers'] as $teacher) {
                    LessonsTeachers::updateOrCreate($params_operation->buildArray([
                        'lesson_dn_id' => $lesson['id'],
                        'teacher_dn_id' => $teacher,
                    ]));
                }
            }
            foreach ($day['marks'] as $mark) {
                Mark::updateOrCreate([
                    'dn_id' => $mark['id'],
                ], $params_operation->buildArray([
                    'dn_id' => $mark['id'],
                    'dn_str_id' => $mark['id_str'],
                    'type' => $mark['type'],
                    'value' => $mark['value'],
                    'textValue' => $mark['textValue'],
                    'person_id' => $mark['person'],
                    'person_str_id' => $mark['person_str'],
                    'homework_id' => $mark['work'],
                    'homework_str_id' => $mark['work_str'],
                    'lesson_id' => $mark['lesson'],
                    'lesson_str_id' => $mark['lesson_str'],
                    'number' => $mark['number'],
                    'date' => (new \DateTime($mark['date']))->format('Y-m-d H:i:s'),
                    'work_type' => $mark['workType'],
                    'mood' => $mark['mood'],
                    'use_avg_calc' => $mark['use_avg_calc'],
                ]));
            }

            foreach ($day['workTypes'] as $workType) {
                WorkType::updateOrCreate($params_operation->buildArray([
                    'dn_id' => $workType['id'],
                    'school_id' => $workType['schoolId'],
                    'abbreviation' => $workType['abbreviation'],
                    'name' => $workType['name'],
                    'is_final' => $workType['isFinal'],
                    'is_important' => $workType['isImportant'],
                    'kind_id' => $workType['kindId'],
                    'kind' => $workType['kind'],
                ]));
            }
            foreach ($day['teachers'] as $teacher) {
                $teacher = $teacher['person'];
                User::updateOrCreate([
                    'dn_uid' => $teacher['userId']
                ], $params_operation->buildArray([
                    'dn_uid' => $teacher['userId'],
                    'person_id' => $teacher['id'],
                    'short_name' => $teacher['shortName'],
                    'sex' => $teacher['sex'],
                ]));
            }
        }
        return Lesson::with([
            'subject',
            'homework',
            'marks',
            'homework.files'
        ])->where([
            ['date', '>=', $start_date],
            ['date', '<=', $end_date],
        ])->whereIn('dn_id', Homework::where([
            ['target_date', '>=', $start_date],
            ['target_date', '<=', $end_date],
            ['eg_id', '=', $eg_id],
        ])->pluck('lesson_id'))->get();
    }
}
