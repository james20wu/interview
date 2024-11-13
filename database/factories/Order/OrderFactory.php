<?php

namespace Database\Factories\Order;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'id'       => 'A1000000',
            'name'     => 'Melody Holiday Inn',
            'address'  => [
                'city'     => 'taipei-city',
                'district' => 'da-an-district',
                'street'   => 'fuxing-south-road'
            ],
            'price'    => '2050',
            'currency' => 'TWD'
        ];
    }
}
