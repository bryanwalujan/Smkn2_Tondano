<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_classroom_subject', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
            $table->string('subject_name')->after('classroom_id');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_classroom_subject', function (Blueprint $table) {
            $table->dropColumn('subject_name');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        });
    }
};