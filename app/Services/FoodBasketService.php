<?php

namespace App\Services;

use App\ProductsFoodbasketConfig;
use App\Product;
use App\Store;
use App\ProductStock;
use App\DeliveredItem;

class FoodBasketService
{
  public function calculateFoodBaskets($storeId = null)
  {
      $stocksQuery = ProductStock::query();
      $deliveriesQuery = DeliveredItem::query();

      if ($storeId) {
          $stocksQuery->where('store_id', $storeId);
          $deliveriesQuery->where('store_id', $storeId);
      }

      $stocks = $stocksQuery->get();
      $deliveries = $deliveriesQuery->get();
      $configurations = ProductsFoodbasketConfig::with(['product.invoiceProducts' => function ($query) use ($storeId) {
          if ($storeId) {
              $query->whereHas('invoice', function ($q) use ($storeId) {
                  $q->where('store_id', $storeId);
              });
          }
      }])->get();

      //   dd($configurations);
            // Check if there are no configurations yet
      if ($configurations->isEmpty()) {
          return 0;
      }
      // Initialize minBaskets to a very high number
      $minBaskets = PHP_INT_MAX;
  
      // Iterate over each configuration to calculate the maximum number of complete baskets
      foreach ($configurations as $config) {

        //   dd($config->product->invoiceProducts);
          if (!$config->product) { continue; }

          // Calculate the total available quantity of the product from invoices
          // $availableQuantity = $config->product->invoiceProducts->sum('quantity');
          // $availableQuantity = $stocks->where('product_id', $config->product_id)->sum('quantity');

          $stockQuantity = $stocks->where('product_id', $config->product_id)->sum('quantity');
          $deliveredQuantity = $deliveries->where('product_id', $config->product_id)->sum('delivered_quantity');
          // $availableQuantity = $stockQuantity - $deliveredQuantity;
          $availableQuantity = $stockQuantity;
          
          // Calculate the number of possible baskets for this configuration
          // $possibleBaskets = intdiv($availableQuantity, $config->quantity);
          // dd($stockQuantity , $deliveredQuantity,$availableQuantity, $config->quantity);
          $possibleBaskets = intdiv($availableQuantity, $config->quantity);
          
          // Update minBaskets to the minimum value between the current minBaskets and possibleBaskets
          if ($possibleBaskets < $minBaskets) {
              $minBaskets = $possibleBaskets;
          }
      }
      
        // dd($minBaskets);
      return $minBaskets === PHP_INT_MAX ? 0 : $minBaskets;
  }
}
