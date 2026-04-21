<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Se houver seeders
    }

    /**
     * Teste: Usuário não autenticado não pode acessar tickets
     */
    public function test_unauthenticated_user_cannot_access_tickets(): void
    {
        $response = $this->get('/tickets');
        $response->assertRedirect('/login');
    }

    /**
     * Teste: Administrador pode ver todos os tickets
     */
    public function test_admin_can_view_all_tickets(): void
    {
        $admin = User::factory()->create(['role' => 'Administrator']);
        Ticket::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get('/tickets');

        $response->assertStatus(200);
        $response->assertViewHas('tickets');
    }

    /**
     * Teste: Solicitante só vê seus próprios tickets
     */
    public function test_solicitante_only_sees_own_tickets(): void
    {
        $solicitante = User::factory()->create(['role' => 'Solicitante']);
        $otherUser = User::factory()->create(['role' => 'Solicitante']);

        $ownTicket = Ticket::factory()->create([
            'requester_id' => $solicitante->id,
            'user_id' => $solicitante->id,
        ]);

        $otherTicket = Ticket::factory()->create([
            'requester_id' => $otherUser->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($solicitante)
            ->get('/tickets');

        $response->assertStatus(200);
        $response->assertSee($ownTicket->title);
        $response->assertDontSee($otherTicket->title);
    }

    /**
     * Teste: Usuário pode criar ticket
     */
    public function test_user_can_create_ticket(): void
    {
        $user = User::factory()->create(['role' => 'Solicitante']);

        $response = $this->actingAs($user)
            ->post('/tickets', [
                'title' => 'Novo Chamado Teste',
                'description' => 'Descrição do chamado de teste',
                'priority' => 'high',
            ]);

        $response->assertRedirect('/tickets');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tickets', [
            'title' => 'Novo Chamado Teste',
            'requester_id' => $user->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Teste: Validação ao criar ticket
     */
    public function test_ticket_creation_requires_title_and_description(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/tickets', [
                'title' => '',
                'description' => '',
            ]);

        $response->assertSessionHasErrors(['title', 'description']);
    }

    /**
     * Teste: Administrador pode atualizar qualquer ticket
     */
    public function test_admin_can_update_any_ticket(): void
    {
        $admin = User::factory()->create(['role' => 'Administrator']);
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($admin)
            ->put("/tickets/{$ticket->id}", [
                'title' => 'Título Atualizado',
                'description' => $ticket->description,
                'status' => 'in_progress',
                'priority' => 'high',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'title' => 'Título Atualizado',
            'status' => 'in_progress',
        ]);
    }

    /**
     * Teste: Solicitante não pode atualizar ticket de outro usuário
     */
    public function test_solicitante_cannot_update_others_ticket(): void
    {
        $solicitante = User::factory()->create(['role' => 'Solicitante']);
        $otherUser = User::factory()->create();

        $ticket = Ticket::factory()->create([
            'requester_id' => $otherUser->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($solicitante)
            ->put("/tickets/{$ticket->id}", [
                'title' => 'Tentativa de Atualização',
                'description' => 'Descrição',
            ]);

        $response->assertForbidden();
    }

    /**
     * Teste: Apenas admin pode deletar ticket
     */
    public function test_only_admin_can_delete_ticket(): void
    {
        $admin = User::factory()->create(['role' => 'Administrator']);
        $solicitante = User::factory()->create(['role' => 'Solicitante']);
        $ticket = Ticket::factory()->create();

        // Solicitante tenta deletar
        $response = $this->actingAs($solicitante)
            ->delete("/tickets/{$ticket->id}");
        $response->assertForbidden();

        // Admin deleta
        $response = $this->actingAs($admin)
            ->delete("/tickets/{$ticket->id}");
        $response->assertRedirect('/tickets');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    /**
     * Teste: Filtros de busca funcionam
     */
    public function test_ticket_search_filter_works(): void
    {
        $user = User::factory()->create(['role' => 'Administrator']);
        Ticket::factory()->create(['title' => 'Ticket de Rede']);
        Ticket::factory()->create(['title' => 'Ticket de Sistema']);

        $response = $this->actingAs($user)
            ->get('/tickets?search=Rede');

        $response->assertStatus(200);
        $response->assertSee('Ticket de Rede');
        $response->assertDontSee('Ticket de Sistema');
    }
}
