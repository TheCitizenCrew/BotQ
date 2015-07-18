<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Message;

/**
 *
 * @author cyrille
 */
class ApiController extends Controller
{

    public function stats()
    {
        $stats = array(
            'messagesCount' => Message::all()->count(),
            'channelsCount' => Channel::all()->count()
        );
        return response()->json($stats);
    }

    /**
     * Select 2 messages as client's jobs
     *
     * Order : priority, play_time, id
     *
     * @param unknown $channelId            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMessagesSet($channelId)
    {
        $d = new \Carbon\Carbon();
        
        $q = Message::forChannel($channelId)->notDone()
            ->orderBy('priority', 'desc')
            ->orderBy('play_at_time', 'desc')
            ->orderBy('id', 'asc')
            ->limit(2);

        // Only minute, force seconds to 00
        $time_hms = str_pad($d->hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($d->minute, 2, '0', STR_PAD_LEFT) . ':00';
        // error_log( 'play_at_time: '. $time_hms );

        // and a nested query for 'play_at_time'
        $m = new Message();
        $q2 = $m->newQueryWithoutScopes()
            ->where('play_at_time', '=', $time_hms)
            ->orWhere('play_at_time', '=', '');
        $q->addNestedWhereQuery($q2->getQuery());

        // error_log($q->toSql());
        $messagesSet = $q->get();

        return response()->json($messagesSet);
    }

    /**
     * 
     * @param number $channelId
     * @param number $messageId
     * @param string $status could be "got", "done", "aborted" or "reset"
     * @return \Symfony\Component\HttpFoundation\Response as JSON
     */
    public function setMessageStatus($channelId, $messageId, $status)
    {
        $m = Message::setMessageStatus($channelId, $messageId, $status);
        return response()->json($m);
    }

    public function addUrgentTextMessage($channelId, $text)
    {
        $text = urldecode($text);
        $text = str_replace('"', '\"', $text);

        $msg = \App\Models\Message::create([
            'channel_id' => $channelId,
            'label' => 'msg#' . time(),
            'priority' => 1000,
            'priority_action' => 'pause',
            'play_loop' => false,
            'play_duration' => 10*1000,
            'content_type' => 'text/plain',
            'content' => '{"text": "'.$text.'"}'
        ]);
        $msg->save();
        return response()->json($msg);
    }

}
