<?php

namespace App\Operations;

use App\DnFiles;
use App\DnFilesHomework;
use App\Homework;
use App\Lesson;
use App\LessonsTeachers;
use App\Subject;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class HomeworkOperation extends AbstractDnOperation
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

    public function getMyHomeworkBySchool($school_id, $start_date, $end_date)
    {
        $response = $this->client->get('users/me/school/' . $school_id . '/homeworks', [
            'query' => [
                'startDate' => $start_date,
                'endDate' => $end_date
            ]
        ])->getBody()->getContents();
        $data = json_decode($response, true);
        foreach ($data['works'] as $work) {
            Homework::updateOrCreate([
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
            ]);
            foreach ($work['files'] as $file) {
                DnFilesHomework::updateOrCreate([
                    'homework_dn_id' => $work['id'],
                    'file_dn_id' => $file,
                ]);
            }
        }

        foreach ($data['subjects'] as $subject) {
            Subject::updateOrCreate([
                'dn_id' => $subject['id'],
                'name' => $subject['name'],
                'knowledge_area_id' => $subject['knowledgeAreaId']
            ]);
        }

        foreach ($data['files'] as $file) {
            DnFiles::updateOrCreate([
                'dn_id' => $file['id'],
                'dn_str_id' => $file['id_str'],
                'name' => $file['name'],
                'type_group' => $file['typeGroup'],
                'type' => $file['type'],
                'page_url' => $file['pageUrl'],
                'download_url' => $file['downloadUrl'],
                'owner_dn_uid' => $file['user']['id'],
                'size' => $file['size'],
                'uploaded_date' => (new \DateTime($file['uploadedDate']))->format('Y-m-d H:i:s'),
                'storage_type' => $file['storageType'],
            ]);
            $user = $file['user'];
            User::updateOrCreate([
                'dn_uid' => $user['id']
            ],[
                'dn_uid' => $user['id'],
                'person_id' => $user['personId'],
                'short_name' => $user['shortName'],
                'timezone' => $user['timezone'],
                'sex' => $user['sex'],
            ]);
        }

        foreach ($data['teachers'] as $teacher) {
            User::updateOrCreate([
                'dn_uid' => $teacher['userId']
            ],[
                'dn_uid' => $teacher['userId'],
                'person_id' => $teacher['id'],
                'short_name' => $teacher['shortName'],
                'sex' => $teacher['sex'],
            ]);
        }

        foreach ($data['lessons'] as $lesson) {
            Lesson::updateOrCreate([
                'dn_id' => $lesson['id']
            ], [
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
            ]);
            foreach ($lesson['teachers'] as $teacher) {
                LessonsTeachers::updateOrCreate([
                    'lesson_dn_id' => $lesson['id'],
                    'teacher_dn_id' => $teacher,
                ]);
            }
        }

        return Homework::with(['lesson', 'subject', 'files'])->where([
            ['eg_id', '=', Auth::user()->eg_id],
            ['target_date', '>=', $start_date],
            ['target_date', '<=', $end_date],
        ])->get();
    }
}
