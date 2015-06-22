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

    public function getMessagesSet($channelId)
    {
        $messagesSet = Message::getMessagesSet($channelId);
        return response()->json($messagesSet);
    }

    public function setMessageGot($channelId, $messageId)
    {
        $m = Message::setMessageStatusGot($channelId, $messageId);
        return response()->json($m);
    }
}
