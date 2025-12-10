<?php
namespace App\Services;

use GuzzleHttp\Client;
use App\Jobs\UpdateFirestoreMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class FirestoreService
{
    protected $client;
    protected $databaseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->databaseUrl = 'https://firestore.googleapis.com/v1/projects/dar1-16df9/databases/(default)/documents/';
    }

    // إضافة الباركود إلى Firestore
    public function addBarcode($barcodeId)
    {
        $url = $this->databaseUrl . 'barcodes/' . $barcodeId;
        
       $now = Carbon::now('Asia/Riyadh');
        
        $response = $this->client->request('PATCH', $url, [
            'json' => [
                'fields' => [
                    // 'message'   => ['stringValue' => 'يحق لك أستلام سلتك الأن'],
                    // 'message'   => ['stringValue' => 'تم التسليم 
                    'message'   => ['stringValue' => 'تم استلام السلة بتاريخ '. $now->format('Y-m-d H:i:s')],
                    'scanned'   => ['booleanValue' => true],
                    'timestamp' => ['timestampValue' => now()->toIso8601String()],
                ]
            ]
        ]);
        // UpdateFirestoreMessage::dispatch($url)->delay(now()->addSeconds(200));
        return json_decode($response->getBody()->getContents());
    }

    // حذف الباركود من Firestore
    public function deleteBarcode($barcodeId)
    {
        $url = $this->databaseUrl . 'barcodes/' . $barcodeId;

        $response = $this->client->request('DELETE', $url);

        return json_decode($response->getBody()->getContents());
    }
}
