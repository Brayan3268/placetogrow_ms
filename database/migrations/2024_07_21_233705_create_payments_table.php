<?php

use App\Constants\CurrentTypes;
use App\Constants\PaymentGaeway;
use App\Constants\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('description', 100);
            $table->unsignedBigInteger('amount');
            $table->enum('currency', CurrentTypes::toArray());
            $table->enum('status', PaymentStatus::toArray());
            $table->enum('gateway', PaymentGaeway::toArray());
            $table->unsignedBigInteger('process_identifier')->nullable();
            $table->foreignId('site_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};