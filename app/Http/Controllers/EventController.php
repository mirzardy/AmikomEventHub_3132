<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function show(\App\Models\Event $event)
    {
        $categories = \App\Models\Category::all();
        return view('event-detail', compact('categories', 'event'));
    }

    function checkout(){
        return view('checkout');
    }

    function ticket(){
        return view('ticket');
    }
}
