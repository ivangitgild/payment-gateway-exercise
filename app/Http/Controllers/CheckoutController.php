<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CheckoutController extends Controller {

    public function store(Request $request) {

        // @todo: Please supply any details
        $payload = $this->getRequestData();

        $secretKey = ''; // @todo: retrieve secret key from ENV
        $apiUrl = ''; // @todo: Retrieve paymongo api url from ENV
        $checkoutSessionUrl = 'checkout_sessions';

        $client = new Client();

        $client->setGuzzleOptions([
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ],
            'auth' => [$secretKey, ''],
            'json' => $payload,
        ]);

        $response = $client->request('POST', $apiUrl . '/' . $checkoutSessionUrl);

        $result = $this->parseToArray((string) $response->getBody());

        return $this->response($result);
    }

    private function response($rawResponse) {
        return [
            'redirect_url' => $rawResponse['data']['attributes']['checkout_url'],
            'checkout_id' => $rawResponse['data']['id']
        ];
    }


    private function parseToArray($data) {
        return json_decode($data);
    }
    private function getRequestData($code, $totalAmount, $customerDetail, $totalServiceCharge, $method) {

        return [
            'data' => [
                'attributes' => [
                    'cancel_url' =>  config('services.payment_redirects.cancelled') . '/' . $code,
                    'line_items' => [
                        [
                            'amount' => (int) number_format($totalAmount * 100, 0, '', ''),
                            'currency' => 'PHP',
                            'name' => 'Total Fare',
                            'quantity' => 1
                        ],
                        [
                            'amount' => (int) number_format($totalServiceCharge * 100, 0, '', ''),
                            'currency' => 'PHP',
                            'name' => 'Service Charge',
                            'quantity' => 1
                        ]
                    ],
                    'billing' => [
                        'name' =>  $customerDetail['fullname'],
                        'email' => $customerDetail['email'],
                        'phone' => $customerDetail['phone']
                    ],
                    'payment_method_types' => $method,
                    'reference_number' => $code,
                    'send_email_receipt' => true,
                    'show_description' => false,
                    'success_url' => config('services.payment_redirects.success') . '/' . $code,
                ]
            ]
        ];
    }
}
