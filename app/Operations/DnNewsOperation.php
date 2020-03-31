<?php


namespace App\Operations;


use App\DnCookiesFile;
use App\News;
use App\Thread;
use App\ThreadComment;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class DnNewsOperation extends AbstractDnOperation
{
    public $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://dnevnik.ru/api/',
        ]);

    }

    public function getMixNews($school_id, $eg_id, $limit)
    {
        $this->getSchoolNews($school_id, $limit);
        $this->getEduGroupNews($school_id, $eg_id, $limit);

        return News::where([
            'school_id' => $school_id,
        ])->orderBy('dn_created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getSchoolNews($school_id, $limit)
    {
        $cookie_file_jar = (new DnCookiesFileOperation())->getMyCookiesFileJar();

        $news_request = $this->client->get('posts/topic/school_' . $school_id, [
            'cookies' => $cookie_file_jar,
            'query' => [
                'take' => $limit
            ]
        ]);

        $this->parseAndSaveNewsFromDn($news_request->getBody()->getContents(), $school_id);

        return News::where([
            'school_id' => $school_id,
            'eg_id' => null,
        ])->orderBy('dn_created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getEduGroupNews($school_id, $eg_id, $limit)
    {
        $cookie_file_jar = (new DnCookiesFileOperation())->getMyCookiesFileJar();

        $news_request = $this->client->get('posts/topic/school_' . $school_id . '_group_' . $eg_id, [
            'cookies' => $cookie_file_jar,
            'query' => [
                'take' => $limit
            ]
        ]);
        echo $news_request->getBody()->getContents();
        die();
        $this->parseAndSaveNewsFromDn($news_request->getBody()->getContents(), $school_id, $eg_id);

        return News::where([
            'school_id' => $school_id,
            'eg_id' => $eg_id
        ])->orderBy('dn_created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    private function parseAndSaveNewsFromDn($json, $school_id, $eg_id = null)
    {
        $params_operation = new ParamsOperation();
        $news_decoded_json = json_decode($json, true);
        $posts = $news_decoded_json['posts'];
        foreach ($posts as $post) {
            $thread_id = Thread::firstOrCreate($params_operation->buildArray([
                'event_key' => $post['thread']['eventKey']
            ]));

            foreach ($post['thread']['comments'] as $comment) {
                ThreadComment::updateOrCreate([
                    'dn_id' => $comment['id']
                ], $params_operation->buildArray([
                    'dn_id' => $comment['id'],
                    'reply_uid' => $comment['replyUserId'],
                    'author_uid' => $comment['authorId'],
                    'dn_created_at' => date('Y-m-d H:i:s', $comment['createdDateTime']),
                    'text' => $comment['text'],
                    'thread_id' => $thread_id->id,
                ]));
            }
            $news_date = !empty($post['createdDate']) ? date('Y-m-d H:i:s', $post['createdDate']) : date('Y-m-d H:i:s');
            News::updateOrCreate([
                'dn_news_id' => $post['id']
            ], $params_operation->buildArray([
                'dn_news_id' => $post['id'],
                'text' => $post['text'],
                'topic_name' => $post['topicName'],
                'views_count' => $post['viewsCount'],
                'author_uid' => $post['author']['id'],
                'dn_created_at' => $news_date,
                'school_id' => $school_id,
                'thread_id' => $thread_id->id,
                'eg_id' => $eg_id,
            ]))->get();
        }
    }
}
