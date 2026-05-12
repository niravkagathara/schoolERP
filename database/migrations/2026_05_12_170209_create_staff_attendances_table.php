<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_attendances', function (Blueprint $user) {
            $user->id();
            $user->foreignId('user_id')->constrained()->onDelete('cascade');
            $user->date('date');
            $user->enum('status', ['Present', 'Absent', 'Late', 'Half Day'])->default('Present');
            $user->text('remarks')->nullable();
            $user->timestamps();
            
            $user->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
    }
};
