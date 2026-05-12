<!DOCTYPE html>
<html>
<head>
    <title>Fee Receipt - {{ $payment->receipt_no }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .receipt-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .receipt-table th, .receipt-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .footer { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SCHOOL ERP SYSTEM</h1>
        <h3>FEE PAYMENT RECEIPT</h3>
    </div>

    <table class="receipt-table">
        <tr>
            <th>Receipt No:</th>
            <td>{{ $payment->receipt_no }}</td>
            <th>Date:</th>
            <td>{{ $payment->payment_date }}</td>
        </tr>
        <tr>
            <th>Student Name:</th>
            <td>{{ $payment->student->user->name }}</td>
            <th>Admission No:</th>
            <td>{{ $payment->student->admission_no }}</td>
        </tr>
        <tr>
            <th>Class:</th>
            <td>{{ $payment->student->class->name }}</td>
            <th>Payment Method:</th>
            <td>{{ $payment->payment_method }}</td>
        </tr>
    </table>

    <table class="receipt-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $payment->fee->category->name }} Payment</td>
                <td style="text-align: right;">${{ number_format($payment->amount_paid, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: right;">TOTAL PAID:</th>
                <th style="text-align: right;">${{ number_format($payment->amount_paid, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>_______________________</p>
        <p>Authorized Signature</p>
    </div>
</body>
</html>
