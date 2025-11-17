<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'messageContent' => $validated['message'],
            ];

            Mail::send('emails.contact', $data, function($message) use ($data) {
                $message->to('covoit.2025@gmail.com')
                        ->subject('Nouveau message de contact: ' . $data['subject'])
                        ->replyTo($data['email'], $data['name']);
            });

            return redirect('/contact')->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            return redirect('/contact')->with('error', 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.');
        }
    }
}

