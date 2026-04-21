<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets - HelpDeskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">HelpDeskFlow</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('tickets.index') }}">Tickets</a>
                @if(auth()->user()->role == 'Administrator')
                    <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                @endif
            </div>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1>Tickets</h1>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Create Ticket</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filters -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="em atendimento" {{ request('status') == 'em atendimento' ? 'selected' : '' }}>Em Atendimento</option>
                        <option value="aguardando resposta" {{ request('status') == 'aguardando resposta' ? 'selected' : '' }}>Aguardando Resposta</option>
                        <option value="resolvido" {{ request('status') == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="priority" class="form-control">
                        <option value="">All Priorities</option>
                        <option value="baixa" {{ request('priority') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                        <option value="média" {{ request('priority') == 'média' ? 'selected' : '' }}>Média</option>
                        <option value="alta" {{ request('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="urgente" {{ request('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Requester</th>
                    <th>Assignee</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->status }}</td>
                        <td>{{ $ticket->priority }}</td>
                        <td>{{ $ticket->requester->name }}</td>
                        <td>{{ $ticket->assignee ? $ticket->assignee->name : 'Unassigned' }}</td>
                        <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tickets->links() }}
    </div>
</body>
</html>