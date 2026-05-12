<?php

namespace App\Services;

use App\Models\FeeCategory;
use App\Models\Fee;
use App\Models\FeePayment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class FeeService
{
    public function getAllCategories()
    {
        return FeeCategory::all();
    }

    public function createCategory(array $data)
    {
        return FeeCategory::create($data);
    }

    public function getFeesByClass($classId)
    {
        return Fee::with('category')->where('class_id', $classId)->get();
    }

    public function collectPayment(array $data)
    {
        return DB::transaction(function () use ($data) {
            $payment = FeePayment::create([
                'student_id' => $data['student_id'],
                'fee_id' => $data['fee_id'],
                'amount_paid' => $data['amount_paid'],
                'payment_date' => $data['payment_date'],
                'payment_method' => $data['payment_method'],
                'receipt_no' => 'REC-' . time() . '-' . rand(100, 999),
                'remarks' => $data['remarks'] ?? null,
            ]);

            return $payment;
        });
    }

    public function getStudentPaymentHistory($studentId)
    {
        return FeePayment::with(['fee.category'])
            ->where('student_id', $studentId)
            ->orderBy('payment_date', 'desc')
            ->get();
    }
}
