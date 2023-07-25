<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\DocumentTypeEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('public.documents', function (Blueprint $table) {
            $table->id();
            $table->enum('type', DocumentTypeEnum::all());
            $table->string('number', 20);
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
