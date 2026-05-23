<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #f5f7fc 0%, #eef2f8 100%);
            font-family: 'Poppins', sans-serif;
            color: #1a2f5a;
            min-height: 100vh;
        }

        .top-header {
            background: linear-gradient(135deg, #1a2f5a 0%, #1e3a6b 100%);
            padding: 20px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-header h1 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin: 0;
        }

        .top-header span {
            color: #b0c4de;
            font-size: 0.85rem;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 14px;
            border-radius: 40px;
        }

        .main-wrapper {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 24px;
        }

        .alert-success {
            background: #e8f7ef;
            border-left: 4px solid #2d7a4f;
            border-radius: 12px;
            color: #1e5a3a;
            font-size: 0.9rem;
            margin-bottom: 28px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .alert-success button {
            background: none;
            border: none;
            color: #2d7a4f;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .alert-success button:hover {
            opacity: 1;
        }

        .card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            margin-bottom: 32px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #edf2f7;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-header .title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #1a2f5a;
            letter-spacing: -0.2px;
        }

        .count-badge {
            background: #eff3fa;
            color: #1a2f5a;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 40px;
        }

        .count-badge.done {
            background: #e6f7ec;
            color: #2d7a4f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #7f8c8d;
            font-weight: 600;
            border-bottom: 1px solid #eef2f8;
            padding: 16px 24px;
            text-align: left;
            background-color: #fafcff;
        }

        table td {
            padding: 18px 24px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #2d3748;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr:hover td {
            background-color: #fafcff;
        }

        .task-completed td {
            text-decoration: line-through;
            color: #a0aec0;
            background-color: #fefefe;
        }

        .overdue {
            background-color: #fff9f9;
            border-left: 4px solid #e74c3c;
        }

        .overdue td:first-child {
            border-left: 4px solid #e74c3c;
            padding-left: 20px;
        }

        .badge-cat {
            background: #eef2ff;
            color: #1a2f5a;
            font-size: 0.72rem;
            padding: 5px 12px;
            border-radius: 40px;
            font-weight: 600;
            display: inline-block;
        }

        .btn-add {
            background: linear-gradient(135deg, #1a2f5a 0%, #1e3a6b 100%);
            color: white;
            border: none;
            border-radius: 40px;
            padding: 10px 24px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(26, 47, 90, 0.2);
        }

        .btn-add:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(26, 47, 90, 0.25);
            background: linear-gradient(135deg, #1e3a6b 0%, #1a2f5a 100%);
        }

        .btn-complete {
            background: #e8f7ef;
            color: #2d7a4f;
            border: 1px solid #b8e0cc;
            border-radius: 30px;
            padding: 5px 14px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-complete:hover {
            background: #d4f0e3;
            transform: translateY(-1px);
        }

        .btn-undo {
            background: #fff8e7;
            color: #b86b00;
            border: 1px solid #ffdfa5;
            border-radius: 30px;
            padding: 5px 14px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-undo:hover {
            background: #ffefcf;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #feeceb;
            color: #c0392b;
            border: 1px solid #f5cdca;
            border-radius: 30px;
            padding: 5px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-delete:hover {
            background: #fddfdd;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 24px;
            color: #a0aec0;
        }

        .empty-state p {
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .overdue-badge {
            background: #fee2e2;
            color: #c0392b;
            font-size: 0.68rem;
            padding: 3px 10px;
            border-radius: 40px;
            font-weight: 700;
            margin-left: 8px;
            display: inline-block;
        }

        .d-flex {
            display: flex;
        }

        .gap-2 {
            gap: 8px;
        }

        .text-muted {
            color: #7f8c8d;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (max-width: 768px) {
            .top-header {
                padding: 16px 24px;
                flex-direction: column;
                text-align: center;
            }

            .top-header h1 {
                font-size: 1.2rem;
            }

            .main-wrapper {
                padding: 0 16px;
                margin: 24px auto;
            }

            .card-header {
                padding: 16px 20px;
            }

            table th,
            table td {
                padding: 12px 16px;
            }

            .btn-add,
            .btn-complete,
            .btn-undo,
            .btn-delete {
                padding: 6px 12px;
                font-size: 0.7rem;
            }
        }

        @media (max-width: 640px) {
            .row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>

<div class="top-header">
    <h1>Task Management System</h1>
    <span>To-do List</span>
</div>

<div class="main-wrapper">
    @if(session('success'))
        <div class="alert-success">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'">×</button>
        </div>
    @endif

    @yield('content')
</div>

</body>
</html>