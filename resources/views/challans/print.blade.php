<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Fee Challan - {{ $challan->challan_no }}</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .container1 {
                display: flex;
                height: 148mm;
                /* HALF A4 */
                width: 100%;
            }

            .copy {
                width: 100% !important;
                flex: 1;
                /* transform: scale(0.95); */
                transform-origin: top left;
            }
        }


        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #000;
        }

        .container1 {
            display: flex;
            height: 50vh;
        }

        .copy {
            width: 100%;
            height: 100%;
            /* overflow: hidden; */
        }


        .copy {
            flex: 1;
            border: 1.5px solid #888;
            padding: 5px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.5px solid #aaa;
            padding-bottom: 4px;
            margin-bottom: 4px;
        }

        .header img.logo {
            height: 60px;
            object-fit: contain;
        }

        .header img.photo {
            width: 80px;
            height: 80px;
            /* border: 2px solid #999; */
            /* border-radius: 8px; */
            object-fit: contain;
            background: #f9f9f9;
        }

        .institute-name {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 0px 0;
            color: #222;
            letter-spacing: 0.5px;
        }

        .copy-title {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            margin: 0 0 8px 0;
            color: #333;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .challan-no {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }

        /* NO TABLE BORDERS - Clean professional look */
        .info-table,
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            border: none !important;
        }

        .info-table td,
        .fee-table th,
        .fee-table td {
            border: none !important;
            padding: 2px 2px;
            vertical-align: top;
        }

        .info-table td.label {
            font-weight: bold;
            color: #444;
            width: 120px;
            padding-right: 15px;
        }

        .fee-table th {
            font-weight: bold;
            text-align: left;
            color: #333;
            padding-bottom: 6px;
        }

        .fee-table .amount {
            text-align: right;
            font-weight: bold;
            color: #000;
            width: 100px;
        }

        .total-row {
            font-weight: bold;
            font-size: 12px;
            padding-top: 8px;
            border-top: 1px dashed #aaa;
            margin-top: 5px;
        }

        .total-row td {
            padding-top: 5px;
        }

        .amount-words {
            margin: 14px 0;
            font-style: italic;
            color: #444;
            font-size: 10.5px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px dashed #aaa;
        }

        .signatures {
            font-size: 10.5px;
            color: #333;
            line-height: 2.2;
        }

        .sign-line {
            border-top: 1px solid #666;
            width: 180px;
            display: inline-block;
            margin-top: 4px;
        }

        .bank-info {
            text-align: right;
            font-size: 10px;
            color: #444;
            line-height: 1.7;
        }

        .qr-code {
            text-align: right;
            margin-top: 8px;
        }

        .qr-code img {
            width: 70px;
            height: 70px;
            border: 1px solid #aaa;
            border-radius: 6px;
        }

        .print-date {
            font-size: 9px;
            text-align: right;
            margin-top: 4px;
            color: #666;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        .paid-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            font-weight: bold;
            color: rgba(255, 0, 0, 0.2);
            /* Semi-transparent red */
            pointer-events: none;
            z-index: 10;
            user-select: none;
            letter-spacing: 10px;
            text-transform: uppercase;
        }

        .copy {
            position: relative;
            /* Important: to make watermark position relative to each copy */
            overflow: hidden;
        }

        @media print {
            .paid-watermark {
                color: rgba(200, 0, 0, 0.15);
                /* Slightly more visible on print if needed */
            }
        }
    </style>
</head>

