<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['baixa', 'média', 'alta', 'urgente']);
            $table->enum('status', ['aberto', 'em atendimento', 'aguardando resposta', 'resolvido', 'cancelado']);
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('requester_id')->constrained('users');
            $table->foreignId('assignee_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->timestamp('closed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};