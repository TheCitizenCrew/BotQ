<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Channel;
use App\Models\Message;

class MessageController extends BaseController
{

    /*
     * public function all($channelId)
     * {
     * $messages = Channel::find($channelId)->messages();
     * return view('MessageList', ['channels'=>$channels]);
     * }
     */
    public function get($id)
    {
        $message = Message::findOrFail($id);
        return view('messageView', [
            'message' => $message
        ]);
    }

    public function edit($id = null, $channelId = null)
    {
        if (empty($id)) {
            $message = new Message();
            $message->channel_id = $channelId;
        } else {
            $message = Message::findOrFail($id);
        }
        return view('messageEdit', [
            'message' => $message
        ]);
    }

    public function save(Request $request)
    {
        return $this->store($request, null);
    }

    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }

    protected function store(Request $request, $id)
    {
        // Create a new Channel or retreive the one with $id
        
        // $attributes = Input::only( 'label' );
        $attributes = Input::all();
        
        if (empty($id)) {
            $message = \App\Models\Message::create($attributes);
        } else {
            $message = \App\Models\Message::findOrFail($id);
            $message->fill($attributes);
        }
        
        if( $message->play_at_time == '' )
            $message->play_at_time = null ;
        if( $message->status_got == '' )
            $message->status_got = null ;

        if (! $message->save()) {
            return view('messageEdit', [
                'message' => $message
            ])->withErrors($message->getErrors()); // ->withInput();
        }
        
        return redirect(route('ChannelGet', [
            'id' => $message->channel_id
        ]));
    }

    public function delete($id)
    {
        $message = \App\Models\Message::findOrFail($id);
        $channelId = $message->channel_id;
        $message->delete();
        
        return redirect(route('ChannelGet', [
            'id' => $channel_id
        ]));
    }
}
