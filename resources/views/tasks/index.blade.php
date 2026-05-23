@extends('layouts.app')

@section('content')
<div class="main-wrapper">
    <div class="tasks-header">
        <h2>My Tasks</h2>
        <a href="{{ route('tasks.create') }}" class="btn-add">+ Add New Task</a>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}" class="filter-form">
        <select name="category" class="filter-select">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <select name="sort" class="filter-select">
            <option value="due_date" {{ $sort === 'due_date' ? 'selected' : '' }}>Sort by Due Date</option>
            <option value="title" {{ $sort === 'title' ? 'selected' : '' }}>Sort by Title</option>
        </select>

        <button type="submit" class="btn-filter">Apply</button>
        <a href="{{ route('tasks.index') }}" class="btn-reset">Reset</a>

        <input type="text" name="search" id="searchInput" value="{{ $search }}" placeholder="Search tasks..." class="search-input" oninput="realtimeSearch()">
    </form>

    <div class="task-card">
        <div class="task-card-header">
            <span class="task-card-title">Pending Tasks</span>
            <span class="count-badge" id="pendingCount">{{ $pendingTasks->count() }}</span>
        </div>
        <div class="task-card-body">
            @if($pendingTasks->isEmpty())
                <div class="empty-state">
                    <p>No pending tasks! Great job!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="tasks-table" id="pendingTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingTasks as $task)
                            @php
                                $today = now()->startOfDay();
                                $dueDate = $task->due_date->startOfDay();
                                $diff = $today->diffInDays($dueDate, false);
                            @endphp
                            <tr class="{{ $diff < 0 ? 'overdue-row' : '' }}">
                                <td>
                                    <div class="task-title">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div class="task-desc">{{ $task->description }}</div>
                                    @endif
                                </td>
                                <td><span class="badge-cat">{{ $task->category->name }}</span></td>
                                <td>
                                    <span class="due-date">{{ $task->due_date->format('M d, Y') }}</span>
                                    @if($diff < 0)
                                        <span class="due-label overdue-label">Overdue</span>
                                    @elseif($diff == 0)
                                        <span class="due-label today-label">Due Today</span>
                                    @elseif($diff == 1)
                                        <span class="due-label tomorrow-label">Due Tomorrow</span>
                                    @endif
                                </td>
                                <td class="actions-cell">
                                    <div class="action-group">
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn-undo">Edit</a>
                                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-complete">Complete</button>
                                        </form>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr id="pendingEmpty" style="display:none;">
                                <td colspan="4" class="empty-search">No tasks found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="task-card">
        <div class="task-card-header">
            <span class="task-card-title">Completed Tasks</span>
            <span class="count-badge done" id="completedCount">{{ $completedTasks->count() }}</span>
        </div>
        <div class="task-card-body">
            @if($completedTasks->isEmpty())
                <div class="empty-state">
                    <p>No completed tasks yet!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="tasks-table" id="completedTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($completedTasks as $task)
                            <tr class="completed-row">
                                <td>
                                    <div class="task-title completed-text">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div class="task-desc completed-text">{{ $task->description }}</div>
                                    @endif
                                </td>
                                <td><span class="badge-cat">{{ $task->category->name }}</span></td>
                                <td><span class="due-date">{{ $task->due_date->format('M d, Y') }}</span></td>
                                <td class="actions-cell">
                                    <div class="action-group">
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn-undo">Edit</a>
                                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="inline-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-undo">Undo</button>
                                        </form>
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr id="completedEmpty" style="display:none;">
                                <td colspan="4" class="empty-search">No tasks found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .main-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .tasks-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .tasks-header h2 {
        color: #1a2f5a;
        font-weight: 700;
        font-size: 1.8rem;
        margin: 0;
    }

    .btn-add {
        background-color: #1a2f5a;
        color: white;
        border: none;
        border-radius: 40px;
        padding: 10px 24px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(26, 47, 90, 0.2);
        display: inline-block;
    }

    .btn-add:hover {
        background-color: #253d73;
        transform: translateY(-1px);
    }

    .filter-form {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 8px 14px;
        border: 1px solid #dde3f0;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #1a2f5a;
        background: white;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
    }

    .btn-filter {
        background: #1a2f5a;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
    }

    .btn-filter:hover {
        background: #253d73;
    }

    .btn-reset {
        background: #f0f4ff;
        color: #1a2f5a;
        border: 1px solid #dde3f0;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }

    .btn-reset:hover {
        background: #e0e8ff;
    }

    .search-input {
        padding: 8px 14px;
        border: 1px solid #dde3f0;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #1a2f5a;
        background: white;
        font-family: 'Poppins', sans-serif;
        flex: 1;
        outline: none;
    }

    .search-input:focus {
        border-color: #1a2f5a;
    }

    .task-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        margin-bottom: 32px;
        overflow: hidden;
    }

    .task-card-header {
        padding: 20px 28px;
        background: white;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .task-card-title {
        font-weight: 700;
        font-size: 1.2rem;
        color: #1a2f5a;
    }

    .count-badge {
        background: #eff3fa;
        color: #1a2f5a;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 40px;
    }

    .count-badge.done {
        background: #e6f7ec;
        color: #2d7a4f;
    }

    .task-card-body {
        padding: 0;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .tasks-table {
        width: 100%;
        border-collapse: collapse;
    }

    .tasks-table th {
        text-align: left;
        padding: 16px 24px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #7f8c8d;
        background-color: #fafcff;
        border-bottom: 1px solid #eef2f8;
    }

    .tasks-table td {
        padding: 18px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .tasks-table tr:last-child td {
        border-bottom: none;
    }

    .overdue-row {
        background-color: #fff9f9;
    }

    .overdue-row td:first-child {
        border-left: 4px solid #e74c3c;
        padding-left: 20px;
    }

    .completed-row {
        opacity: 0.75;
    }

    .completed-text {
        text-decoration: line-through;
        color: #a0aec0;
    }

    .task-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 4px;
    }

    .task-desc {
        font-size: 0.8rem;
        color: #7f8c8d;
        margin-top: 4px;
    }

    .due-date {
        font-weight: 500;
        color: #4a5568;
    }

    .badge-cat {
        background: #eef2ff;
        color: #1a2f5a;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 40px;
        display: inline-block;
        white-space: nowrap;
    }

    .due-label {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 30px;
        margin-left: 8px;
        white-space: nowrap;
        display: inline-block;
    }

    .overdue-label {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .today-label {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .tomorrow-label {
        background: #ffe5cc;
        color: #cc7a00;
        border: 1px solid #ffd8b3;
    }

    .actions-cell {
        white-space: nowrap;
    }

    .action-group {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-undo, .btn-complete, .btn-delete {
        border-radius: 30px;
        padding: 5px 14px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-family: 'Poppins', sans-serif;
    }

    .btn-undo {
        background: #fff8e7;
        color: #b86b00;
        border: 1px solid #ffdfa5;
    }

    .btn-undo:hover {
        background: #ffefcf;
    }

    .btn-complete {
        background: #e8f7ef;
        color: #2d7a4f;
        border: 1px solid #b8e0cc;
    }

    .btn-complete:hover {
        background: #d4f0e3;
    }

    .btn-delete {
        background: #feeceb;
        color: #c0392b;
        border: 1px solid #f5cdca;
    }

    .btn-delete:hover {
        background: #fddfdd;
    }

    .inline-form {
        display: inline;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #a0aec0;
    }

    .empty-state p {
        font-size: 0.9rem;
    }

    .empty-search {
        text-align: center;
        padding: 40px;
        color: #a0aec0;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .tasks-header {
            flex-direction: column;
            align-items: stretch;
        }

        .tasks-header h2 {
            text-align: center;
            font-size: 1.5rem;
        }

        .btn-add {
            text-align: center;
        }

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-select, .btn-filter, .btn-reset, .search-input {
            width: 100%;
            flex: none;
        }

        .tasks-table th, .tasks-table td {
            padding: 12px 16px;
        }

        .action-group {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .actions-cell {
            white-space: normal;
        }
    }
</style>

<script>
function realtimeSearch() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();

    const pendingRows = document.querySelectorAll('#pendingTable tbody tr:not(#pendingEmpty)');
    let pendingVisible = 0;
    pendingRows.forEach(row => {
        const title = row.querySelector('.task-title');
        if (title) {
            const text = title.textContent.toLowerCase();
            const show = text.includes(searchValue);
            row.style.display = show ? '' : 'none';
            if (show) pendingVisible++;
        }
    });
    document.getElementById('pendingCount').textContent = pendingVisible;
    const pendingEmpty = document.getElementById('pendingEmpty');
    if (pendingEmpty) pendingEmpty.style.display = pendingVisible === 0 ? '' : 'none';

    const completedRows = document.querySelectorAll('#completedTable tbody tr:not(#completedEmpty)');
    let completedVisible = 0;
    completedRows.forEach(row => {
        const title = row.querySelector('.task-title');
        if (title) {
            const text = title.textContent.toLowerCase();
            const show = text.includes(searchValue);
            row.style.display = show ? '' : 'none';
            if (show) completedVisible++;
        }
    });
    document.getElementById('completedCount').textContent = completedVisible;
    const completedEmpty = document.getElementById('completedEmpty');
    if (completedEmpty) completedEmpty.style.display = completedVisible === 0 ? '' : 'none';
}
</script>

@endsection