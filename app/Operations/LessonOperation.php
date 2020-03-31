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

class LessonOperation extends AbstractDnOperation
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

    public function getLessonsByEduGroup($eg_id, $start_date, $end_date)
    {
        $response = $this->client->get("edu-groups/{$eg_id}/lessons/{$start_date}/{$end_date}")->getBody()->getContents();
        $data = json_decode($response, true);

        return;
    }
}
