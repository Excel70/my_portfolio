<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit() { return view('admin.settings', ['settings' => Setting::pluck('value', 'key')->all()]); }
    public function update(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100'], 'title' => ['required', 'string', 'max:150'], 'about' => ['nullable', 'string', 'max:5000'], 'email' => ['nullable', 'email', 'max:255'], 'github_url' => ['nullable', 'url', 'max:2048'], 'linkedin_url' => ['nullable', 'url', 'max:2048'], 'whatsapp_url' => ['nullable', 'url', 'max:2048']]);
        foreach ($data as $key => $value) { Setting::updateOrCreate(['key' => $key], ['value' => $value]); }
        return back()->with('success', 'Profile and settings updated successfully.');
    }
    public function messages() { return view('admin.messages', ['messages' => Message::latest()->paginate(15)]); }
    public function updateMessage(Request $request, Message $message) { $data = $request->validate(['status' => ['required', 'in:unread,read,replied,archived']]); if ($data['status'] !== 'unread' && ! $message->read_at) { $data['read_at'] = now(); } $message->update($data); return back()->with('success', 'Message status updated.'); }
    public function destroyMessage(Message $message) { $message->delete(); return back()->with('success', 'Message deleted successfully.'); }
}
