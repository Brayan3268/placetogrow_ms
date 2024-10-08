<?php

use App\Constants\CurrencyTypes;
use App\Constants\SiteTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 30);
            $table->foreignId('category_id')->constrained();
            $table->integer('expiration_time');
            $table->enum('currency_type', CurrencyTypes::toArray());
            $table->enum('site_type', array_column(SiteTypes::cases(), 'name'));
            $table->string('image')->nullable();
            $table->timestamp('enable_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
