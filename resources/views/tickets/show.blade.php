@extends('layouts.app')

@section('title', 'Chamado #' . $ticket->id . ' - HelpDeskFlow')

@section('header')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Chamado #{{ $ticket->id }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Criado em {{ $ticket->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1-11v11m0 0l-4-4m4 4l4-4"></path>
                </svg>
                Editar
            </a>
            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este chamado?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Excluir
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    @php
        $statusClasses = [
            'open' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
            'waiting' => 'bg-orange-100 text-orange-800 border-orange-200',
            'resolved' => 'bg-green-100 text-green-800 border-green-200',
            'closed' => 'bg-gray-100 text-gray-800 border-gray-200',
            'aberto' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'em_andamento' => 'bg-blue-100 text-blue-800 border-blue-200',
            'aguardando' => 'bg-orange-100 text-orange-800 border-orange-200',
            'resolvido' => 'bg-green-100 text-green-800 border-green-200',
            'fechado' => 'bg-gray-100 text-gray-800 border-gray-200'
        ];
        $priorityColors = [
            'low' => 'text-green-600 bg-green-50 border-green-200',
            'medium' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
            'high' => 'text-orange-600 bg-orange-50 border-orange-200',
            'urgent' => 'text-red-600 bg-red-50 border-red-200',
            'baixa' => 'text-green-600 bg-green-50 border-green-200',
            'média' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
            'media' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
            'alta' => 'text-orange-600 bg-orange-50 border-orange-200',
            'urgente' => 'text-red-600 bg-red-50 border-red-200'
        ];
        $statusClass = $statusClasses[strtolower(str_replace(' ', '_', $ticket->status))] ?? 'bg-gray-100 text-gray-800';
        $priorityClass = $priorityColors[strtolower($ticket->priority)] ?? 'text-gray-600 bg-gray-50';
        $statusLabel = [
            'open' => 'Aberto',
            'in_progress' => 'Em Andamento',
            'waiting' => 'Aguardando',
            'resolved' => 'Resolvido',
            'closed' => 'Fechado'
        ][strtolower($ticket->status)] ?? $ticket->status;
        $priorityLabel = [
            'low' => 'Baixa',
            'medium' => 'Média',
            'high' => 'Alta',
            'urgent' => 'Urgente'
        ][strtolower($ticket->priority)] ?? $ticket->priority;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $priorityClass }}">
                            Prioridade: {{ $priorityLabel }}
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $ticket->title }}
                    </h1>

                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Comentários</h3>
                </div>
                <div class="p-6">
                    @forelse($ticket->comments()->with('user')->latest()->get() as $comment)
                        <div class="flex gap-4 mb-6">
                            <div class="shrink-0">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                        {{ substr($comment->user->name, 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">Nenhum comentário ainda</p>
                    @endforelse

                    <!-- Add Comment Form -->
                    <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST" class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        @csrf
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Adicionar comentário
                            </label>
                            <textarea id="content" name="content" rows="3" required
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Escreva seu comentário..."></textarea>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Informações</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Solicitante</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $ticket->requester->name ?? 'Não atribuído' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Atendente</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $ticket->assignee->name ?? 'Não atribuído' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Categoria</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $ticket->category->name ?? 'Não categorizado' }}</p>
                    </div>
                    @if($ticket->closed_at)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Resolvido em</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $ticket->closed_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('tickets.index') }}" class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para lista
            </a>
        </div>
    </div>
@endsection