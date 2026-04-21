<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketService
{
    /**
     * Criar um novo ticket
     */
    public function create(array $data): Ticket
    {
        $user = Auth::user();

        $ticket = Ticket::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => 'open',
            'priority' => $data['priority'] ?? 'medium',
            'requester_id' => $user->id,
            'user_id' => $user->id,
            'category_id' => $data['category_id'] ?? null,
        ]);

        // Registrar histórico
        $this->logHistory($ticket, 'created', null, 'open', 'Ticket criado');

        return $ticket;
    }

    /**
     * Atualizar ticket
     */
    public function update(Ticket $ticket, array $data): Ticket
    {
        $oldStatus = $ticket->status;
        $user = Auth::user();

        // Determinar quais campos podem ser atualizados baseado na role
        $allowedFields = ['title', 'description'];

        if (in_array($user->role, ['Administrator', 'Atendente'])) {
            $allowedFields[] = 'priority';
            $allowedFields[] = 'status';
            $allowedFields[] = 'assignee_id';
        } elseif ($user->role === 'Solicitante') {
            // Solicitante só pode mudar status para resolved
            if (isset($data['status']) && in_array($data['status'], ['resolved', $oldStatus])) {
                $allowedFields[] = 'status';
            }
        }

        $updateData = array_intersect_key($data, array_flip($allowedFields));
        $ticket->update($updateData);

        // Atualizar closed_at se necessário
        if (isset($data['status'])) {
            if (in_array($data['status'], ['resolved', 'closed']) && !in_array($oldStatus, ['resolved', 'closed'])) {
                $ticket->update(['closed_at' => now()]);
            } elseif (!in_array($data['status'], ['resolved', 'closed']) && in_array($oldStatus, ['resolved', 'closed'])) {
                $ticket->update(['closed_at' => null]);
            }
        }

        // Registrar histórico de mudança de status
        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            $this->logHistory($ticket, 'status_changed', $oldStatus, $data['status'], 'Status alterado');
        }

        return $ticket->fresh();
    }

    /**
     * Listar tickets com filtros
     */
    public function list(array $filters = [], ?string $role = null, ?int $userId = null): LengthAwarePaginator
    {
        $query = Ticket::with(['requester', 'assignee', 'category'])->latest();

        // Filtro por role
        if ($role === 'Solicitante' && $userId) {
            $query->where(function ($q) use ($userId) {
                $q->where('requester_id', $userId)
                  ->orWhere('user_id', $userId);
            });
        }

        // Filtros adicionais
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Excluir ticket
     */
    public function delete(Ticket $ticket): bool
    {
        $this->logHistory($ticket, 'deleted', $ticket->status, null, 'Ticket excluído');
        return $ticket->delete();
    }

    /**
     * Atribuir ticket a um atendente
     */
    public function assign(Ticket $ticket, int $assigneeId): Ticket
    {
        $oldAssignee = $ticket->assignee_id;

        $ticket->update([
            'assignee_id' => $assigneeId,
            'status' => $ticket->status === 'open' ? 'in_progress' : $ticket->status,
        ]);

        $this->logHistory($ticket, 'assigned', $oldAssignee, $assigneeId, "Ticket atribuído ao atendente #{$assigneeId}");

        return $ticket->fresh();
    }

    /**
     * Obter estatísticas para dashboard
     */
    public function getDashboardStats(?string $role = null, ?int $userId = null): array
    {
        $query = Ticket::query();

        if ($role === 'Solicitante' && $userId) {
            $query->where(function ($q) use ($userId) {
                $q->where('requester_id', $userId)
                  ->orWhere('user_id', $userId);
            });
        }

        $totalOpen = (clone $query)->whereNotIn('status', ['resolved', 'closed'])->count();
        $byStatus = (clone $query)->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        $byPriority = (clone $query)->selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority');
        $resolvedThisMonth = (clone $query)
            ->whereIn('status', ['resolved', 'closed'])
            ->whereMonth('closed_at', now()->month)
            ->count();

        return [
            'totalOpen' => $totalOpen,
            'byStatus' => $byStatus,
            'byPriority' => $byPriority,
            'resolvedThisMonth' => $resolvedThisMonth,
            'total' => $query->count(),
            'newToday' => $query->whereDate('created_at', today())->count(),
        ];
    }

    /**
     * Registrar histórico de alterações
     */
    private function logHistory(Ticket $ticket, string $action, $oldValue, $newValue, string $description): void
    {
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'description' => $description,
        ]);
    }
}
