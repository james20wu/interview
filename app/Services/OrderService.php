<?php

namespace App\Services;

use App\Models\Order\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Exception;

class OrderService
{
    const CURRENCY_TWD = 'TWD';
    const CURRENCY_USD = 'USD';
    const CURRENCY_JPY = 'JPY';
    const CURRENCY_RMB = 'RMB';
    const CURRENCY_MYR = 'MYR';

    public function createOrder($data)
    {
        return DB::transaction(function () use ($data) {
            $data = collect($data)->only([
                'id',
                'name',
                'address',
                'price',
                'currency',
            ])->toArray();
            $order = Order::create($data);

            // 根據不同的 currency 存入不同的資料表
            self::getCurrencyOrderModel($data['currency'])->create($data);

            return $order;
        });
    }


    /**
     * 取得 Currency Order Model
     * @param $currency
     * @return Order
     */
    public function getCurrencyOrderModel($currency)
    {
        if (!in_array($currency, self::getCurrencyList())) {
            throw new Exception('Invalid currency');
        }

        $modelClass = 'App\\Models\\Order\\Order'. $currency;
        return new $modelClass();
    }

    /**
     * 根據id取得order
     * @param $id
     * @return mixed
     */
    public function getOrderById($id)
    {
        return Order::firstWhere('id', $id);
    }
    /**
     * 訂單 幣別
     * @return array
     */
    public static function getCurrencyList(): array
    {
        return [
            self::CURRENCY_TWD,
            self::CURRENCY_USD,
            self::CURRENCY_JPY,
            self::CURRENCY_RMB,
            self::CURRENCY_MYR,
        ];
    }

}
