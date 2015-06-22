<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Channel;
use App\Models\Message;

class ChannelController extends BaseController
{

    public function all()
    {
        $channels = Channel::all();
        foreach ($channels as $channel) {
            $channel->messagesCount = $channel->messages()->count();
        }
        return view('channelList', [
            'channels' => $channels
        ]);
    }

    public function get($id)
    {
        $channel = Channel::findOrFail($id);
        return view('channelView', [
            'channel' => $channel
        ]);
    }

    public function edit($id = null)
    {
        if (empty($id)) {
            $channel = new Channel();
        } else {
            $channel = Channel::findOrFail($id);
        }
        return view('channelEdit', [
            'channel' => $channel
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
        $attributes = Input::only('label', 'description');
        
        if (empty($id)) {
            $channel = \App\Models\Channel::create($attributes);
        } else {
            $channel = \App\Models\Channel::findOrFail($id);
            $channel->fill($attributes);
        }
        
        if (! $channel->save()) {
            return view('channelEdit', [
                'channel' => $channel
            ])->withErrors($channel->getErrors()); // ->withInput();
        }
        
        return redirect('channel/' . $channel->id);
    }

    public function delete($id)
    {
        $channel = \App\Models\Channel::findOrFail($id);
        $channel->delete();
        
        return route('Home');
    }
}
