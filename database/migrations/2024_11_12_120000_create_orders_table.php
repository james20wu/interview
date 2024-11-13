<?php

use App\Services\OrderService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            $table->string('id', 100)->primary();
            $table->string('name', 255)->nullable()->comment('名稱');
            $table->json('address')->nullable(false)->comment('地址');
            $table->integer('price')->nullable(false)->comment('價格');
            $table->string('currency', 10)->nullable(false)->comment('幣別');
            $table->timestamps();
        });

        foreach (OrderService::getCurrencyList() as $currency)
        {
            $currency = Str::lower($currency);

            Schema::create('orders_'. $currency, function (Blueprint $table) {
                $table->string('id', 100)->primary();
                $table->string('name', 255)->nullable()->comment('名稱');
                $table->json('address')->nullable(false)->comment('地址');
                $table->integer('price')->nullable(false)->comment('價格');
                $table->string('currency', 10)->nullable(false)->comment('幣別');
                $table->timestamps();
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
        Schema::dropIfExists('orders');

        foreach (OrderService::getCurrencyList() as $currency) {
            $currency = Str::lower($currency);
            Schema::dropIfExists('orders_'. $currency);
        }
    }
}
