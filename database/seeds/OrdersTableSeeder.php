<?php

use App\Order;
use App\Product;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath('database/seeds/orders.csv');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $product = Product::firstOrCreate(['name' => $record['product']]);
            Order::forceCreate([
                'order_id' => $record['order_id'],
                'product_id' => $product->id,
                'quantity' => $record['quantity'],
                'created_at' => $record['created_at'],
                'updated_at' => $record['updated_at']
            ]);
        }
    }
}
