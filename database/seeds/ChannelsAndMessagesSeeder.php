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
        
        //$this->messsageSet_TestPriority1($channel);
        //$this->messsageSet_TestPlayAtTime($channel);
        //$this->messsageSet_TestPriority2($channel);
        //$this->messsageSet_TestPlayLoop($channel);
        //$this->messsageSet_TestServiceMessages( $channel );
        
        //$this->messsageSet_SaniBotProgrammationEssai1( $channel );
        $this->messsageSet_SaniBotProgrammation( $channel );
    }

    function messsageSet_TestPriority1($channel)
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
    function messsageSet_TestPlayAtTime($channel)
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
    function messsageSet_TestPriority2($channel)
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

    function messsageSet_SaniBotProgrammationEssai1($channel)
    {
        $duration = 10*60*1000 ;
        $duration = 30*1000 ;

        $msgLabelIdx = 1 ;

        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_loop' => false,
            'play_duration' => $duration,
            'content_type' => 'application/url',
            'content' => '{"url": "https://www.facebook.com/CentreSocialPlurielles/&output=embed"}'
        ]);

    }

    function messsageSet_SaniBotProgrammation($channel)
    {
        $duration = 10*60*1000 ;
        $duration = 30*1000 ;
        
        //$ttsurl = 'ws://localhost:8080/action';
        $ttsurl = 'ws://192.168.0.10:5000/action';

        $msgLabelIdx = 1 ;

        // normal message, play tts
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'application/tts',
            'content' => '{"text":"Bienvenue au Sanitas","url":"'.$ttsurl.'"}'
        ]);

        // normal message, play web page
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_loop' => false,
            'play_duration' => $duration,
            'content_type' => 'application/url',
            'content' => '{"url": "http://sanilabo.org", "css":"#theIframe{ position: absolute; top: -130px; left: 0; height: 660px; }"}'
        ]);

        // normal message, play tts
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'application/tts',
            'content' => '{"text":"On dirait que le SaniBot est tombé en marche ! C\'est chouette !","url":"'.$ttsurl.'"}'
        ]);

        /* Pas de Facebook because of X-Frame-Options DENIED
        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_loop' => false,
            'play_duration' => $duration,
            'content_type' => 'application/url',
            'content' => '{"url": "https://www.facebook.com/CentreSocialPlurielles/"}'
        ]);
        */

        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'play_loop' => false,
            'play_duration' => $duration,
            'content_type' => 'application/url',
            'content' => '{"url": "http://sanilabo.org", "css":"#theIframe{ position: absolute; top: -130px; left: 0; height: 660px; }"}'
        ]);

        $msg = \App\Models\Message::create([
            'channel_id' => $channel->id,
            'label' => 'msg#' . ($msgLabelIdx ++),
            'content_type' => 'application/service',
            'content' => '{"command": "resetChannel"}'
        ]);

    }
}
