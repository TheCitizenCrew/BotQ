<?php
use Illuminate\Database\Seeder;
use Monolog\Handler\error_log;

class ChannelsAndMessagesSeeder extends Seeder
{

    public function run()
    {
        DB::table('channels')->delete();
        // cascading delete
        // DB::table('messages')->delete();
        
        $channel = \App\Models\Channel::create([
            'label' => 'Channel #1',
            'description' => 'A seeded channel'
        ]);
        
        if (! $channel->id) {
            error_log(var_export($channel->getErrors(), true));
        }
        
        //$this->messsageSet01($channel);
        //$this->messsageSet02($channel);
        //$this->messsageSet03($channel);
        //$this->messsageSet_TestPlayLoop($channel);
        $this->messsageSet_TestServiceMessages( $channel );
    }

    function messsageSet01($channel)
    {
        $msgLabelIdx = 1;

        // normal message, play web page
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_loop' => false,
            'play_duration' => 10*1000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://sanilabo.org"}'
        ]);
        
        // normal message, play video
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'video/mp4',
            'content' => '{"url":"http://www.jplayer.org/video/m4v/Big_Buck_Bunny_Trailer.m4v"}'
        ]);
        
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'priority' => 100,
            'priority_action' => 'pause',
            // 'priority_action' => 'stop',
            'play_duration' => 10*1000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://comptoir.net"}'
        ]);
    }

    /**
     * test play_at_time message
     * 
     * @param unknown $channel            
     */
    function messsageSet02($channel)
    {
        $msgLabelIdx = 0;
        
        // normal message, play web page
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . (++ $msgLabelIdx),
            'play_duration' => 8000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://botq.localhost/pages/chrono.html#msg' . $msgLabelIdx . '"}'
        ]);
        // normal message, play web page
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . (++ $msgLabelIdx),
            'play_duration' => 5000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://botq.localhost/pages/chrono.html#msg' . $msgLabelIdx . '"}'
        ]);
        // at time message
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . (++ $msgLabelIdx),
            'priority_action' => 'pause',
            'play_at_time' => '11:00:00',
            'play_duration' => 5000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://botq.localhost/pages/chrono.html#msg' . $msgLabelIdx . '"}'
        ]);
    }

    /**
     * test priority and invalid content-type
     * 
     * @param unknown $channel            
     */
    function messsageSet03($channel)
    {
        $msgLabelIdx = 1;

        // normal message, play web page
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_loop' => false,
            'play_duration' => 10*1000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://sanilabo.org"}'
        ]);
        // normal message, play tts
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'application/tts',
            'content' => '{"text":"On dirait que BotQ est tombé en marche ! C\'est chouette"}'
        ]);
        // normal message, play video
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'video/mp4',
            'content' => '{"url":"http://www.jplayer.org/video/m4v/Big_Buck_Bunny_Trailer.m4v"}'
        ]);
        // unknow content type message
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'dummy',
            'content' => 'bla bla'
        ]);
        // normal message, play video
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'video/mp4',
            'content' => '{"url":"https://cloud.comptoir.net/public.php?service=files&t=daded4130946466782bd44adfabf7b30&download"}'
        ]);
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'priority' => 100,
            'priority_action' => 'pause',
            // 'priority_action' => 'stop',
            'play_duration' => 10*1000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://comptoir.net"}'
        ]);
    }

    /**
     * To test message.play_loop
     * 
     * @param number $channel
     */
    function messsageSet_TestPlayLoop($channel)
    {
        $msgLabelIdx = 1;

        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_loop' => '1',
            'play_duration' => 30*1000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://comptoir.net"}'
        ]);

    }
    
    function messsageSet_TestServiceMessages($channel)
    {
        $msgLabelIdx = 1;
    
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_duration' => 10*1000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://comptoir.net"}'
        ]);
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_duration' => 10*1000,
            'content_type' => 'application/url',
            'content' => '{"url": "http://sanilabo.org"}'
        ]);

        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'application/service',
            'content' => '{"command": "resetChannel"}'
        ]);
        
    }
    
}
