<?php
use Illuminate\Database\Seeder;
use Monolog\Handler\error_log;

class ChannelsAndMessagesSeeder extends Seeder
{

    public function run()
    {
        // DB::table('channels')->delete();
        // cascading delete
        // DB::table('messages')->delete();
        $channel = \App\Models\Channel::create([
            'label' => 'Channel #1',
            'description' => 'Une seed channel'
        ]);
        
        if (! $channel->id) {
            error_log(var_export($channel->getErrors(), true));
        }
        
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg #1',
            'priority' => 100,
            'priority_action' => 'stop',
            'play_loop' => false,
            //'play_at_time' => '',
            //'play_duration' => 0,
            'content_type' => 'application/url',
            'content' => 'http://sanibot.org',
            //'status_got' => null,
            //'status_done' => null,
            //'status_aborted' => null
        ]);
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg #2',
            'content_type' => 'video/mp4',
            'content' => 'http://www.jplayer.org/video/m4v/Big_Buck_Bunny_Trailer.m4v',
        ]);
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg #3',
            'play_duration' => 5,
            'content_type' => 'application/url',
            'content' => 'http://sanilabo.org',
        ]);
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg #4',
            'content_type' => 'video/mp4',
            'content' => 'https://cloud.comptoir.net/public.php?service=files&t=daded4130946466782bd44adfabf7b30&download',
        ]);
    }
}
