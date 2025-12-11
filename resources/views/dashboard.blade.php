<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firewall Dashboard</title>

    <style>
        :root {
            --danger: #ef4444;
            --success: #10b981;
            --bg: #f5f5f7;
            --card-bg: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .d-flex{
            display: flex;
        } 

        .justify-between{
            justify-content: space-between
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        /* Header */
        .header {
            background: var(--card-bg);
            padding: 20px 25px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
        }

        .badge {
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            color: white;
        }
        .badge.success { background: var(--success); }
        .badge.danger { background: var(--danger); }

        /* Cards Grid */
        .grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .card-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            color: var(--muted);
        }

        .value {
            font-size: 34px;
            font-weight: 800;
        }

        /* Threat list */
        .threat-list {
            margin-top: 15px;
        }

        .threat-item {
            background: #f9fafb;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .threat-name {
            font-weight: bold;
        }

        .threat-count {
            background: var(--text);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: bold;
        }
    </style>
</head>

<body>
<div class="container">

    <!-- Header -->
    <div class="header">
        <div class="title">Firewall Dashboard</div>

        <div class="badge {{ $status ? 'success' : 'danger' }}">
            {{ $status ? 'Active' : 'Inactive' }}
        </div>
    </div>

    <!-- Grid -->
    <div class="grid">

        <!-- Today -->
        <div class="card">
            <div class="card-title">Today's Blocks</div>
            <div class="value">{{ number_format($todayBlocks) }}</div>
        </div>

        <!-- Logs -->
        <div class="card">
            <div class="card-title d-flex justify-between">
                <span>Total Logs</span>
                <a href="{{ route('firewall.logs') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g fill-rule="evenodd" clip-rule="evenodd"><circle cx="256" cy="256" r="256" fill="#e5aa17" opacity="1" data-original="#e5aa17" class=""></circle><path fill="#ffffff" d="M255.995 366.255c-75.203 0-140.201-41.094-174.994-110.256 34.793-69.159 99.791-110.252 174.994-110.252 75.199 0 140.201 41.094 175.005 110.252-34.804 69.162-99.806 110.256-175.005 110.256zm0-189.717c-43.82 0-79.46 35.644-79.46 79.46 0 43.82 35.64 79.464 79.46 79.464 43.817 0 79.46-35.644 79.46-79.464 0-43.816-35.644-79.46-79.46-79.46zm0 133.723c29.919 0 54.259-24.343 54.259-54.262 0-29.915-24.34-54.259-54.259-54.259s-54.259 24.343-54.259 54.259c0 29.919 24.339 54.262 54.259 54.262z" opacity="1" data-original="#ffffff"></path></g></g></svg>
                </a>
            </div>
            <div class="value">{{ number_format($totalLogs) }}</div>
        </div>

        <!-- Threats -->
        <div class="card">
            <div class="card-title">Threat Breakdown</div>

            <div class="threat-list">
                @foreach ($threats as $name => $count)
                    <div class="threat-item">
                        <div class="threat-name">{{ ucfirst($name) }}</div>
                        <div class="threat-count">{{ ucfirst($count) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>
</html>
