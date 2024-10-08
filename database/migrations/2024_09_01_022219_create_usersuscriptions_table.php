<?php

use App\Constants\SuscriptionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usersuscriptions', function (Blueprint $table) {
            $table->uuid('reference');
            $table->foreignId('user_id')->constrained();
            $table->primary(['reference', 'user_id']);
            $table->integer('expiration_time');
            $table->integer('days_until_next_payment')->nullable();
            $table->foreignId('suscription_id')->constrained()->onDelete('cascade');
            $table->enum('status', SuscriptionStatus::toArray())->default(SuscriptionStatus::PENDING);
            $table->string('request_id', 50)->nullable();
            $table->string('token', 70)->nullable();
            $table->string('sub_token', 50)->nullable();
            $table->json('additional_data')->nullable();
            $table->date('date_try')->nullable();
            $table->integer('attempts_realised')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usersuscriptions');
    }
};
