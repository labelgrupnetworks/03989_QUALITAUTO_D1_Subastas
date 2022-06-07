<?php
namespace App\libs;

use App\Http\Controllers\PaymentsController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

Class PayPalV2API{

	protected $baseUri;
    protected $clientId;
    protected $clientSecret;

	public function __construct()
    {
		$this->baseUri = config('app.debug', false) ? "https://api-m.sandbox.paypal.com" : "https://api-m.paypal.com/";
        $this->clientId = config('app.paypalClientId');
        $this->clientSecret = config('app.paypalClientSecret');
    }

	public function handlePayment($amount, $merchantID, $currency = 'USD')
	{
		$order = $this->createOrder($amount, $merchantID, $currency);

		$orderLinks = collect($order->links);

        $approve = $orderLinks->where('rel', 'approve')->first();

		session()->put('approvalId', $order->id);

		return redirect($approve->href);
	}

	public function createOrder($value, $merchantID, $currency)
    {
		$url = config('app.url');

        return $this->makeRequest(
            'POST',
            '/v2/checkout/orders',
			[],
            [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    0 => [
						'reference_id' => $merchantID,
						'invoice_id' => $merchantID,
                        'amount' => [
                            'currency_code' => strtoupper($currency),
                            'value' => $value,
                        ]
                    ]
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('paypal_approve'),
                    'cancel_url' => $url.config('app.UP2_cancel'),
                ]
			],
			[],
            $isJsonRequest = true
        );
    }

	public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $isJsonRequest = false)
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

		$headers['Authorization'] = $this->resolveAccessToken();

		try {
			$response = $client->request($method, $requestUrl, [
				$isJsonRequest ? 'json' : 'form_params' => $formParams,
				'headers' => $headers,
				'query' => $queryParams,
			]);
		} catch (\Throwable $th) {
			\Log::error("response to paypal:" . print_r(json_decode($th), true));
			abort(500);
		}


        $response = $response->getBody()->getContents();
		\Log::info("response to paypal:" . print_r(json_decode($response), true));

		return json_decode($response);
    }

    public function resolveAccessToken()
    {
        $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");

        return "Basic {$credentials}";
    }


	public function handleApproval()
    {
        if (session()->has('approvalId')) {
            $approvalId = session()->get('approvalId');

            $payment = $this->capturePayment($approvalId);

			if($payment->status != 'COMPLETED'){
				return redirect(config('app.UP2_cancel'));
			}

			\Log::info("Pago: " . print_r($payment, true));

			$merchantID = $payment->purchase_units[0]->reference_id;
			$amount = $payment->purchase_units[0]->payments->captures[0]->amount->value;

			(new PaymentsController())->pagoDirectoReturn($merchantID, $amount, $payment);

            return redirect(config('app.UP2_return'));
        }

        return redirect(config('app.UP2_cancel'));
    }


	public function capturePayment($approvalId)
    {
        return $this->makeRequest('POST', "/v2/checkout/orders/{$approvalId}/capture", [], [], ['Content-Type' => 'application/json']);
	}

}
