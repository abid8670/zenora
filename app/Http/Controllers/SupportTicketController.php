<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\SupportTicket;
use App\Models\SupportType;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function create()
    {
        $offices = Office::all();
        $supportTypes = SupportType::all();
        return view('support.create', compact('offices', 'supportTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'office_id' => 'required|exists:offices,id',
            'support_type_id' => 'required|exists:support_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $ticket = new SupportTicket();
        $ticket->name = $validatedData['full_name'];
        $ticket->email = $validatedData['email'];
        $ticket->office_id = $validatedData['office_id'];
        $ticket->support_type_id = $validatedData['support_type_id'];
        $ticket->title = $validatedData['title'];
        $ticket->description = $validatedData['description'];
        
        // Set status back to New as originally intended
        $ticket->status = 'New';
        $ticket->local_ip = $request->ip();
        $ticket->save();

        return redirect()->route('support-ticket.create')->with('success', 'Your support ticket has been submitted successfully! Ticket ID: ' . $ticket->id);
    }
}
