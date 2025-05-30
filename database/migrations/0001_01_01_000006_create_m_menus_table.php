<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu',255);
            $table->string('description',255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->foreign("created_by")
                ->references("id")
                ->on("users")
                ->cascadeOnUpdate();

            $table->foreign("updated_by")
                ->references("id")
                ->on("users")
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('m_menus');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
