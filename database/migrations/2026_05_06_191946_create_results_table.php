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
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('baji_id')
                ->constrained('bajis')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('result_date')->index();

            $table->string('patti', 3);
            $table->string('single', 1);

            $table->decimal('patti_win_amount', 12, 2)->default(0);
            $table->decimal('single_win_amount', 12, 2)->default(0);
            $table->decimal('cp_win_amount', 12, 2)->default(0);

            $table->integer('patti_win_count')->default(0);
            $table->integer('single_win_count')->default(0);
            $table->integer('cp_win_count')->default(0);

            $table->integer('total_entries')->default(0);

            $table->decimal('total_collection', 12, 2)->default(0);
            $table->decimal('total_liability', 12, 2)->default(0);
            $table->decimal('profit_loss', 12, 2)->default(0);

            $table->foreignId('declared_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['baji_id', 'result_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
