<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usersuscriptions', function (Blueprint $table) {
            $table->string('reference', 100);
            $table->foreignId('user_id')->constrained();
            $table->primary(['reference', 'user_id']);
            $table->foreignId('suscription_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usersuscriptions');
    }
};
