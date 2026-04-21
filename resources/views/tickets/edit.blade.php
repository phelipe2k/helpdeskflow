<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ticket - HelpDeskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">HelpDeskFlow</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('tickets.index') }}">Tickets</a>
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
        <h1>Edit Ticket</h1>
        <form method="POST" action="{{ route('tickets.update', $ticket) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $ticket->title }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required>{{ $ticket->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-control" id="priority" name="priority" required>
                    <option value="baixa" {{ $ticket->priority == 'baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="média" {{ $ticket->priority == 'média' ? 'selected' : '' }}>Média</option>
                    <option value="alta" {{ $ticket->priority == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="urgente" {{ $ticket->priority == 'urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="aberto" {{ $ticket->status == 'aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="em atendimento" {{ $ticket->status == 'em atendimento' ? 'selected' : '' }}>Em Atendimento</option>
                    <option value="aguardando resposta" {{ $ticket->status == 'aguardando resposta' ? 'selected' : '' }}>Aguardando Resposta</option>
                    <option value="resolvido" {{ $ticket->status == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                    <option value="cancelado" {{ $ticket->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $ticket->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="assignee_id" class="form-label">Assignee</label>
                <select class="form-control" id="assignee_id" name="assignee_id">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $ticket->assignee_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>