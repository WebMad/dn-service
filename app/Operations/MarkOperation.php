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
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class MarkOperation extends AbstractDnOperation
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

    public function getMyMarksByPersonAndSchools($person_id, $school_id, $start_date, $end_date)
    {
        $params_operation = new ParamsOperation();
        $response = $this->client->get("persons/{$person_id}/schools/{$school_id}/marks/{$start_date}/{$end_date}")->getBody()->getContents();

        $marks = json_decode($response, true);
        foreach ($marks as $mark) {
            Mark::updateOrCreate([
                'dn_id' => $mark['id']
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

        return Mark::where([
            ['date', '>=', $start_date],
            ['date', '<=', $end_date],
            ['person_id', '=', $person_id],
        ])->get();
    }
}
