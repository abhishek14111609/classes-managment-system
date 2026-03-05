<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Financial Statement - {{ $student->user->name }}</title>
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
            font-family: 'FreeSans', 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            line-height: 1.5;
            font-size: 11px;
            background-color: #ffffff;
        }

        .page-wrapper {
            padding: 40px;
        }

        .top-accent {
            height: 8px;
            background-color: #0f172a;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
        }

        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }

        .document-label {
            font-size: 18px;
            font-weight: 800;
            color: #475569;
            margin: 10px 0;
            letter-spacing: 2px;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .info-grid td {
            vertical-align: top;
            width: 50%;
            padding: 5px;
        }

        .section-title {
            font-size: 9px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }

        .info-content {
            color: #334155;
            font-size: 11px;
        }

        .info-content strong {
            color: #0f172a;
            font-size: 13px;
            display: block;
            margin-bottom: 2px;
        }

        .summary-boxes {
            width: 100%;
            margin-bottom: 30px;
        }

        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 5px;
        }

        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .ledger-table th {
            text-align: left;
            background: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px 10px;
        }

        .ledger-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .type-dr {
            color: #dc2626;
            font-weight: bold;
        }

        .type-cr {
            color: #16a34a;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
        }

        .rupee {
            font-family: 'FreeSans', sans-serif;
        }
    </style>
</head>

<body>
    <div class="top-accent"></div>
    <div class="page-wrapper">
        <div class="header">
            <h1 class="school-name">{{ $school->name }}</h1>
            <div class="document-label">STUDENT FINANCIAL STATEMENT</div>
            <p style="margin: 5px 0; color: #64748b;">Generated on: {{ date('d M, Y H:i') }}</p>
        </div>

        <table class="info-grid">
            <tr>
                <td>
                    <span class="section-title">Athlete / Student Details</span>
                    <div class="info-content">
                        <strong>{{ $student->user->name }}</strong>
                        ID: {{ $student->roll_number }}<br>
                        Reg Date: {{ $student->admission_date->format('d M, Y') }}<br>
                        Primary Batch: {{ $student->batch->name ?? 'N/A' }}
                    </div>
                </td>
                <td style="text-align: right;">
                    <span class="section-title">Institutional Info</span>
                    <div class="info-content">
                        <strong>{{ $school->name }}</strong>
                        {{ $school->address }}<br>
                        Email: {{ $school->email }}<br>
                        Phone: {{ $school->phone ?? 'N/A' }}
                    </div>
                </td>
            </tr>
        </table>

        @php
            $totalDebit = 0;
            $totalCredit = 0;
            foreach ($ledger as $entry) {
                if ($entry['type'] == 'dr')
                    $totalDebit += $entry['amount'];
                else
                    $totalCredit += $entry['amount'];
            }
            $balance = $totalDebit - $totalCredit;
        @endphp

        <table class="summary-boxes" cellspacing="10">
            <tr>
                <td width="33%">
                    <div class="summary-box">
                        <span class="section-title">Total Assessed</span>
                        <div class="summary-value"><span class="rupee">₹</span>{{ number_format($totalDebit, 2) }}</div>
                    </div>
                </td>
                <td width="33%">
                    <div class="summary-box">
                        <span class="section-title">Total Paid</span>
                        <div class="summary-value" style="color: #16a34a;"><span
                                class="rupee">₹</span>{{ number_format($totalCredit, 2) }}</div>
                    </div>
                </td>
                <td width="33%">
                    <div class="summary-box" style="border-left: 4px solid #dc2626;">
                        <span class="section-title">Outstanding Balance</span>
                        <div class="summary-value" style="color: #dc2626;"><span
                                class="rupee">₹</span>{{ number_format($balance, 2) }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <table class="ledger-table">
            <thead>
                <tr>
                    <th width="15%">Date</th>
                    <th width="45%">Description / Reference</th>
                    <th width="20%" style="text-align: right;">Charges (Dr)</th>
                    <th width="20%" style="text-align: right;">Credits (Cr)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledger as $entry)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d/m/Y') }}</td>
                        <td>
                            <div style="font-weight: bold;">{{ str_replace('_', ' ', ucfirst($entry['description'])) }}
                            </div>
                            <small style="color: #64748b;">Ref: {{ $entry['reference'] }}</small>
                        </td>
                        <td style="text-align: right;" class="type-dr">
                            @if($entry['type'] == 'dr') <span class="rupee">₹</span>{{ number_format($entry['amount'], 2) }}
                            @else — @endif
                        </td>
                        <td style="text-align: right;" class="type-cr">
                            @if($entry['type'] == 'cr') <span class="rupee">₹</span>{{ number_format($entry['amount'], 2) }}
                            @else — @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: bold;">
                    <td colspan="2" style="text-align: right; padding: 15px;">CLOSING BALANCE</td>
                    <td colspan="2"
                        style="text-align: right; font-size: 14px; padding: 15px; color: {{ $balance > 0 ? '#dc2626' : '#16a34a' }};">
                        <span class="rupee">₹</span>{{ number_format($balance, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>This is a computer-generated financial statement and does not require a physical signature.</p>
            <p>&copy; {{ date('Y') }} {{ $school->name }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>