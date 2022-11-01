<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('xiapi_waybill_no');
            $table->string('order_no');
            $table->text('goods_describe');
            $table->string('china_express_information');
            $table->string('recipients');
            $table->string('phone');
            $table->string('address', 1000);
            $table->decimal('collecting_amount', 9, 2);
            $table->bigInteger('internet_channel_id');
            $table->bigInteger('creator_user_id');
            $table->text('comment');
            $table->string('express_sheet_pdf_url', 800);
            $table->bigInteger('tenant_id');
            $table->string('is_dispose');
            $table->string('dispose_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
