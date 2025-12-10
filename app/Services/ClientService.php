<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClientService
{
    /**
     * Get the first 10 clients without distributions for the current month.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getClientsWithoutDistributions()
    {
 
        return DB::table('clients')
        ->leftJoin('distributions', function ($join) {
            $join->on('clients.id', '=', 'distributions.clients_id')
            ->where('distributions.status', '=', 1);
        })
            ->whereNull('distributions.clients_id')
            ->select('clients.id', 'clients.basket_due_no', 'clients.name')
            ->orderBy('id', 'desc')

            ->get();


    }

    /**
     * Create distributions for clients.
     *
     * @param array $clients The array of clients to create distributions for
     */
    public function createDistributionsForClients($clients)
    {
        $chunkSize = 500; // Define an optimal chunk size
        $now = now(); // Capture the current timestamp to use for all records
        DB::beginTransaction(); // Start a transaction
        try {
             $clients->chunk($chunkSize)->each(function ($chunkedClients) use ($now, &$lastProcessedClientIds) {
                // Collect client IDs for potential logging in case of an error
                $lastProcessedClientIds = $chunkedClients->pluck('id')->toArray();
                $distributions = $chunkedClients->map(function ($client) use ($now) {
                    return [
                        'distribution_date' => $now,
                        'clients_id' => $client->id,
                        'products_id' => 1,
                        'number_of_products' => $client->basket_due_no,
                        'status' => 1,
                        'note' => 'created from system',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                })->toArray();
               DB::table('distributions')->insert($distributions);

            });
            DB::commit(); // Commit the transaction if all chunks were inserted successfully
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction in case of error
            // Log the error along with the last batch of client IDs attempted to be processed
            Log::error('Failed to create distributions for clients. Last client IDs attempted: ' . implode(', ', $lastProcessedClientIds) . ' Error: ' . $e->getMessage());
            // Optionally, rethrow or handle the exception as required by your application logic
        }
    }


    /**
     * Log the execution of a command.
     *
     * @param datatype $operation Description of the operation being logged
     * @param datatype $affectedClients Description of the affected clients
     */
    public function logCommandExecution($operation, $affectedClients)
    {
        DB::table('command_logs')->insert([
            'command_run_date' => now(),
            'operation' => $operation,
            'affected_clients' => $affectedClients,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

}
