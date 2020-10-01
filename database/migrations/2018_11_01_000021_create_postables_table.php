<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostablesTable extends Migration
{
    public function up()
    {
        Schema::create('postables', function (Blueprint $table) {
            $table->primary(['post_id','postable_id', 'postable_type']);
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('postable_id');
            $table->string('postable_type');
            
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postables');
    }
}