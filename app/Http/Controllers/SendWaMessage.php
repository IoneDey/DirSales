<?php
// app/Http/Controllers/SendWaMessage.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendWaMessage extends Controller {

    public function sendMessage($phoneNo, $message) {
        $apiKey = env('WHATSAPP_API_KEY');
        $numberKey = env('WHATSAPP_NUMBER_KEY');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/send_message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                "api_key" => $apiKey,
                "number_key" => $numberKey,
                "phone_no" => $phoneNo,
                "message" => $message
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function sendMessageWithImage($phoneNo, $imageUrl, $message, $separateCaption) {
        $apiKey = env('WHATSAPP_API_KEY');
        $numberKey = env('WHATSAPP_NUMBER_KEY');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/send_image_url',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                "api_key" => $apiKey,
                "number_key" => $numberKey,
                "phone_no" => $phoneNo,
                "url" => $imageUrl,
                "message" => $message,
                "separate_caption" => $separateCaption
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
