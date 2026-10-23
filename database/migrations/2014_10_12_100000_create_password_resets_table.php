<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->string('email', 191)->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
            return;
        }

        DB::statement('ALTER TABLE `password_resets` MODIFY `email` VARCHAR(191) NOT NULL');
        $indice = DB::select("SHOW INDEX FROM `password_resets` WHERE Key_name = 'password_resets_email_index'");
        if (count($indice) === 0) {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->index('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
