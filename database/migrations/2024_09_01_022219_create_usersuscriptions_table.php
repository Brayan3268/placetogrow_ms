<?php

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
            $table->foreignId('suscription_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usersuscriptions');
    }
};
