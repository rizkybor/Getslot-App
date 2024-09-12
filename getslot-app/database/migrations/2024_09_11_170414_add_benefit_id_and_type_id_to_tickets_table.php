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
        Schema::table('tickets', function (Blueprint $table) {
            //
            $table->foreignId('type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('benefit_id')->constrained()->cascadeOnDelete();
            $table->string('like');
            $table->date('event_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Hapus foreign key dan kolom terkait
            $table->dropForeign(['type_id']);
            $table->dropForeign(['benefit_id']);
            $table->dropColumn(['type_id', 'benefit_id', 'like', 'event_date']);
        });
    }
};
