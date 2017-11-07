<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GiftCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
    	Schema::create('gift_card', function (Blueprint $table) {
    		$table->increments('gift_card_id');
    		$table->integer('fk_product');
    		$table->string('gift_card_code',50);
    		$table->enum('status', ['0', '1'])->default('1');
    		$table->double('face_value', 15, 2)->default('0.00');
    		$table->double('balance_amount', 15, 2)->default('0.00');
    		$table->string('sender_name',50);
    		$table->string('sender_email',255);
    		$table->string('recipient_name',50);
    		$table->string('recipient_email',255);
    		$table->text('sender_message')->nullable();
    		$table->enum('is_reward', ['0', '1'])->default('0');
    		$table->enum('fully_redeemed', ['0', '1'])->default('0');
    		$table->dateTime('last_redemption_date');
    		$table->dateTime('date_expiry');
    		$table->integer('created_by')->default(0);
    		$table->integer('updated_by')->default(0);
    		$table->integer('deleted_by')->default(0);
    		$table->timestamps();
    		$table->softDeletes();
    	});
    	
    		Schema::table('gift_card', function (Blueprint $table) {
    		//	$table->foreign('fk_product')->references('product_id')->on('product');
    		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
     Schema::dropIfExists('gift_card');
    }
}
