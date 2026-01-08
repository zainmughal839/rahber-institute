<!DOCTYPE html>
<html>
<head>
    <title>Salary Challan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .challan {
            width: 100%;
            height: 50vh; /* A4 HALF PAGE */
            padding: 30px;
            border: 2px dashed #000;
            font-size: 14px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .label {
            font-weight: 600;
        }

        .table th {
            background: #f1f1f1;
        }

        @media print {
            body {
                background: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

<div class="container-fluid mt-2">

    <div class="challan mx-auto">

        {{-- HEADER --}}
        <div class="title">
            Teacher Salary Challan
        </div>

        {{-- BASIC INFO --}}
        <div class="row mb-3">
            <div class="col-6">
                <span class="label">Teacher Name:</span><br>
                {{ $salary->teacher->name }}
            </div>
            <div class="col-6 text-end">
                <span class="label">Date:</span><br>
                {{ $salary->created_at->format('d-m-Y') }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <span class="label">Salary Period:</span><br>
                {{ $salary->title }}
            </div>
        </div>

        <hr>

        {{-- SALARY DETAILS TABLE --}}
        @php
            // Basic Salary (Teacher table se)
            $basicSalary = $salary->teacher->salary ?? 0;

            // Deduction find from description (fallback safe)
            preg_match('/Deduction:\s*([\d.]+)/', $salary->description, $matches);
            $deduction = $matches[1] ?? 0;

            // Net Salary (already stored)
            $netSalary = $salary->amount;
        @endphp

        <table class="table table-bordered mb-2">
            <tr>
                <th width="70%">Total Salary</th>
                <td class="text-end fw-bold">
                    Rs. {{ number_format($basicSalary, 2) }}
                </td>
            </tr>

            <tr>
                <th>Total Deduction -  <small>{{ $salary->description }}</small></th>
                <td class="text-end text-danger fw-bold">
                    Rs. {{ number_format($deduction, 2) }}
                </td>
            </tr>

            <tr>
                <th class="fs-6">Net Payable Salary</th>
                <td class="text-end text-success fw-bold fs-6">
                    Rs. {{ number_format($netSalary, 2) }}
                </td>
            </tr>
        </table>

        {{-- DESCRIPTION --}}
        <div class="mb-4">
            <span class="label">Remarks:</span><br>
            {{ $salary->description }}
        </div>

        {{-- SIGNATURES --}}
        <div class="row mt-2">
            <div class="col-6">
                _______________________<br>
                Accountant Signature
            </div>
            <div class="col-6 text-end">
                _______________________<br>
                Teacher Signature
            </div>
        </div>

    </div>

</div>

</body>
</html>
