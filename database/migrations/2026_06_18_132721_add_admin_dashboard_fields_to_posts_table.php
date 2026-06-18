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
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->after('category_id')->constrained('users')->nullOnDelete();
            $table->json('gallery_images')->nullable()->after('featured_image');
            $table->string('image_alt')->nullable()->after('gallery_images');
            $table->string('image_caption')->nullable()->after('image_alt');
            $table->unsignedInteger('views_count')->default(0)->after('status');
            $table->unsignedInteger('reading_time')->default(1)->after('views_count');
            $table->boolean('is_featured')->default(false)->index()->after('reading_time');
            $table->boolean('is_trending')->default(false)->index()->after('is_featured');
            $table->string('focus_keyword')->nullable()->after('canonical_url');
            $table->boolean('auto_share_to_facebook')->default(false)->after('schema');
            $table->string('facebook_share_status')->default('not_shared')->index()->after('auto_share_to_facebook');
            $table->string('facebook_post_id')->nullable()->after('facebook_share_status');
            $table->text('facebook_share_error')->nullable()->after('facebook_post_id');
            $table->timestamp('facebook_shared_at')->nullable()->after('facebook_share_error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('author_id');
            $table->dropColumn([
                'gallery_images',
                'image_alt',
                'image_caption',
                'views_count',
                'reading_time',
                'is_featured',
                'is_trending',
                'focus_keyword',
                'auto_share_to_facebook',
                'facebook_share_status',
                'facebook_post_id',
                'facebook_share_error',
                'facebook_shared_at',
            ]);
        });
    }
};
