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
        Schema::create('wamessages', function (Blueprint $table) {
            $table->id();
            $table->string('sender', 255);
            $table->string('receiver', 255);
            $table->text('message');
            $table->boolean('status')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wamessages');
    }
};
