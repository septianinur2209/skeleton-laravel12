<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_role', function (Blueprint $table) {
            $table->id();
            $table->string('role',255);
            $table->string('name',255);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

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
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('s_role');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
