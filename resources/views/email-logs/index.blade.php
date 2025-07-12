<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Logs - Todo Reminder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            Email Logs
                        </h3>
                        <a href="/todos" class="btn btn-light btn-sm">
                            <i class="fas fa-tasks me-1"></i>Back to Todos
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Stats Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $emailLogs->where('status', 'success')->count() }}</h4>
                                        <small>Successful Emails</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $emailLogs->where('status', 'failed')->count() }}</h4>
                                        <small>Failed Emails</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $emailLogs->count() }}</h4>
                                        <small>Total Emails</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $emailLogs->where('sent_at', '>=', now()->subDay())->count() }}</h4>
                                        <small>Last 24 Hours</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email Logs Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Recipient</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Sent At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emailLogs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ $log->to_email }}
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $log->subject }}">
                                                {{ $log->subject }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($log->status === 'success')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Success
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $log->sent_at->format('M d, Y H:i:s') }}
                                            </small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewEmailDetails({{ $log->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                            <p>No email logs found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($emailLogs->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $emailLogs->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Details Modal -->
    <div class="modal fade" id="emailDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Email Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="emailDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function viewEmailDetails(id) {
            try {
                const response = await fetch(`/api/email-logs/${id}`);
                const emailLog = await response.json();
                
                const content = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Recipient:</strong><br>
                            <span class="text-muted">${emailLog.to_email}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            <span class="badge ${emailLog.status === 'success' ? 'bg-success' : 'bg-danger'}">
                                ${emailLog.status === 'success' ? '<i class="fas fa-check me-1"></i>Success' : '<i class="fas fa-times me-1"></i>Failed'}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <strong>Subject:</strong><br>
                            <span class="text-muted">${emailLog.subject}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <strong>Content:</strong><br>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0">${emailLog.body || 'No content available'}</pre>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <strong>Sent At:</strong><br>
                            <span class="text-muted">${new Date(emailLog.sent_at).toLocaleString()}</span>
                        </div>
                    </div>
                `;
                
                document.getElementById('emailDetailsContent').innerHTML = content;
                new bootstrap.Modal(document.getElementById('emailDetailsModal')).show();
            } catch (error) {
                console.error('Error loading email details:', error);
                alert('Error loading email details');
            }
        }
    </script>
</body>
</html> 