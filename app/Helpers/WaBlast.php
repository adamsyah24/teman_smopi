<?php

namespace App\Helpers;

class WaBlast
{
    public static function send(array $numbers, string $message)
    {
        $curl = curl_init();

        // Token & Secret Key dari .env
        $token = env('WABLAS_TOKEN', 'QurkGqxZFGGmnjdAhLP5v1fBQtpfLnOE43YhFbjUURy69aE7YHtQpoE');
        $secret_key = env('WABLAS_SECRET', 'ML0SVlth');

        // Payload untuk multi nomor
        $payload = [
            "data" => collect($numbers)->map(function ($phone) use ($message) {
                return [
                    'phone' => $phone,
                    'message' => $message,
                    'isGroup' => 'false',
                ];
            })->toArray()
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: $token.$secret_key",
            "Content-Type: application/json"
        ]);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_URL, "https://sby.wablas.com/api/v2/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            \Illuminate\Support\Facades\Log::error("WA Blast gagal: " . curl_error($curl));
        }

        curl_close($curl);

        $response = json_decode($result, true);

        // simpan di log supaya bisa dicek
        \Illuminate\Support\Facades\Log::info('WA Blast response:', $response ?? ['raw' => $result]);

        if (isset($response['status']) && $response['status'] === true) {
            return true;
        }

        return $result;
    }
}
