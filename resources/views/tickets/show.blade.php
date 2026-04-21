<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details - HelpDeskFlow</title>
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
        <h1>Ticket #{{ $ticket->id }}</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $ticket->title }}</h5>
                <p class="card-text">{{ $ticket->description }}</p>
                <p><strong>Status:</strong> {{ $ticket->status }}</p>
                <p><strong>Priority:</strong> {{ $ticket->priority }}</p>
                <p><strong>Category:</strong> {{ $ticket->category->name }}</p>
                <p><strong>Requester:</strong> {{ $ticket->requester->name }}</p>
                <p><strong>Assignee:</strong> {{ $ticket->assignee ? $ticket->assignee->name : 'Unassigned' }}</p>
                <p><strong>Created:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                @if($ticket->closed_at)
                    <p><strong>Closed:</strong> {{ $ticket->closed_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>

        <h3 class="mt-4">History</h3>
        @foreach($ticket->history as $history)
            <div class="card mt-2">
                <div class="card-body">
                    <p>{{ $history->action }}: {{ $history->old_value }} -> {{ $history->new_value }}</p>
                    <small>By {{ $history->user->name }} on {{ $history->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        @endforeach

        <h3 class="mt-4">Comments</h3>
        @foreach($ticket->comments as $comment)
            <div class="card mt-2">
                <div class="card-body">
                    <p>{{ $comment->content }}</p>
                    <small>By {{ $comment->user->name }} on {{ $comment->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        @endforeach

        <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}" class="mt-3">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Add Comment</label>
                <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>

        <a href="{{ route('tickets.index') }}" class="btn btn-secondary mt-3">Back</a>
        <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning mt-3">Edit</a>
    </div>
</body>
</html>