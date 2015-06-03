<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input ;
use App\Models\Channel;
use App\Models\Message;

class MessageController extends BaseController
{
    public function edit($id)
    {
        $message = Message::findOrFail($id);
        return view('messageEdit', ['message'=>$message]);
    }
    
}
