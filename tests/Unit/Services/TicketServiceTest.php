<?php

namespace Tests\Unit\Services;

use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TicketService $ticketService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ticketService = new TicketService();
    }

    /**
     * Teste: Criar ticket associa ao usuário logado
     */
    public function test_create_ticket_assigns_to_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ticket = $this->ticketService->create([
            'title' => 'Test Ticket',
            'description' => 'Test Description',
            'priority' => 'high',
        ]);

        $this->assertEquals($user->id, $ticket->requester_id);
        $this->assertEquals($user->id, $ticket->user_id);
        $this->assertEquals('open', $ticket->status);
        $this->assertEquals('high', $ticket->priority);
    }

    /**
     * Teste: Atualizar ticket registra histórico
     */
    public function test_update_ticket_logs_history(): void
    {
        $user = User::factory()->create(['role' => 'Atendente']);
        $this->actingAs($user);

        $ticket = Ticket::factory()->create(['status' => 'open']);

        $this->ticketService->update($ticket, [
            'title' => 'Updated Title',
            'description' => $ticket->description,
            'status' => 'in_progress',
        ]);

        $this->assertDatabaseHas('ticket_histories', [
            'ticket_id' => $ticket->id,
            'action' => 'status_changed',
            'old_value' => 'open',
            'new_value' => 'in_progress',
        ]);
    }

    /**
     * Teste: Listar tickets filtra por role
     */
    public function test_list_filters_by_role(): void
    {
        $solicitante = User::factory()->create(['role' => 'Solicitante']);
        $atendente = User::factory()->create(['role' => 'Atendente']);

        Ticket::factory()->create([
            'requester_id' => $solicitante->id,
            'title' => 'Solicitante Ticket',
        ]);

        Ticket::factory()->create([
            'requester_id' => $atendente->id,
            'title' => 'Atendente Ticket',
        ]);

        $this->actingAs($solicitante);

        $result = $this->ticketService->list([], 'Solicitante', $solicitante->id);

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Solicitante Ticket', $result->items()[0]->title);
    }

    /**
     * Teste: Dashboard stats retorna dados corretos
     */
    public function test_get_dashboard_stats_returns_correct_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Ticket::factory()->count(5)->create(['status' => 'open']);
        Ticket::factory()->count(3)->create(['status' => 'resolved', 'closed_at' => now()]);

        $stats = $this->ticketService->getDashboardStats('Administrator', $user->id);

        $this->assertEquals(8, $stats['total']);
        $this->assertEquals(5, $stats['totalOpen']);
        $this->assertEquals(3, $stats['resolvedThisMonth']);
    }

    /**
     * Teste: Atribuir ticket atualiza status
     */
    public function test_assign_ticket_updates_status(): void
    {
        $user = User::factory()->create();
        $atendente = User::factory()->create();
        $this->actingAs($user);

        $ticket = Ticket::factory()->create([
            'status' => 'open',
            'assignee_id' => null,
        ]);

        $result = $this->ticketService->assign($ticket, $atendente->id);

        $this->assertEquals($atendente->id, $result->assignee_id);
        $this->assertEquals('in_progress', $result->status);
    }
}
