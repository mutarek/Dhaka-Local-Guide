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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ad_package_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('image');
            $table->string('mobile_image')->nullable();
            $table->string('destination_url');
            $table->string('placement_position')->index();
            $table->string('target_type')->default('all_posts')->index();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->string('status')->default('draft')->index();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('payment_status')->default('unpaid')->index();
            $table->unsignedInteger('priority')->default(0)->index();
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->boolean('open_in_new_tab')->default(true);
            $table->boolean('nofollow')->default(true);
            $table->boolean('sponsored_label')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'placement_position', 'start_date', 'end_date'], 'ads_active_lookup_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
