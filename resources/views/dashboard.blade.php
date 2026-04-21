<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HelpDeskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">HelpDeskFlow</a>
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
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Open Tickets</h5>
                        <p class="card-text">{{ $totalOpen }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Resolved This Month</h5>
                        <p class="card-text">{{ $resolvedThisMonth }}</p>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="mt-4">Tickets by Status</h3>
        <ul class="list-group">
            @foreach($byStatus as $status => $count)
                <li class="list-group-item">{{ $status }}: {{ $count }}</li>
            @endforeach
        </ul>
        <h3 class="mt-4">Tickets by Priority</h3>
        <ul class="list-group">
            @foreach($byPriority as $priority => $count)
                <li class="list-group-item">{{ $priority }}: {{ $count }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>