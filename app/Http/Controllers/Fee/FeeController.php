<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Fee;
use App\Services\FeeService;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function categories()
    {
        $categories = $this->feeService->getAllCategories();
        return view('fees.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:fee_categories,name']);
        $this->feeService->createCategory($request->all());
        return redirect()->back()->with('success', 'Fee category created');
    }

    public function collectIndex(Request $request)
    {
        $query = Student::with(['user', 'class.fees', 'feePayments']);

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('admission_no', $request->search);
        }

        $students = $query->paginate(20);

        return view('fees.collect_index', compact('students'));
    }

    public function collectStudent(Student $student)
    {
        $user = auth()->user();
        if ($user->hasRole('Student') && $user->student && $user->student->id !== $student->id) {
            abort(403, 'Unauthorized access to other student data');
        }

        $student->load(['user', 'class']);
        $availableFees = $this->feeService->getFeesByClass($student->class_id);
        $payments = $this->feeService->getStudentPaymentHistory($student->id);
        
        return view('fees.collect_student', compact('student', 'availableFees', 'payments'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'fee_id' => 'required',
            'amount_paid' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required',
        ]);

        $this->feeService->collectPayment($request->all());

        return redirect()->back()->with('success', 'Payment recorded successfully');
    }

    public function downloadReceipt(\App\Models\FeePayment $payment)
    {
        $user = auth()->user();
        if ($user->hasRole('Student') && $user->student && $user->student->id !== $payment->student_id) {
            abort(403, 'Unauthorized access to other student receipt');
        }

        $payment->load(['student.user', 'student.class', 'fee.category']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('fees.receipt', compact('payment'));
        return $pdf->download('receipt_' . $payment->receipt_no . '.pdf');
    }
}
