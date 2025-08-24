<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'type'           => 'required|in:booking,consultation',
            'emoji'          => 'required|in:sad,flat,happy',
            'anonymous'      => 'boolean',
            'comment'        => 'nullable|string|max:1000',
        ]);

        Feedback::create([
            'user_id'        => $request->boolean('anonymous') ? null : Auth::id(),
            'appointment_id' => $validated['appointment_id'] ?? null,
            'type'           => $validated['type'],
            'emoji'          => $validated['emoji'],
            'anonymous'      => $validated['anonymous'] ?? false,
            'comment'        => $validated['comment'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}