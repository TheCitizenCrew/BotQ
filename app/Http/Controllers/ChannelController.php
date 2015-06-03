<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input ;
use App\Models\Channel;
use App\Models\Message;

class ChannelController extends BaseController
{
    public function channelList()
    {
        $channels = Channel::all();
        foreach( $channels as $channel)
        {
            $channel->messagesCount = $channel->messages()->count();
        }
        return view('channelList', ['channels'=>$channels]);
    }

    public function channelGet($id,$editMsgId=null)
    {
        $channel = Channel::findOrFail($id);
        return view('channelView', ['channel'=>$channel, 'editMsgId'=>$editMsgId]);
    }

    public function channelNew()
    {
        return $this->channelEdit();
    }

    public function channelEdit( $id=null )
    {
        if( empty($id) )
        {
            $channel = new Channel();
        }
        else
        {
            $channel = Channel::findOrFail($id);
    
        }
        return view('channelEdit', ['channel'=>$channel]);
    }

    public function channelSave( Request $request )
    {
        return $this->channelStore( $request , null);
    }

    public function channelUpdate(Request $request, $id)
    {
        return $this->channelStore( $request, $id );
    }

    protected function channelStore( Request $request, $id )
    {
        // Create a new Channel or retreive the one with $id

        $attributes = Input::only( 'label', 'description' );

        if( empty($id) )
        {
            $channel = \App\Models\Channel::create( $attributes );
        }
        else
        {
            $channel = \App\Models\Channel::findOrFail( $id );
            $channel->fill( $attributes );
        }

        if( ! $channel->save() )
        {
            return view('channelEdit', ['channel'=>$channel])
                ->withErrors( $channel->getErrors() )
                ;//->withInput();
        }

        return redirect( 'channel/' . $channel->id );
    }

    public function channelDelete($id)
    {
        $channel = \App\Models\Channel::findOrFail( $id );
        $channel->delete();

        return route('Home');
    }

}
