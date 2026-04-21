<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
        // Aplica autorização para todos os métodos
        $this->authorizeResource(Ticket::class, 'ticket');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $filters = [
            'search' => $request->search,
            'status' => $request->status,
            'priority' => $request->priority,
        ];

        $tickets = $this->ticketService->list(
            $filters,
            $user->role,
            $user->id
        );

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:5',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $this->ticketService->create($validated);

        return redirect()->route('tickets.index')->with('success', 'Chamado criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:5',
            'status' => 'nullable|in:open,in_progress,waiting,resolved,closed',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $this->ticketService->update($ticket, $validated);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Chamado atualizado com sucesso!');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        $this->ticketService->delete($ticket);
        return redirect()->route('tickets.index')->with('success', 'Chamado excluído com sucesso!');
    }
}