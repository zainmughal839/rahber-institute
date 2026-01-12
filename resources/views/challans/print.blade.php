<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Fee Challan - {{ $challan->challan_no }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, Helvetica, sans-serif; font-size: 10px; color: #000; line-height: 1.4; }

        @page { size: A4 portrait; margin: 0; }

        @media print {
            html, body { margin: 0 !important; padding: 0 !important; }
            .no-print { display: none !important; }
        }

        .container1 { display: flex; height: 148mm; width: 100%; }
        .copy { flex: 1; border: 1.5px solid #888; padding: 8px; display: flex; flex-direction: column; position: relative; background: #fff; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid #aaa; padding-bottom: 4px; margin-bottom: 4px; }
        .header img.logo { height: 60px; object-fit: contain; }
        .company-details { text-align: right; font-size: 11px; }
        .company-details h2 { font-size: 16px; margin-bottom: 2px; }
        .company-details p { font-size: 10px; margin: 1px 0; color: #555; }

        .institute-name { text-align: center; font-size: 12px; font-weight: bold; margin: 2px 0; color: #222; letter-spacing: 0.5px; }
        .copy-title { text-align: center; font-size: 10px; font-weight: bold; margin: 0 0 6px 0; color: #333; text-decoration: underline; text-underline-offset: 3px; }
        .challan-no { text-align: center; font-size: 13px; font-weight: bold; margin-bottom: 6px; color: #000; }

        .info-table, .fee-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .info-table td.label { font-weight: bold; color: #444; width: 130px; padding-right: 10px; }
        .fee-table th { font-weight: bold; text-align: left; color: #333; padding-bottom: 4px; border-bottom: 1px solid #aaa; }
        .fee-table td.amount { text-align: right; font-weight: bold; width: 100px; }

        .total-row { font-weight: bold; font-size: 12px; padding-top: 6px; border-top: 1px dashed #aaa; margin-top: 5px; }
        .amount-words { margin: 12px 0; font-style: italic; color: #444; font-size: 10px; }

        .footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; padding-top: 10px; border-top: 1px dashed #aaa; }
        .signatures { font-size: 10.5px; color: #333; line-height: 2; }
        .sign-line { border-top: 1px solid #666; width: 160px; display: inline-block; margin-top: 4px; }

        .paid-watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 80px; font-weight: bold; color: rgba(255,0,0,0.2); pointer-events: none; z-index: 10; letter-spacing: 10px; text-transform: uppercase; }

    </style>
</head>

<body>

@php
    $settings = \App\Models\Setting::first();
    $logoPath = $settings?->logo ? asset('storage/' . $settings->logo) : asset('assets/img/logo.png');
    $instituteName = $settings?->company_name ?? 'RAHBER INSTITUTE';

    $studentPhoto = $challan->student->student_image ? asset('storage/' . $challan->student->student_image) : asset('assets/img/stud.png');

    $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $additionalFees = \App\Models\AllLedger::where('challan_no', $challan->challan_no)->where('ledger_category', 'additional_fee')->get();
    $totalAmount = $challan->amount;
    $amountWords = ucfirst($formatter->format($totalAmount)) . ' Rupees Only';
    $paidStamp = $settings?->paid_stamp ? asset('storage/' . $settings->paid_stamp) : asset('assets/img/paid-stamp.png');

    $additionalTotal = $additionalFees->sum('amount');
    $tuitionAmount = max(0, $challan->amount - $additionalTotal);
    $showPurpose = $tuitionAmount > 0 || $additionalFees->count() > 0;
@endphp

<div class="container1">

    <!-- Student Copy -->
    <div class="copy">
        @if($challan->status === 'paid')
          <img src="{{ $paidStamp }}" alt="PAID Stamp" style="position:absolute; 
                        top:65%; 
                        left:50%; 
                        transform:translate(-50%,-50%) rotate(-30deg); 
                        width:250px; 
                        opacity:0.4; 
                        pointer-events:none; 
                        z-index:10;">
        @endif

        <!-- Header: Logo Left, Company Right -->
        <div class="header">
            <img src="{{ $logoPath }}" alt="Logo" class="logo">
            <div class="company-details">
                <h2>{{ $settings?->company_name ?? 'Company Name' }}</h2>
                <p>{{ $settings?->address ?? 'Address' }}</p>
                <p>Phone: {{ $settings?->phone ?? 'N/A' }} | Email: {{ $settings?->email ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="institute-name">{{ $instituteName }}</div>
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="copy-title">STUDENT COPY</div>
            <div class="challan-no">Challan No: {{ $challan->challan_no }}</div>
        </div>

        <table class="info-table">
            <tr><td class="label">Name</td><td>{{ $challan->student->name }}</td></tr>
            <tr><td class="label">Father Name</td><td>{{ $challan->student->father_name ?? 'N/A' }}</td></tr>
            <tr><td class="label">Roll No</td><td>{{ $challan->student->rollnum ?? 'N/A' }}</td></tr>
            <tr><td class="label">Program</td><td>{{ $challan->student->program->name ?? 'N/A' }}</td></tr>
            <tr><td class="label">Class</td><td>{{ $challan->student->classSubject->class_name ?? 'N/A' }}</td></tr>
            <tr><td class="label">Mobile</td><td>{{ $challan->student->phone ?? 'N/A' }}</td></tr>
        </table>

        <table class="fee-table">
            <thead>
                <tr>
                    <th>#</th>
                    @if($showPurpose) <th>Purpose of Fee</th> @endif
                    <th>Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @if($tuitionAmount > 0)
                <tr>
                    <td>1</td>
                    @if($showPurpose)<td>Tuition Fee</td>@endif
                    <td class="amount">{{ number_format($tuitionAmount) }}/-</td>
                </tr>
                @endif
                @foreach($additionalFees as $i => $fee)
                <tr>
                    <td>{{ $i + 2 }}</td>
                    @if($showPurpose)
                    <td><strong>{{ $fee->title }}</strong>@if($fee->description_fee)<br><small>{{ $fee->description_fee }}</small>@endif</td>
                    @endif
                    <td class="amount">{{ number_format($fee->amount) }}/-</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-row">
            <table style="width:100%; border:none;">
                <tr>
                    <td><strong>Total Amount</strong></td>
                    <td style="text-align:right;"><strong>Rs. {{ number_format($totalAmount) }}/-</strong></td>
                </tr>
            </table>
        </div>

        <div class="amount-words"><strong>Amount in Words:</strong> {{ $amountWords }}</div>

        <div class="footer">
            <div class="signatures">
                <div>Candidate's Signature</div>
                <div class="sign-line"></div>
                <br>
                <div>College Officer Signature</div>
                <div class="sign-line"></div>
            </div>
        </div>
    </div>

    <!-- College / Bank Copy: similar structure -->
    <div class="copy">
        @if($challan->status === 'paid')   <img src="{{ $paidStamp }}" alt="PAID Stamp" style="position:absolute; 
                        top:65%; 
                        left:50%; 
                        transform:translate(-50%,-50%) rotate(-30deg); 
                        width:250px; 
                        opacity:0.4; 
                        pointer-events:none; 
                        z-index:10;"> @endif

        <div class="header">
            <img src="{{ $logoPath }}" alt="Logo" class="logo">
            <div class="company-details">
                <h2>{{ $settings?->company_name ?? 'Company Name' }}</h2>
                <p>{{ $settings?->address ?? 'Address' }}</p>
                <p>Phone: {{ $settings?->phone ?? 'N/A' }} | Email: {{ $settings?->email ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="institute-name">{{ $instituteName }}</div>
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="copy-title">COLLEGE / BANK COPY</div>
            <div class="challan-no">Challan No: {{ $challan->challan_no }}</div>
        </div>

        <table class="info-table">
            <tr><td class="label">Name</td><td>{{ $challan->student->name }}</td></tr>
            <tr><td class="label">Father Name</td><td>{{ $challan->student->father_name ?? 'N/A' }}</td></tr>
            <tr><td class="label">Roll No</td><td>{{ $challan->student->rollnum ?? 'N/A' }}</td></tr>
            <tr><td class="label">Program</td><td>{{ $challan->student->program->name ?? 'N/A' }}</td></tr>
        </table>

        <table class="fee-table">
            <thead>
                <tr>
                    <th>#</th>
                    @if($showPurpose) <th>Purpose of Fee</th> @endif
                    <th>Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @if($tuitionAmount > 0)
                <tr>
                    <td>1</td>
                    @if($showPurpose)<td>Challan Fee</td>@endif
                    <td class="amount">{{ number_format($tuitionAmount) }}/-</td>
                </tr>
                @endif
                @foreach($additionalFees as $i => $fee)
                <tr>
                    <td>{{ $i + 2 }}</td>
                    @if($showPurpose)
                    <td><strong>{{ $fee->title }}</strong>@if($fee->description_fee)<br><small>{{ $fee->description_fee }}</small>@endif</td>
                    @endif
                    <td class="amount">{{ number_format($fee->amount) }}/-</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-row">
            <table style="width:100%; border:none;">
                <tr>
                    <td><strong>Total Amount</strong></td>
                    <td style="text-align:right;"><strong>Rs. {{ number_format($totalAmount) }}/-</strong></td>
                </tr>
            </table>
        </div>

        <div class="amount-words"><strong>Amount in Words:</strong> {{ $amountWords }}</div>

        <div class="footer">
            <div class="signatures">
                <div>Received By</div>
                <div class="sign-line"></div>
                <br>
                <div>College Stamp & Signature</div>
                <div class="sign-line"></div>
            </div>
        </div>

    </div>

</div>

<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-success btn-sm px-3">Print Challan</button>
    <a href="{{ route('challans.index') }}" class="btn btn-secondary btn-sm px-3">Back to List</a>
</div>

</body>
</html>
