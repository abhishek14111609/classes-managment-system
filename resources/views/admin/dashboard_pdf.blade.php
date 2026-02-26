<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Dashboard Export Report</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4e54c8;
            margin-bottom: 5px;
        }

        .date {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            color: #333;
        }

        .highlight {
            font-weight: bold;
            color: #4e54c8;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>
    <h1>System Analysis Report</h1>
    <div class="date">Generated on {{ date('d M Y, h:i A') }}</div>

    <div class="section-title">Platform Statistics</div>
    <table>
        <tr>
            <th>Total Schools</th>
            <td>{{ $stats['total_schools'] }}</td>
        </tr>
        <tr>
            <th>Active Schools</th>
            <td class="highlight">{{ $stats['active_schools'] }}</td>
        </tr>
        <tr>
            <th>Expired Subscriptions</th>
            <td>{{ $stats['expired_schools'] }}</td>
        </tr>
        <tr>
            <th>Total Revenue</th>
            <td class="highlight">Rs. {{ number_format($stats['total_revenue'], 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Subscription Overview</div>
    <table>
        <tr>
            <th>Total Active Subscriptions</th>
            <td>{{ $stats['active_subscriptions'] }}</td>
        </tr>
        <tr>
            <th>Total Registered Plans</th>
            <td>{{ $stats['plan_count'] }}</td>
        </tr>
    </table>

    <div class="section-title">User Accounts</div>
    <table>
        <tr>
            <th>Total Users</th>
            <td>{{ $stats['users_total'] }}</td>
        </tr>
        <tr>
            <th>Active Users</th>
            <td>{{ $stats['users_active'] }}</td>
        </tr>
    </table>
</body>

</html>