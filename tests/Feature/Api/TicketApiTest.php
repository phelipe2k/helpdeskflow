<?php

namespace Tests\Feature\Api;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste: API requer autenticação
     */
    public function test_api_requires_authentication(): void
    {
        $response = $this->getJson('/api/tickets');
        $response->assertUnauthorized();
    }

    /**
     * Teste: Listar tickets via API
     */
    public function test_can_list_tickets_via_api(): void
    {
        $user = User::factory()->create();
        Ticket::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/tickets');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'status', 'priority']
                ]
            ]);
    }

    /**
     * Teste: Criar ticket via API
     */
    public function test_can_create_ticket_via_api(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/tickets', [
                'title' => 'API Ticket Test',
                'description' => 'Testing API creation',
                'priority' => 'high',
                'category_id' => 1,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'API Ticket Test',
                'status' => 'aberto',
            ]);
    }

    /**
     * Teste: Ver ticket específico via API
     */
    public function test_can_show_ticket_via_api(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($user)
            ->getJson("/api/tickets/{$ticket->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $ticket->id,
                'title' => $ticket->title,
            ]);
    }

    /**
     * Teste: Atualizar status via API
     */
    public function test_can_update_ticket_status_via_api(): void
    {
        $user = User::factory()->create(['role' => 'Atendente']);
        $ticket = Ticket::factory()->create(['status' => 'aberto']);

        $response = $this->actingAs($user)
            ->patchJson("/api/tickets/{$ticket->id}/status", [
                'status' => 'resolvido',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'resolvido',
        ]);
    }

    /**
     * Teste: Relatório de resumo
     */
    public function test_can_get_tickets_summary(): void
    {
        $user = User::factory()->create(['role' => 'Administrator']);
        Ticket::factory()->count(5)->create();
        Ticket::factory()->count(3)->create(['status' => 'resolvido']);

        $response = $this->actingAs($user)
            ->getJson('/api/reports/tickets-summary');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total',
                'open',
                'resolved',
            ]);
    }
}