<body>

    @php
    $settings = \App\Models\Setting::first();
    $instituteName = $settings?->company_name ?? 'RAHBER INSTITUTE';
    $logoPath = $settings?->logo ? asset('storage/' . $settings->logo) : asset('assets/img/logo.png');

    $studentPhoto = $challan->student->student_image
    ? asset('storage/' . $challan->student->student_image)
    : asset('assets/img/stud.png');

    $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $qrCode = asset('assets/img/qr.png');

    $additionalFees = \App\Models\AllLedger::where('challan_no', $challan->challan_no)
    ->where('ledger_category', 'additional_fee')
    ->get();

    $totalAmount = $challan->amount;

    $amountWords = ucfirst($formatter->format($totalAmount)) . ' Rupees Only';

    $paidStamp = $settings?->paid_stamp
    ? asset('storage/' . $settings->paid_stamp)
    : asset('assets/img/paid-stamp.png');
    @endphp

    @php
    $additionalTotal = $additionalFees->sum('amount');

    // Tuition = Total Challan - Additional Fees
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
            <div class="header">
                <img src="{{ $logoPath }}" alt="Logo" class="logo">
                <img src="{{ $studentPhoto }}" alt="Student Photo" class="photo">
            </div>

            <div class="institute-name">{{ $instituteName }}</div>
            <div class="flex">
                <div class="copy-title">STUDENT COPY</div>
                <div class="challan-no">Challan No: {{ $challan->challan_no }}</div>
            </div>

            <table class="info-table">
                <tr>
                    <td class="label">Name</td>
                    <td>{{ $challan->student->name }}</td>
                </tr>
                <tr>
                    <td class="label">Father Name</td>
                    <td>{{ $challan->student->father_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Roll No</td>
                    <td>{{ $challan->student->rollnum ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Program</td>
                    <td>{{ $challan->student->program->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Class</td>
                    <td>{{ $challan->student->classSubject->class_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Mobile</td>
                    <td>{{ $challan->student->phone ?? 'N/A' }}</td>
                </tr>
            </table>

            @php
            $showPurpose = $tuitionAmount > 0 || $additionalFees->count() > 0;
            @endphp

            <table class="fee-table">
                <thead>
                    <tr>
                        <th>#</th>
                        @if($showPurpose)
                        <th>Purpose of Fee</th>
                        @endif
                        <th>Amount (Rs.)</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Tuition Fee --}}
                    @if($tuitionAmount > 0)
                    <tr>
                        <td>1</td>
                        @if($showPurpose)
                        <td>Tuition Fee</td>
                        @endif
                        <td class="amount">{{ number_format($tuitionAmount) }}/-</td>
                    </tr>
                    @endif

                    {{-- Additional Fees --}}
                    @foreach($additionalFees as $i => $fee)
                    <tr>
                        <td>{{ $i + 2 }}</td>
                        @if($showPurpose)
                        <td>
                            <strong>{{ $fee->title }}</strong>
                            @if(!empty($fee->description_fee))
                            <br>
                            <small>{{ $fee->description_fee }}</small>
                            @endif
                        </td>
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
                <div>
                    <!-- <div class="bank-info">
                    <div>Date: ________________</div>
                    <div>Branch Code: _________</div>
                </div> -->
                    <!-- <div class="qr-code">
                    <div class="print-date">Printed: {{ now()->format('d-m-Y') }}</div>
                    <img src="{{ $qrCode }}" alt="QR Code">
                </div> -->
                </div>
            </div>
        </div>

        <!-- College / Bank Copy -->
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
            <div class="header">
                <img src="{{ $logoPath }}" alt="Logo" class="logo">
                <img src="{{ $studentPhoto }}" alt="Student Photo" class="photo">
            </div>

            <div class="institute-name">{{ $instituteName }}</div>
            <div class="flex">
                <div class="copy-title">COLLEGE / BANK COPY</div>
                <div class="challan-no">Challan No: {{ $challan->challan_no }}</div>
            </div>

            <table class="info-table">
                <tr>
                    <td class="label">Name</td>
                    <td>{{ $challan->student->name }}</td>
                </tr>
                <tr>
                    <td class="label">Father Name</td>
                    <td>{{ $challan->student->father_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Roll No</td>
                    <td>{{ $challan->student->rollnum ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Program</td>
                    <td>{{ $challan->student->program->name ?? 'N/A' }}</td>
                </tr>
            </table>

            @php
            $showPurpose = false;

            if(isset($additionalFees)) {
            foreach ($additionalFees as $fee) {
            if (!empty($fee->title) || !empty($fee->description_fee)) {
            $showPurpose = true;
            break;
            }
            }
            }
            @endphp

            @php
            $showPurpose = $tuitionAmount > 0 || $additionalFees->count() > 0;
            @endphp

            <table class="fee-table">
                <thead>
                    <tr>
                        <th>#</th>
                        @if($showPurpose)
                        <th>Purpose of Fee</th>
                        @endif
                        <th>Amount (Rs.)</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- Tuition Fee --}}
                    @if($tuitionAmount > 0)
                    <tr>
                        <td>1</td>
                        @if($showPurpose)
                        <td>Challan Fee</td>
                        @endif
                        <td class="amount">{{ number_format($tuitionAmount) }}/-</td>
                    </tr>
                    @endif

                    {{-- Additional Fees --}}
                    @foreach($additionalFees as $i => $fee)
                    <tr>
                        <td>{{ $i + 2 }}</td>
                        @if($showPurpose)
                        <td>
                            <strong>{{ $fee->title }}</strong>
                            @if(!empty($fee->description_fee))
                            <br>
                            <small>{{ $fee->description_fee }}</small>
                            @endif
                        </td>
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
                <div>
                    <!-- <div class="bank-info">
                    <div>Date: ________________</div>
                    <div>Branch Code: _________</div>
                </div> -->
                    <!-- <div class="qr-code">
                    <div class="print-date">Printed: {{ now()->format('d-m-Y') }}</div>
                    <img src="{{ $qrCode }}" alt="QR Code">
                </div> -->
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-success btn-medium px-2">Print Challan</button>
        <a href="{{ route('challans.index') }}" class="btn btn-secondary btn-medium ms-3 px-2">Back to List</a>
    </div>

    <!-- Bootstrap JS (Bundle with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>