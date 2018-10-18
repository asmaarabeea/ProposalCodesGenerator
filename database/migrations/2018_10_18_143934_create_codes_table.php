<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('proposal_type');
            $table->string('technical_approval');
            $table->string('sales_agent');
            $table->string('proposal_number');
            $table->string('client_source');
            $table->string('client_name')->nullable();
            $table->date('proposal_date')->nullable();
            $table->string('proposal_value')->nullable();
            $table->integer("user_id")->unsigned(); //create
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('codes');
    }
}
