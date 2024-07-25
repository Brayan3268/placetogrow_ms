<?php

use App\Constants\CurrencyTypes;
use App\Constants\PaymentGateway;
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
            $table->enum('locale', array_column(LocalesTypes::cases(), 'name'));
            $table->string('reference')->unique();
            $table->string('description', 100);
            $table->unsignedBigInteger('amount');
            $table->enum('currency', CurrencyTypes::toArray());
            $table->enum('status', PaymentStatus::toArray());
            $table->enum('gateway', PaymentGateway::toArray());
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
