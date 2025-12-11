<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firewall Logs</title>

    <style>
        :root {
            --primary: #2563eb;
            --danger: #dc2626;
            --warning: #f59e0b;
            --success: #10b981;
            --info: #0ea5e9;
            --bg: #f9fafb;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text);
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .back {
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .back:hover {
            color: var(--primary);
        }

        .back:hover svg path {
            fill: var(--primary);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid var(--border);
            background: var(--card);
            cursor: pointer;
        }

        .btn:hover {
            background: var(--bg);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            margin-top: 4px;
        }

        /* Card */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .card-title {
            font-weight: 600;
            font-size: 16px;
        }

        .search {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            width: 240px;
            font-size: 14px;
        }

        .search:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            background: var(--bg);
        }

        td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        tbody tr:hover {
            background: var(--bg);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .ip-address {
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }

        /* Tags */
        .tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
        }

        .danger { background: #fee2e2; color: #991b1b; }
        .warning { background: #fef3c7; color: #92400e; }
        .info { background: #dbeafe; color: #1e40af; }
        .neutral { background: #f3f4f6; color: #374151; }

        .timestamp-main {
            font-weight: 500;
        }

        .time-sm {
            font-size: 13px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--muted);
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--muted);
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn {
                flex: 1;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .search {
                width: 100%;
            }

            th, td {
                padding: 10px 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <a href="{{ route('firewall.dashboard') }}" class="back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 512 512">
                    <path d="M411.5 281h-298c-13.81 0-25-11.19-25-25s11.19-25 25-25h298c13.81 0 25 11.19 25 25s-11.19 25-25 25z" fill="currentColor"/>
                    <path d="M227.99 399.25c-6.08 0-12.18-2.21-16.99-6.67L83.5 274.33a25 25 0 0 1 .25-36.89l131-118.25c10.25-9.25 26.06-8.44 35.31 1.81s8.44 26.06-1.81 35.31l-110.72 99.94L245 355.92c10.12 9.39 10.72 25.21 1.33 35.33-4.93 5.31-11.62 8-18.34 8z" fill="currentColor"/>
                </svg>
                Back to Dashboard
            </a>
            
            <div class="header-content">
                <div>
                    <div class="title">Firewall Logs</div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat">
                <div class="stat-label">Total Entries</div>
                <div class="stat-value">{{ number_format($totalEntries ?? $logs->total()) }}</div>
            </div>

            <div class="stat">
                <div class="stat-label">Current Page</div>
                <div class="stat-value">{{ $logs->currentPage() }} / {{ $logs->lastPage() }}</div>
            </div>

            <div class="stat">
                <div class="stat-label">Latest Event</div>
                <div class="stat-value">
                    @if(isset($latestEvent) && $latestEvent)
                        {{ \Carbon\Carbon::parse($latestEvent->created_at)->diffForHumans() }}
                    @elseif($logs->count() > 0)
                        {{ $logs->first()->created_at->diffForHumans() }}
                    @else
                        —
                    @endif
                </div>
            </div>

            <div class="stat">
                <div class="stat-label">System Status</div>
                <div class="stat-value" style="color: var(--success)">Active</div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">

            <div class="card-header">
                <div class="card-title">Recent Security Events</div>

                <form method="GET">
                    <input
                        type="text"
                        class="search"
                        name="search"
                        placeholder="Search IP..."
                        value="{{ request('search') }}"
                        onkeyup="clearTimeout(window.searchDebounce);
                            window.searchDebounce = setTimeout(() => {
                                this.form.submit();
                            }, 500);">
                </form>
            </div>

            <table>
                <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Threat Type</th>
                    <th>Timestamp</th>
                </tr>
                </thead>

                <tbody>
                @forelse ($logs as $log)

                    @php
                        $types = [
                            'ratelimit' => ['danger', 'Rate Limit'],
                            'block' => ['warning', 'Blacklist'],
                            'country_block' => ['info', 'Country Block'],
                            'cidr_block' => ['neutral', 'CIDR Block'],
                        ];

                        $type = $types[$log->action] ?? ['neutral', ucfirst($log->action)];
                    @endphp

                    <tr>
                        <td>
                            <span class="ip-address">{{ $log->ip_address }}</span>
                        </td>

                        <td>
                            <span class="tag {{ $type[0] }}">{{ $type[1] }}</span>
                        </td>

                        <td>
                            <div class="timestamp-main">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y') }}</div>
                            <div class="time-sm">{{ \Carbon\Carbon::parse($log->created_at)->format('g:i A') }} · {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">No logs found</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            @if($logs->hasPages())
            <div class="pagination-wrapper">
                <span>
                    Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ number_format($logs->total()) }}
                </span>
                <span>
                    {{ $logs->links('firewall::pagination') }}
                </span>
            </div>
            @endif
        </div>
    </div>
</body>
</html>