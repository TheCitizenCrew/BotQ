<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Message;

class ChannelController extends BaseController
{
    public function channelGet($id)
    {
        $channel = Channel::findOrFail($id);
        return view('channelView', ['channel'=>$channel]);
    }
    
    public function channelNew()
    {
        return $this->channelEdit();
    }
        
    protected function channelEdit( $id=null )
    {
        if( empty($id) )
        {
            $channel = new Channel();
        }
        else
        {
            $channel = Channel::findOrFail($id);
    
        }
        //return response()->json($channel);
        return view('channelEdit', ['channel'=>$channel]);
        
    }
    
    public function channelSave( Request $request )
    {
        return $this->update( $request , null);
    }
    
    public function channelUpdate(Request $request, $id)
    {
        return $this->update( $request, $id );
    }
    
    protected function update( Request $request, $id )
    {
        // Create a new Channel or retreive the one with $id
    
        if( empty($id) )
        {
            $channel = \App\Models\Channel::create( $request->all() );
        }
        else
        {
            $channel = \App\Models\Channel::findOrFail( $id );
            $channel->fill( $request->all() );
        }

        if( ! $channel->save() )
        {
            return view('channelEdit', ['channel'=>$channel])
                ->withErrors( $channel->getErrors() )
                ->withInput();
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
