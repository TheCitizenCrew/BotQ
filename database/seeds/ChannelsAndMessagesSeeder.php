<?php
use Illuminate\Database\Seeder;
use Monolog\Handler\error_log;

class ChannelsAndMessagesSeeder extends Seeder
{

    public function run()
    {
        //DB::table('channels')->delete();
        // cascading delete
        // DB::table('messages')->delete();
        
        $channel = \App\Models\Channel::create([
            'label' => 'Channel #1',
            'description' => 'Une seed channel'
        ]);
        
        if( ! $channel->id )
        {
            error_log(var_export($channel->getErrors(),true));            
        }

        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg #1',
            'priority' => 100,
            'concurentAction' => 'stop',
            'playloop' => false,
            'content_type' => 'application/url',
            'content' => 'http://sanibot.org',
            /*'status_got' => null,
            'status_done' => null,
            'status_aborted' => null*/
        ]);
    }
}
