{{-- resources/views/tasks/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="create-wrapper">
    {{-- Back to Tasks link --}}
    <div class="back-link">
        <a href="{{ route('tasks.index') }}">← Back to Tasks</a>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <span class="form-card-title">Create New Task</span>
        </div>
        <div class="form-card-body">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf

                {{-- Title --}}
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter task title">
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Enter task description (optional)">{{ old('description') }}</textarea>
                </div>

                {{-- Category --}}
                <div class="form-group">
                    <label>Category <span class="required">*</span></label>
                    <select name="category_id">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Due Date --}}
                <div class="form-group">
                    <label>Due Date <span class="required">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}">
                    @error('due_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Create Task</button>
                    <a href="{{ route('tasks.index') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-wrapper {
        max-width: 700px;
        margin: 0 auto;
        padding: 10px;
    }

    .back-link {
        margin-bottom: 12px;
    }

    .back-link a {
        color: #1a2f5a;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        padding: 6px 0;
    }

    .back-link a:hover {
        color: #253d73;
        transform: translateX(-2px);
    }

    .form-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .form-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.08);
    }

    .form-card-header {
        padding: 24px 32px;
        border-bottom: 1px solid #edf2f7;
    }

    .form-card-title {
        font-weight: 700;
        font-size: 1.3rem;
        color: #1a2f5a;
        letter-spacing: -0.2px;
    }

    .form-card-body {
        padding: 32px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a2f5a;
        margin-bottom: 8px;
    }

    .required {
        color: #e74c3c;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.9rem;
        font-family: inherit;
        transition: all 0.2s ease;
        background: white;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #1a2f5a;
        box-shadow: 0 0 0 3px rgba(26, 47, 90, 0.1);
    }

    .form-group input:hover,
    .form-group textarea:hover,
    .form-group select:hover {
        border-color: #b0c4de;
    }

    .error-message {
        color: #e74c3c;
        font-size: 0.75rem;
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 8px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #1a2f5a 0%, #1e3a6b 100%);
        color: white;
        border: none;
        border-radius: 40px;
        padding: 10px 28px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(26, 47, 90, 0.2);
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(26, 47, 90, 0.25);
        background: linear-gradient(135deg, #1e3a6b 0%, #1a2f5a 100%);
    }

    .btn-cancel {
        background: #f4f6f9;
        color: #4a5568;
        padding: 10px 24px;
        border-radius: 40px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .btn-cancel:hover {
        background: #e8ecf2;
        transform: translateY(-1px);
    }

    @media (max-width: 640px) {
        .create-wrapper {
            padding: 0 16px;
        }

        .form-card-header {
            padding: 20px 24px;
        }

        .form-card-title {
            font-size: 1.2rem;
        }

        .form-card-body {
            padding: 24px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            text-align: center;
            justify-content: center;
        }
    }

    .create-wrapper,
    .create-wrapper *,
    .create-wrapper button,
    .create-wrapper a,
    .create-wrapper input,
    .create-wrapper select,
    .create-wrapper textarea {
        font-family: 'Poppins', sans-serif !important;
    }
</style>
@endsection