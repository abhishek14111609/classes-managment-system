<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Kit Receipt #{{ $sale->id }}</title>
    <style>
        @page {
            margin: 0;
            size: a4 portrait;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }

        body {
            font-family: 'DejaVu Sans', 'FreeSans', sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            line-height: 1.5;
            font-size: 11px;
            background-color: #ffffff;
        }

        .page-wrapper {
            padding: 50px;
        }

        .top-accent {
            height: 8px;
            background-color: #4f46e5;
            width: 100%;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .school-name {
            font-size: 26px;
            font-weight: bold;
            color: #4f46e5;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .school-sub {
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: block;
            margin-top: 5px;
        }

        .receipt-label {
            font-size: 36px;
            font-weight: 800;
            color: #0f172a;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 5px;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .info-table td {
            vertical-align: top;
            width: 33.33%;
        }

        .section-title {
            font-size: 10px;
            font-weight: 800;
            color: #4f46e5;
            text-transform: uppercase;
            margin-bottom: 12px;
            display: block;
        }

        .info-content {
            color: #334155;
            font-size: 11px;
            line-height: 1.6;
        }

        .info-content strong {
            color: #0f172a;
            font-size: 13px;
            display: block;
            margin-bottom: 4px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        .items-table th {
            text-align: left;
            background: #f8fafc;
            color: #475569;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 15px;
            border-bottom: 2px solid #4f46e5;
        }

        .items-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .item-description {
            font-weight: bold;
            color: #0f172a;
            font-size: 13px;
        }

        .item-details {
            color: #64748b;
            font-size: 10px;
            margin-top: 5px;
        }

        .totals-container {
            width: 100%;
            margin-top: 20px;
        }

        .totals-table {
            width: 300px;
            float: right;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 10px 0;
            font-size: 12px;
        }

        .total-label {
            text-align: right;
            color: #64748b;
            padding-right: 30px;
        }

        .total-amount {
            text-align: right;
            font-weight: bold;
            color: #1e293b;
            width: 120px;
        }

        .grand-total {
            border-top: 2px solid #e2e8f0;
            padding-top: 20px;
            margin-top: 10px;
        }

        .grand-total .total-label {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .grand-total .total-amount {
            font-size: 22px;
            font-weight: 800;
            color: #4f46e5;
        }

        .receipt-status {
            float: left;
            margin-top: 20px;
            padding: 15px;
            border: 2px solid #10b981;
            border-radius: 8px;
            color: #10b981;
            text-align: center;
            width: 200px;
        }

        .status-text {
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .page-footer {
            clear: both;
            margin-top: 100px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 30px;
        }

        .footer-note {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 10px;
        }

        .rupee {
            font-family: 'FreeSans', 'DejaVu Sans', sans-serif;
        }
    </style>
</head>

<body>
    <div class="top-accent"></div>
    <div class="page-wrapper">
        <div class="receipt-header">
            <h1 class="school-name">{{ $sale->school->name }}</h1>
            <span class="school-sub">Official Kit & Equipment Receipt</span>
            <div class="receipt-label">KIT INVOICE</div>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <span class="section-title">Invoice Info</span>
                    <div class="info-content">
                        <strong>#KIT-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</strong>
                        Date: {{ $sale->created_at->format('d M, Y') }}<br>
                        Time: {{ $sale->created_at->format('h:i A') }}
                    </div>
                </td>
                <td>
                    <span class="section-title">Sold By</span>
                    <div class="info-content">
                        <strong>{{ $sale->school->name }}</strong>
                        {{ $sale->school->address }}<br>
                        {{ $sale->school->email }}
                    </div>
                </td>
                <td style="text-align: right;">
                    <span class="section-title">Student Details</span>
                    <div class="info-content">
                        <strong>{{ $sale->student->user->name }}</strong>
                        ID: {{ $sale->student->student_id }}<br>
                        Roll: {{ $sale->student->roll_number }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">Description</th>
                    <th width="15%" style="text-align: center;">Qty</th>
                    <th width="15%" style="text-align: right;">Unit Price</th>
                    <th width="20%" style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-description">{{ $sale->item->name }}</div>
                        <div class="item-details">
                            Category: {{ $sale->item->category ?? 'General' }}
                        </div>
                    </td>
                    <td style="text-align: center;">{{ $sale->quantity }}</td>
                    <td style="text-align: right;"><span
                            class="rupee">&#x20B9;</span>{{ number_format($sale->unit_price, 2) }}</td>
                    <td style="text-align: right; font-weight: bold;"><span
                            class="rupee">&#x20B9;</span>{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="totals-container">
            <div class="receipt-status @if($sale->payment_status !== 'paid') border-warning text-warning @endif">
                <span class="status-text">{{ strtoupper($sale->payment_status) }}</span>
                <div style="font-size: 9px; margin-top: 10px; color: #64748b;">
                    Official Inventory Receipt
                </div>
            </div>

            <table class="totals-table">
                <tr class="grand-total">
                    <td class="total-label">NET AMOUNT</td>
                    <td class="total-amount"><span
                            class="rupee">&#x20B9;</span>{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="page-footer">
            <p class="footer-note">This receipt confirms the purchase of sports/academic equipment.</p>
            <p class="footer-note">Thank you for your purchase!</p>
        </div>
    </div>
</body>

</html>