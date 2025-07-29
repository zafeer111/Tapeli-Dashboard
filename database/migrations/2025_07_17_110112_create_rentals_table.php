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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tournament_id');
            $table->string('team_name');
            $table->string('coach_name');
            $table->string('field_number');
            $table->json('items')->nullable(); // Array of {item_id, quantity}
            $table->json('bundles')->nullable(); // Array of bundle IDs
            $table->text('instructions')->nullable();
            $table->datetime('drop_off_time')->nullable();
            $table->string('promo_code', 50)->nullable();
            $table->string('insurance_option')->nullable(); // 3_day, 7_day, none
            $table->boolean('damage_waiver')->nullable();
            $table->date('rental_date')->nullable();
            $table->string('delivery_assigned_to')->nullable();
            $table->string('payment_method')->nullable(); // stripe, apple_pay, google_pay
            $table->string('payment_status')->default('pending');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->text('return_instruction')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
