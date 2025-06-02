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
        Schema::create('user_token', function (Blueprint $table) {
                        $table->increments('id');
            $table->integer('token_no');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp("expire_at")->nullable();
            $table->index(["user_id"], 'fk_user_token_users_idx');

            $table->foreign('user_id', 'fk_user_token_users_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_token');
    }
};
