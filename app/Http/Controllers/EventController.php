<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    function show(Event $event){
        $event->load('category');

        return view('event-detail', compact('event'));
    }

    function checkout(){
        return view('checkout');
    }

    function ticket(){
        return view('ticket');
    }
}
