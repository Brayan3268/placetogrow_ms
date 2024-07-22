<?php

use App\Constants\CurrentTypes;
use App\Constants\PaymentGaeway;
use App\Constants\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
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

            $table->foreignId('site_id');
            $table->foreign('site_id')
                ->references('id')
                ->on('sites');

            $table->foreignId('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
