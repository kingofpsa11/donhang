<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

class NotificationController extends Controller
{
    public function index(){
        $notifications = auth()->user()->notifications;

        return view('notification.index', compact('notifications'));
    }
    public function sendNotification(Request $request)
    {

        $data['title'] = $request->input('title');
        $data['content'] = $request->input('content');

        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );

        $pusher = new Pusher(
            'fcd7e1e10df31aac8de8',
            '7a5c9943489736fc1feb',
            '774101',
            $options
        );

        $pusher->trigger('Notify', 'send-message', $data);

        return redirect()->route('send');
    }
}
