<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('like_counters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('likeable_id')->nullable();
            $table->uuid('likeable_type')->nullable();
            $table->integer('count')->unsigned()->default(0);
            $table->timestamps();

            $table->unique([
                'likeable_type',
                'likeable_id',
            ], 'like_counter_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('like_counters');
    }
}
