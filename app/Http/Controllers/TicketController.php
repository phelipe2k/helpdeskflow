<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\TicketHistory;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'requester', 'assignee']);

        // Permissions: Solicitante only sees own tickets
        if (auth()->user()->role == 'Solicitante') {
            $query->where('requester_id', auth()->id());
        }

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->priority) {
            $query->where('priority', $request->priority);
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $tickets = $query->paginate(10);

        $categories = Category::all();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:baixa,média,alta,urgente',
            'category_id' => 'required|exists:categories,id',
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'aberto',
            'category_id' => $request->category_id,
            'requester_id' => auth()->id(),
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['category', 'requester', 'assignee', 'comments.user', 'history.user']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $categories = Category::all();
        $users = \App\Models\User::where('role', 'Atendente')->get();
        return view('tickets.edit', compact('ticket', 'categories', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:baixa,média,alta,urgente',
            'status' => 'required|in:aberto,em atendimento,aguardando resposta,resolvido,cancelado',
            'category_id' => 'required|exists:categories,id',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $oldStatus = $ticket->status;
        $oldAssignee = $ticket->assignee_id;
        $ticket->update($request->only(['title', 'description', 'priority', 'status', 'category_id', 'assignee_id']));

        if ($oldStatus != $request->status) {
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'status_change',
                'old_value' => $oldStatus,
                'new_value' => $request->status,
            ]);
        }

        if ($oldAssignee != $request->assignee_id) {
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'assignment',
                'old_value' => $oldAssignee ? User::find($oldAssignee)->name : 'Unassigned',
                'new_value' => $request->assignee_id ? User::find($request->assignee_id)->name : 'Unassigned',
            ]);
        }

        if ($request->status == 'resolvido') {
            $ticket->update(['closed_at' => now()]);
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $ticket->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Comment added successfully.');
    }
}