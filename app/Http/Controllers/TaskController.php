<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $sort = $request->get('sort', 'due_date');
        $categoryFilter = $request->get('category', '');
        $search = $request->get('search', '');

        $pendingQuery = Task::with('category')->where('status', 'pending');
        $completedQuery = Task::with('category')->where('status', 'completed');

        if ($categoryFilter) {
            $pendingQuery->where('category_id', $categoryFilter);
            $completedQuery->where('category_id', $categoryFilter);
        }

        if ($search) {
            $pendingQuery->where('title', 'like', '%' . $search . '%');
            $completedQuery->where('title', 'like', '%' . $search . '%');
        }

        if ($sort === 'title') {
            $pendingQuery->orderBy('title');
            $completedQuery->orderBy('title');
        } else {
            $pendingQuery->orderBy('due_date');
            $completedQuery->orderBy('due_date');
        }

        $pendingTasks = $pendingQuery->get();
        $completedTasks = $completedQuery->get();

        return view('tasks.index', compact('pendingTasks', 'completedTasks', 'categories', 'sort', 'categoryFilter', 'search'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'due_date'    => 'required|date|after_or_equal:today',
        ]);

        Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'due_date'    => 'required|date|after_or_equal:today',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function toggle(Task $task)
    {
        $task->update([
            'status' => $task->status === 'pending' ? 'completed' : 'pending'
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task status updated!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}