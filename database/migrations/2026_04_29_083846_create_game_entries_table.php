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
        Schema::create('game_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agent_id')
                ->constrained('agents')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('baji_id')
                ->constrained('bajis')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('game_number', 20);
            $table->decimal('amount', 12, 2);

            $table->foreignId('entry_user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            $table->index(['agent_id', 'baji_id']);
            $table->index('entry_user_id');
            $table->index('game_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_entries');
    }
};
