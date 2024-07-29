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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'id')->onUpdate('cascade')->onDelete('cascade')->index('tasks_user_id_index');
            $table->foreignId('parent_id')->nullable()->constrained(table: 'tasks', indexName: 'id')->onUpdate('cascade')->onDelete('set null')->index('tasks_parent_id_index');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'done'])->default('todo')->index();
            $table->unsignedTinyInteger('priority')->default(1)->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
