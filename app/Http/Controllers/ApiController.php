<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rent;
use App\Models\RentPrice;

/**
 *
 * @author cyrille
 */
class ApiController extends Controller
{

    public function stats()
    {
        $stats = array(
            'messagesCount' => Message::all()->count(),
            'channelsCount' => Channel::all()->count()
        );
        return response()->json($stats);
    }
}
