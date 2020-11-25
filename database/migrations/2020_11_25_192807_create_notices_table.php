<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    // 提示通知表
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('fangowner_id')->comment('房东ID');
            $table->unsignedInteger('renting_id')->default(1)->comment('租客ID');
            $table->dateTime('dtime')->nullable()->comment('时间');
            $table->string('cnt',500)->default('')->comment('内容');
            $table->enum('status',['0','1'])->default('0')->comment('状态0未看，1已看或过期');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notices');
    }
}
