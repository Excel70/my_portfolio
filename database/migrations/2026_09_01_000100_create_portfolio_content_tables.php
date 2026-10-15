<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) { $table->boolean('is_admin')->default(false)->after('password'); });

        Schema::create('projects', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->string('slug')->unique(); $table->string('short_description', 500);
            $table->longText('full_description')->nullable(); $table->text('problem')->nullable(); $table->text('solution')->nullable();
            $table->json('features')->nullable(); $table->string('role')->nullable(); $table->string('category'); $table->string('thumbnail')->nullable();
            $table->string('github_url', 2048)->nullable(); $table->string('live_url', 2048)->nullable(); $table->boolean('featured')->default(false);
            $table->string('status')->default('draft'); $table->unsignedInteger('display_order')->default(0); $table->timestamps();
        });
        Schema::create('technologies', function (Blueprint $table) { $table->id(); $table->string('name')->unique(); $table->string('slug')->unique(); $table->timestamps(); });
        Schema::create('project_technology', function (Blueprint $table) { $table->foreignId('project_id')->constrained()->cascadeOnDelete(); $table->foreignId('technology_id')->constrained()->cascadeOnDelete(); $table->primary(['project_id', 'technology_id']); });
        Schema::create('project_images', function (Blueprint $table) { $table->id(); $table->foreignId('project_id')->constrained()->cascadeOnDelete(); $table->string('image'); $table->string('caption')->nullable(); $table->unsignedInteger('display_order')->default(0); $table->timestamps(); });

        Schema::create('skills', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('category'); $table->text('description')->nullable(); $table->unsignedTinyInteger('proficiency')->nullable(); $table->string('icon')->nullable(); $table->unsignedInteger('display_order')->default(0); $table->string('status')->default('active'); $table->timestamps(); });
        Schema::create('services', function (Blueprint $table) { $table->id(); $table->string('title'); $table->text('description'); $table->string('icon')->nullable(); $table->unsignedInteger('display_order')->default(0); $table->string('status')->default('active'); $table->timestamps(); });
        Schema::create('designs', function (Blueprint $table) { $table->id(); $table->string('title'); $table->text('description')->nullable(); $table->string('category'); $table->string('image')->nullable(); $table->boolean('featured')->default(false); $table->unsignedInteger('display_order')->default(0); $table->string('status')->default('draft'); $table->timestamps(); });
        Schema::create('articles', function (Blueprint $table) { $table->id(); $table->string('title'); $table->string('slug')->unique(); $table->string('excerpt', 700)->nullable(); $table->longText('content'); $table->string('featured_image')->nullable(); $table->string('category')->nullable(); $table->string('status')->default('draft'); $table->timestamp('published_at')->nullable(); $table->timestamps(); });
        Schema::create('messages', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('email'); $table->string('subject'); $table->text('message'); $table->string('status')->default('unread'); $table->timestamp('read_at')->nullable(); $table->timestamps(); });
        Schema::create('settings', function (Blueprint $table) { $table->id(); $table->string('key')->unique(); $table->longText('value')->nullable(); $table->timestamps(); });
    }
    public function down(): void
    {
        Schema::dropIfExists('settings'); Schema::dropIfExists('messages'); Schema::dropIfExists('articles'); Schema::dropIfExists('designs'); Schema::dropIfExists('services'); Schema::dropIfExists('skills'); Schema::dropIfExists('project_images'); Schema::dropIfExists('project_technology'); Schema::dropIfExists('technologies'); Schema::dropIfExists('projects'); Schema::table('users', function (Blueprint $table) { $table->dropColumn('is_admin'); });
    }
};
