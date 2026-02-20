<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $subscription->invoice_number }}</title>
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
            font-family: 'DejaVu Sans', sans-serif;
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

        /* Centered Header */
        .invoice-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-name {
            font-size: 26px;
            font-weight: bold;
            color: #4f46e5;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .brand-sub {
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: block;
            margin-top: 5px;
        }

        .invoice-label {
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

        /* Info Grid */
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

        /* Items Table */
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

        /* Totals Area */
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

        /* Payment Badge */
        .payment-status {
            float: left;
            margin-top: 20px;
            padding: 15px;
            border: 2px dashed #10b981;
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

        .payment-method {
            font-size: 10px;
            text-transform: uppercase;
            margin-top: 5px;
            display: block;
        }

        /* Footer */
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

        .website-url {
            font-weight: bold;
            color: #4f46e5;
            font-size: 13px;
        }

        .rupee {
            font-family: 'DejaVu Sans';
        }
    </style>
</head>

<body>
    <div class="top-accent"></div>
    <div class="page-wrapper">
        <!-- Centered Header -->
        <div class="invoice-header">
            <h1 class="brand-name">WEBVIBE INFOTECH</h1>
            <span class="brand-sub">Premium Software Solutions</span>
            <div class="invoice-label">INVOICE</div>
        </div>

        <!-- Info Grid -->
        <table class="info-table">
            <tr>
                <td>
                    <span class="section-title">Invoice Details</span>
                    <div class="info-content">
                        <strong>#{{ $subscription->invoice_number }}</strong>
                        Date: {{ $subscription->invoice_date->format('d M, Y') }}<br>
                        Due: Paid in Full
                    </div>
                </td>
                <td>
                    <span class="section-title">Issued By</span>
                    <div class="info-content">
                        <strong>Webvibe Infotech</strong>
                        301, The Spire 1, Ayodhya Chowk,<br>
                        150 Feet Ring Road, Rajkot, Gujarat<br>
                        sales@webvibeinfotech.in
                    </div>
                </td>
                <td style="text-align: right;">
                    <span class="section-title">Bill To</span>
                    <div class="info-content">
                        <strong>{{ $subscription->school->name }}</strong>
                        {{ $subscription->school->address ?? 'N/A' }}<br>
                        {{ $subscription->school->email }}<br>
                        {{ $subscription->school->phone ?? 'N/A' }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="70%">Service Description</th>
                    <th width="10%" style="text-align: center;">Qty</th>
                    <th width="20%" style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-description">{{ $subscription->plan->name }} Subscription Plan</div>
                        <div class="item-details">
                            Validity: {{ $subscription->start_date->format('d M, Y') }} -
                            {{ $subscription->end_date->format('d M, Y') }}<br>
                            Includes: {{ $subscription->plan->student_limit }} Students,
                            {{ $subscription->plan->batch_limit }} Batches
                        </div>
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right; font-weight: bold;">
                        <span class="rupee">&#8377;</span>{{ number_format($subscription->amount_paid, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Totals and Payment Area -->
        <div class="totals-container">
            <div class="payment-status">
                <span class="status-text">PAID</span>
                <span class="payment-method">Via {{ ucfirst($subscription->payment_method) }}</span>
                <div style="font-size: 9px; margin-top: 10px; color: #64748b;">
                    TXN: {{ $subscription->transaction_id ?? 'SCH-TRN-' . $subscription->id }}
                </div>
            </div>

            <table class="totals-table">
                <tr>
                    <td class="total-label">Subtotal</td>
                    <td class="total-amount"><span
                            class="rupee">&#8377;</span>{{ number_format($subscription->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td class="total-label">Tax (0%)</td>
                    <td class="total-amount"><span class="rupee">&#8377;</span>0.00</td>
                </tr>
                <tr class="grand-total">
                    <td class="total-label">TOTAL PAID</td>
                    <td class="total-amount"><span
                            class="rupee">&#8377;</span>{{ number_format($subscription->amount_paid, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="page-footer">
            <p class="footer-note">This is a computer-generated invoice. No physical signature is required.</p>
            <p class="footer-note">Thank you for your business. We look forward to working with you again!</p>
            <div class="website-url">www.webvibeinfotech.in</div>
        </div>
    </div>
</body>

</html>