<?php
use Illuminate\Database\Seeder;

class ChannelsAndMessagesSeeder extends Seeder
{

    public function run()
    {
        //DB::table('channels')->delete();
        // cascading delete
        // DB::table('messages')->delete();
        
        $channel = \App\Models\Channel::create([
            'Label' => 'Channel #1',
            'description' => 'Une seed channel'
        ]);
        $channel->save();
        /*$msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg #1',
            'priority' => 100,
            'concurentAction' => 'stop',
            'playloop' => false,
            'content_type' => 'application/url',
            'content' => 'http://sanibot.org',
            'status_got' => null,
            'status_done' => null,
            'status_aborted' => null
        ]);*/
    }
}
