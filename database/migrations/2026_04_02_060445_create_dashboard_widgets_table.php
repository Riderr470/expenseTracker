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
        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('key');
            $table->string('type');        // "bar_chart", "pie_chart", "stat_card"
            $table->json('config');        // {"title": "Sales", "color": "#3b82f6"}
            $table->unsignedSmallInteger('position')->default(0); // drag-to-reorder
            $table->boolean('is_visible')->default(true);         // soft hide without deleting
            $table->timestamps();

            // One widget key per user
            $table->unique(['user_id', 'key']);
            // Fast ordered fetch
            $table->index(['user_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_widgets');
    }
};
