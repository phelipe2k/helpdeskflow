@extends('layouts.app')

@section('title', $user->name . ' - HelpDeskFlow')

@section('header')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $user->name }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1-11v11m0 0l-4-4m4 4l4-4"></path>
                </svg>
                Editar
            </a>
        </div>
    </div>
@endsection

@section('content')
    @php
        $roleClasses = [
            'Administrator' => 'bg-red-100 text-red-800 border-red-200',
            'Atendente' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Solicitante' => 'bg-green-100 text-green-800 border-green-200'
        ];
        $roleLabels = [
            'Administrator' => 'Administrador',
            'Atendente' => 'Atendente',
            'Solicitante' => 'Solicitante'
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Informações do Usuário</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-20 h-20 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ substr($user->name, 0, 2) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $roleClasses[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $roleLabels[$user->role] ?? $user->role }}
                                </span>
                                @if($user->is_active)
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Ativo</span>
                                @else
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">Inativo</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Email</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Cadastrado em</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets by User -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Chamados do Usuário</h3>
                </div>
                <div class="p-6">
                    @php
                        $userTickets = \App\Models\Ticket::where('requester_id', $user->id)
                            ->orWhere('user_id', $user->id)
                            ->latest()
                            ->take(5)
                            ->get();
                    @endphp

                    @forelse($userTickets as $ticket)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div>
                                <a href="{{ route('tickets.show', $ticket) }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                    #{{ $ticket->id }} - {{ Str::limit($ticket->title, 40) }}
                                </a>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $ticket->status }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum chamado encontrado</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Reset Password -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Redefinir Senha</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('users.reset-password', $user) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nova Senha
                            </label>
                            <input type="password" id="password" name="password" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Nova senha">
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Redefinir Senha
                        </button>
                    </form>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('users.index') }}" class="flex items-center justify-center w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar para lista
            </a>
        </div>
    </div>
@endsection