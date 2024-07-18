<?php

use App\Constants\FieldTypes;
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
        Schema::create('fieldspaysites', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('name_user_see', 40);
            $table->enum('type', array_column(FieldTypes::cases(), 'name'));
            $table->boolean('is_optional');
            $table->string('values', 80)->nullable(true);
            $table->foreignId('site_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fieldspaysites');
    }
};
