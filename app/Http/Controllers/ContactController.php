<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact', ['settings' => Setting::pluck('value', 'key')->all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email:rfc,dns', 'max:255'],
            'subject' => ['required', 'string', 'max:180'], 'message' => ['required', 'string', 'max:5000'],
        ]);
        Message::create($data);
        return back()->with('success', 'Thank you — your message has been received.');
    }
}
