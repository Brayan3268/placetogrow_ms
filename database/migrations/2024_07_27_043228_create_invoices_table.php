<?php

use App\Constants\CurrencyTypes;
use App\Constants\InvoiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->unsignedBigInteger('amount');
            $table->enum('currency', CurrencyTypes::toArray());
            $table->enum('status', InvoiceStatus::toArray());
            $table->foreignId('site_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('payment_id')->nullable();
            $table->date('date_created');
            $table->date('date_surcharge');
            $table->unsignedBigInteger('amount_surcharge');
            $table->date('date_expiration');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
