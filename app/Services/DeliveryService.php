<?php

namespace App\Services;

use App\ProductStock;
use App\ProductsFoodbasketConfig;
use App\DeliveredItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliveryService
{
    public function processDelivery($storeId, $foodboxCount)
    {
        DB::transaction(function () use ($storeId, $foodboxCount) {
            $configurations = ProductsFoodbasketConfig::all();

            foreach ($configurations as $config) {
                $totalQuantity = $config->quantity * $foodboxCount;

                // Lock the product stock row to prevent concurrent modifications.
                $productStock = ProductStock::where([
                    'store_id' => $storeId,
                    'product_id' => $config->product_id,
                ])->lockForUpdate()->first();

                if ($productStock && $productStock->quantity >= $totalQuantity) {
                    // Deduct the required quantity from the stock.
                    $productStock->quantity -= $totalQuantity;
                    $productStock->save();

                    // Update the delivered items table with the delivered quantity.
                    $deliveredItem = DeliveredItem::firstOrNew([
                        'store_id' => $storeId,
                        'product_id' => $config->product_id
                    ]);

                    $deliveredItem->delivered_quantity += $totalQuantity;
                    $deliveredItem->save();
                } 
                else {
                    $errorMessage = 'Insufficient stock for product ID: ' . $config->product_id;
                    Log::warning($errorMessage);
                    // throw new \Exception('Insufficient stock for product ID: ' . $config->product_id);

                }
            }
        });
    }
}
