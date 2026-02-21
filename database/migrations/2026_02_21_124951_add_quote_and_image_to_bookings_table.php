<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('image')->nullable();

            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->integer('quoted_duration')->nullable();

            $table->string('quote_status')->default('pending');

            $table->index('status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
               $table->dropIndex(['status']);
        $table->dropIndex(['payment_status']);

        $table->dropColumn(['image','quoted_price','quoted_duration','quote_status']);
        });
    }
};
