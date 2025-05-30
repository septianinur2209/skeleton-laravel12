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
        Schema::create('s_menu_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('menu_id');
            $table->boolean('show');
            $table->boolean('create');
            $table->boolean('edit');
            $table->boolean('delete');
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

            $table->foreign('role_id')
                ->references('id')
                ->on('s_role')
                ->cascadeOnUpdate();

            $table->foreign('menu_id')
                ->references('id')
                ->on('m_menus')
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
        Schema::dropIfExists('s_menu_access');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
