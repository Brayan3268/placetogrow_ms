<?php

use App\Constants\CurrencyTypes;
use App\Constants\FrecuencyCollection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description', 500);
            $table->unsignedBigInteger('amount');
            $table->enum('currency_type', CurrencyTypes::toArray());
            $table->integer('expiration_time');
            $table->enum('frecuency_collection', FrecuencyCollection::toArray());
            $table->foreignId('site_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suscriptions');
    }
};
