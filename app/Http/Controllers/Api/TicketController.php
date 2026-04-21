<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'requester', 'assignee']);

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

        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:baixa,média,alta,urgente',
            'category_id' => 'required|exists:categories,id',
        ]);

        $ticket =         Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open',
            'category_id' => $request->category_id,
            'requester_id' => Auth::id(),
        ]);

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['category', 'requester', 'assignee', 'comments.user', 'history.user']);
        return response()->json($ticket);
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
                'user_id' => Auth::id(),
                'action' => 'status_change',
                'old_value' => $oldStatus,
                'new_value' => $request->status,
            ]);
        }

        if ($oldAssignee != $request->assignee_id) {
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action' => 'assignment',
                'old_value' => $oldAssignee ? User::find($oldAssignee)->name : 'Unassigned',
                'new_value' => $request->assignee_id ? User::find($request->assignee_id)->name : 'Unassigned',
            ]);
        }

        if ($request->status == 'resolvido') {
            $ticket->update(['closed_at' => now()]);
        }

        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted']);
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:aberto,em atendimento,aguardando resposta,resolvido,cancelado',
        ]);

        $oldStatus = $ticket->status;
        $ticket->update(['status' => $request->status]);

        if ($oldStatus != $request->status) {
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action' => 'status_change',
                'old_value' => $oldStatus,
                'new_value' => $request->status,
            ]);
        }

        if ($request->status == 'resolvido') {
            $ticket->update(['closed_at' => now()]);
        }

        return response()->json($ticket);
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $oldAssignee = $ticket->assignee_id;
        $ticket->update(['assignee_id' => $request->assignee_id]);

        if ($oldAssignee != $request->assignee_id) {
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action' => 'assignment',
                'old_value' => $oldAssignee ? User::find($oldAssignee)->name : 'Unassigned',
                'new_value' => $request->assignee_id ? User::find($request->assignee_id)->name : 'Unassigned',
            ]);
        }

        return response()->json($ticket);
    }

    public function ticketsSummary()
    {
        $total = Ticket::count();
        $open = Ticket::where('status', '!=', 'resolvido')->count();
        $resolved = Ticket::where('status', 'resolvido')->count();
        return response()->json([
            'total' => $total,
            'open' => $open,
            'resolved' => $resolved,
        ]);
    }

    public function ticketsByStatus()
    {
        $data = Ticket::selectRaw('status, count(*) as count')->groupBy('status')->get();
        return response()->json($data);
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return response()->json($comment, 201);
    }
}