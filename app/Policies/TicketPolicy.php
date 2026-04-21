<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine whether the user can view any tickets.
     */
    public function viewAny(User $user): bool
    {
        // Todos os usuários autenticados podem ver tickets
        return true;
    }

    /**
     * Determine whether the user can view the ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Admin e Atendentes veem todos
        if (in_array($user->role, ['Administrator', 'Atendente'])) {
            return true;
        }

        // Solicitantes só veem seus próprios
        return $ticket->requester_id === $user->id || $ticket->user_id === $user->id;
    }

    /**
     * Determine whether the user can create tickets.
     */
    public function create(User $user): bool
    {
        // Todos podem criar tickets
        return true;
    }

    /**
     * Determine whether the user can update the ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Admin e Atendentes podem editar todos
        if (in_array($user->role, ['Administrator', 'Atendente'])) {
            return true;
        }

        // Solicitantes só podem editar seus próprios e apenas se estiver aberto
        if ($ticket->requester_id === $user->id || $ticket->user_id === $user->id) {
            return in_array($ticket->status, ['open', 'aberto']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Apenas Admin pode deletar
        return $user->role === 'Administrator';
    }

    /**
     * Determine whether the user can assign the ticket.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        // Apenas Admin e Atendentes podem atribuir
        return in_array($user->role, ['Administrator', 'Atendente']);
    }

    /**
     * Determine whether the user can change status.
     */
    public function changeStatus(User $user, Ticket $ticket): bool
    {
        // Admin e Atendentes podem mudar status
        if (in_array($user->role, ['Administrator', 'Atendente'])) {
            return true;
        }

        // Solicitantes podem apenas marcar como resolvido nos próprios tickets
        if (($ticket->requester_id === $user->id || $ticket->user_id === $user->id) && $ticket->status !== 'closed') {
            return true;
        }

        return false;
    }
}
