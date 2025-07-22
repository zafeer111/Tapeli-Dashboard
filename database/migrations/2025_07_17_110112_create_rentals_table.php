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
            $table->unsignedBigInteger('tournament_id');
            $table->string('team_name');
            $table->string('coach_name');
            $table->string('field_number');
            $table->json('items')->nullable();
            $table->json('bundles')->nullable();
            $table->text('instructions')->nullable();
            $table->datetime('drop_off_time')->nullable();
            $table->string('promo_code', 50)->nullable();
            $table->enum('insurance_option', ['3_day', '7_day', 'none'])->nullable();
            $table->boolean('damage_waiver')->nullable();
            $table->date('rental_date')->nullable();
            $table->string('delivery_assigned_to')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('status')->default(\App\Enums\RentalStatus::PENDING->value);
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
