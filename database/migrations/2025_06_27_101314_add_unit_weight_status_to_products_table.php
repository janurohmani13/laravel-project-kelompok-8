<?php

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
    Schema::table('products', function (Blueprint $table) {
        $table->string('unit', 50)->nullable()->after('stock');
        $table->decimal('weight', 8, 2)->nullable()->after('unit');
        $table->boolean('is_active')->default(true)->after('weight');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['unit', 'weight', 'is_active']);
    });
}

};
