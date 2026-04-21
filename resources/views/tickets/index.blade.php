@extends('layouts.app')

@section('title', 'Chamados - HelpDeskFlow')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Chamados
        </h2>
        <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Novo Chamado
        </a>
    </div>
@endsection

@section('content')
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-6" x-data="{ open: false }">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <div class="relative">
                    <input type="text" name="search" placeholder="Buscar por título ou ID..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="sm:w-40">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    <option value="open">Aberto</option>
                    <option value="in_progress">Em Andamento</option>
                    <option value="waiting">Aguardando</option>
                    <option value="resolved">Resolvido</option>
                    <option value="closed">Fechado</option>
                </select>
            </div>
            <div class="sm:w-40">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridade</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Todas</option>
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
                    <option value="urgent">Urgente</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" class="w-full sm:w-auto px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Tickets Grid (Mobile) -->
    <div class="grid grid-cols-1 md:hidden gap-4">
        @forelse($tickets as $ticket)
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
                    'low' => 'text-green-600',
                    'medium' => 'text-yellow-600',
                    'high' => 'text-orange-600',
                    'urgent' => 'text-red-600',
                    'baixa' => 'text-green-600',
                    'média' => 'text-yellow-600',
                    'media' => 'text-yellow-600',
                    'alta' => 'text-orange-600',
                    'urgente' => 'text-red-600'
                ];
                $statusClass = $statusClasses[strtolower(str_replace(' ', '_', $ticket->status))] ?? 'bg-gray-100 text-gray-800';
                $priorityColor = $priorityColors[strtolower($ticket->priority)] ?? 'text-gray-600';
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">#{{ $ticket->id }}</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full border {{ $statusClass }}">
                        {{ $ticket->status }}
                    </span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                    <a href="{{ route('tickets.show', $ticket) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                        {{ Str::limit($ticket->title, 60) }}
                    </a>
                </h3>
                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <span class="flex items-center {{ $priorityColor }}">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        {{ $ticket->priority }}
                    </span>
                    <span>{{ $ticket->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('tickets.show', $ticket) }}" class="flex-1 text-center px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-sm font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                        Ver
                    </a>
                    <a href="{{ route('tickets.edit', $ticket) }}" class="flex-1 text-center px-3 py-2 bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        Editar
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Nenhum chamado encontrado</p>
            </div>
        @endforelse
    </div>

    <!-- Tickets Table (Desktop) -->
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prioridade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Criado em</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                        @php
                            $statusClass = $statusClasses[strtolower(str_replace(' ', '_', $ticket->status))] ?? 'bg-gray-100 text-gray-800';
                            $priorityColor = $priorityColors[strtolower($ticket->priority)] ?? 'text-gray-600';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                #{{ $ticket->id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ Str::limit($ticket->title, 60) }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($ticket->description, 80) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusClass }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm {{ $priorityColor }}">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $ticket->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" title="Ver">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1-11v11m0 0l-4-4m4 4l4-4"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este chamado?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" title="Excluir">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg">Nenhum chamado encontrado</p>
                                <a href="{{ route('tickets.create') }}" class="mt-2 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Criar primeiro chamado →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($tickets->hasPages())
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    @endif
@endsection