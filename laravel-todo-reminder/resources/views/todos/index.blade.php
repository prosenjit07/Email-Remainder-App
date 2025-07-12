<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Todo Reminder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-tasks me-2"></i>
                            Todo List Manager
                        </h3>
                        <a href="/email-logs" class="btn btn-light btn-sm">
                            <i class="fas fa-envelope me-1"></i>Email Logs
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Add Todo Form -->
                        <form id="todoForm" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" id="title" class="form-control" placeholder="Todo title" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="datetime-local" id="due_datetime" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-2"></i>Add Todo
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <textarea id="description" class="form-control" placeholder="Description (optional)" rows="2"></textarea>
                                </div>
                            </div>
                        </form>

                        <!-- Todo List -->
                        <div id="todoList">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Todo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date & Time</label>
                            <input type="datetime-local" id="edit_due_datetime" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveEdit">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let todos = [];
        let editingTodo = null;

        // Load todos
        async function loadTodos() {
            try {
                console.log('Loading todos...');
                const response = await fetch('/api/todos', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                todos = await response.json();
                console.log('Todos loaded:', todos);
                renderTodos();
            } catch (error) {
                console.error('Error loading todos:', error);
                document.getElementById('todoList').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading todos: ${error.message}
                    </div>
                `;
            }
        }

        // Render todos
        function renderTodos() {
            console.log('Rendering todos:', todos);
            const todoList = document.getElementById('todoList');
            
            if (!todoList) {
                console.error('Todo list element not found!');
                return;
            }
            
            if (todos.length === 0) {
                todoList.innerHTML = '<div class="text-center text-muted"><i class="fas fa-inbox fa-3x mb-3"></i><p>No todos yet. Add your first todo!</p></div>';
                return;
            }

            todoList.innerHTML = todos.map(todo => `
                <div class="card mb-3 ${todo.email_notification_sent ? 'border-success' : ''}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="card-title mb-1">${todo.title}</h5>
                                <p class="card-text text-muted mb-1">${todo.description || 'No description'}</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Due: ${new Date(todo.due_datetime).toLocaleString()}
                                    ${todo.email_notification_sent ? '<span class="badge bg-success ms-2"><i class="fas fa-envelope me-1"></i>Reminder Sent</span>' : ''}
                                </small>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-sm btn-outline-primary me-2" onclick="editTodo(${todo.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteTodo(${todo.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            console.log('Todos rendered successfully');
        }

        // Add todo
        document.getElementById('todoForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = {
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
                due_datetime: document.getElementById('due_datetime').value
            };

            try {
                const response = await fetch('/api/todos', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    document.getElementById('todoForm').reset();
                    await loadTodos();
                } else {
                    alert('Error creating todo');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error creating todo');
            }
        });

        // Edit todo
        function editTodo(id) {
            const todo = todos.find(t => t.id === id);
            if (!todo) return;

            editingTodo = todo;
            document.getElementById('edit_title').value = todo.title;
            document.getElementById('edit_description').value = todo.description || '';
            document.getElementById('edit_due_datetime').value = todo.due_datetime.slice(0, 16);

            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        // Save edit
        document.getElementById('saveEdit').addEventListener('click', async () => {
            if (!editingTodo) return;

            const formData = {
                title: document.getElementById('edit_title').value,
                description: document.getElementById('edit_description').value,
                due_datetime: document.getElementById('edit_due_datetime').value
            };

            try {
                const response = await fetch(`/api/todos/${editingTodo.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                    await loadTodos();
                } else {
                    alert('Error updating todo');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating todo');
            }
        });

        // Delete todo
        async function deleteTodo(id) {
            if (!confirm('Are you sure you want to delete this todo?')) return;

            try {
                const response = await fetch(`/api/todos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (response.ok) {
                    await loadTodos();
                } else {
                    alert('Error deleting todo');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting todo');
            }
        }

        // Load todos on page load
        loadTodos();
    </script>
</body>
</html> 