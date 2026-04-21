@extends('layouts.app')

@section('title', 'Dashboard - HelpDeskFlow')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard
    </h2>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Open Tickets -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-orange-100 dark:bg-orange-900">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Chamados Abertos</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOpen }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                    Ver todos →
                </a>
            </div>
        </div>

        <!-- Resolved This Month -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Resolvidos (Mês)</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $resolvedThisMonth }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                <span class="text-sm text-green-600 dark:text-green-400">
                    Este mês
                </span>
            </div>
        </div>

        <!-- Total Tickets -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Chamados</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Ticket::count() }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Desde o início
                </span>
            </div>
        </div>

        <!-- New Tickets Today -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Novos Hoje</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Ticket::whereDate('created_at', today())->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                <span class="text-sm text-purple-600 dark:text-purple-400">
                    Hoje
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tickets by Status -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Chamados por Status</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $statusColors = [
                            'open' => 'bg-yellow-500',
                            'in_progress' => 'bg-blue-500',
                            'waiting' => 'bg-orange-500',
                            'resolved' => 'bg-green-500',
                            'closed' => 'bg-gray-500',
                            'aberto' => 'bg-yellow-500',
                            'em_andamento' => 'bg-blue-500',
                            'aguardando' => 'bg-orange-500',
                            'resolvido' => 'bg-green-500',
                            'fechado' => 'bg-gray-500'
                        ];
                        $statusLabels = [
                            'open' => 'Aberto',
                            'in_progress' => 'Em Andamento',
                            'waiting' => 'Aguardando',
                            'resolved' => 'Resolvido',
                            'closed' => 'Fechado',
                            'aberto' => 'Aberto',
                            'em_andamento' => 'Em Andamento',
                            'aguardando' => 'Aguardando',
                            'resolvido' => 'Resolvido',
                            'fechado' => 'Fechado'
                        ];
                        $total = $byStatus->sum();
                    @endphp

                    @forelse($byStatus as $status => $count)
                        @php
                            $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                            $color = $statusColors[strtolower(str_replace(' ', '_', $status))] ?? 'bg-gray-500';
                            $label = $statusLabels[strtolower(str_replace(' ', '_', $status))] ?? $status;
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum chamado registrado</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tickets by Priority -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Chamados por Prioridade</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $priorityColors = [
                            'low' => 'bg-green-500',
                            'medium' => 'bg-yellow-500',
                            'high' => 'bg-orange-500',
                            'urgent' => 'bg-red-500',
                            'baixa' => 'bg-green-500',
                            'media' => 'bg-yellow-500',
                            'média' => 'bg-yellow-500',
                            'alta' => 'bg-orange-500',
                            'urgente' => 'bg-red-500'
                        ];
                        $priorityLabels = [
                            'low' => 'Baixa',
                            'medium' => 'Média',
                            'high' => 'Alta',
                            'urgent' => 'Urgente',
                            'baixa' => 'Baixa',
                            'media' => 'Média',
                            'média' => 'Média',
                            'alta' => 'Alta',
                            'urgente' => 'Urgente'
                        ];
                        $totalPriority = $byPriority->sum();
                    @endphp

                    @forelse($byPriority as $priority => $count)
                        @php
                            $percentage = $totalPriority > 0 ? round(($count / $totalPriority) * 100) : 0;
                            $color = $priorityColors[strtolower($priority)] ?? 'bg-gray-500';
                            $label = $priorityLabels[strtolower($priority)] ?? $priority;
                        @endphp
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum chamado registrado</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tickets -->
    <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Chamados Recentes</h3>
            <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">
                Ver todos
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prioridade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse(\App\Models\Ticket::latest()->take(5)->get() as $ticket)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">#{{ $ticket->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ Str::limit($ticket->title, 50) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'open' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'resolved' => 'bg-green-100 text-green-800',
                                        'closed' => 'bg-gray-100 text-gray-800',
                                        'aberto' => 'bg-yellow-100 text-yellow-800',
                                        'em_andamento' => 'bg-blue-100 text-blue-800',
                                        'resolvido' => 'bg-green-100 text-green-800',
                                        'fechado' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $class = $statusClasses[strtolower(str_replace(' ', '_', $ticket->status))] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $ticket->priority }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $ticket->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Nenhum chamado registrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection