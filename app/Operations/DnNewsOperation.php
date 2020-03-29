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

    public function getSchoolNews($school_id, $limit)
    {
        $user = Auth::user();
        $cookie_file_jar = (new DnCookiesFileOperation())->getMyCookiesFileJar();

        $news_request = $this->client->get('posts/topic/school_' . $user->school_id, [
            'cookies' => $cookie_file_jar,
            'query' => [
                'take' => $limit
            ]
        ]);

        $news_decoded_json = json_decode($news_request->getBody()->getContents(), true);
        $posts = $news_decoded_json['posts'];
        foreach ($posts as $post) {
            $thread_id = Thread::firstOrCreate([
                'event_key' => $post['thread']['eventKey']
            ]);

            foreach ($post['thread']['comments'] as $comment) {
                ThreadComment::updateOrCreate([
                    'dn_id' => $comment['id']
                ], [
                    'dn_id' => $comment['id'],
                    'reply_uid' => $comment['replyUserId'],
                    'author_uid' => $comment['authorId'],
                    'dn_created_at' => date('Y-m-d H:i:s', $comment['createdDateTime']),
                    'text' => $comment['text'],
                    'thread_id' => $thread_id->id,
                ]);
            }
            $news_date = !empty($post['createdDate']) ? date('Y-m-d H:i:s', $post['createdDate']) : date('Y-m-d H:i:s');
            News::updateOrCreate([
                'dn_news_id' => $post['id']
            ], [
                'dn_news_id' => $post['id'],
                'text' => $post['text'],
                'topic_name' => $post['topicName'],
                'views_count' => $post['viewsCount'],
                'author_uid' => $post['author']['id'],
                'dn_created_at' => $news_date,
                'school_id' => $user->school_id,
                'thread_id' => $thread_id->id,
                'eg_id' => null,
            ])->get();
        }

        return News::where(['school_id' => $school_id])
            ->orderBy('dn_created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getEduGroupNews($school_id, $eg_id)
    {

    }
}
