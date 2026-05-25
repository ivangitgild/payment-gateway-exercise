<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Luigel\Paymongo\Signer\Signer;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PaymongoWebhook
{
    /**
     * Handle an incoming request.
     *
     * @throws \Illuminate\Routing\Exceptions\InvalidSignatureException
     */
    public function handle(Request $request, Closure $next)
    {

        $payload = $this->headerPayload($request);
        $body = $request->all();

        if (!isset($body['data'])) throw new UnprocessableEntityHttpException('Malformed data');

        $event = $body['data']['attributes']['type'];


        if ($payload) {
            $signature = $this->signature($request, $payload['t'], $event);
            $key = config('paymongo.mode') === 'live' ? 'li' : 'te';

            if ($signature == $payload[$key]) {
                $response = $next($request);

                return $response;
            }
        }

        throw new InvalidSignatureException();
    }

    /**
     * Get header signature payload.
     */
    public function headerPayload(Request $request): array
    {
        $payload = $request->header('paymongo-signature');

        if ($payload === null) {
            return [];
        }

        return collect(explode(',', $payload))
            ->mapWithKeys(function ($val) {
                $pair = explode('=', $val);

                return [$pair[0] => $pair[1]];
            })
            ->filter()
            ->all();
    }

    /**
     * Get webhook signature.
     */
    public function signature(Request $request, string|int $timestamp, string $event = null): string
    {
        // \Log::info(config('paymongo.webhook_secret_key'));

        return $this->calculateSignature(
            $timestamp,
            $request->getContent(),
            config('services.paymongo.webhook_secret_key')
        );
    }

    private function calculateSignature($timestamp, $contentBody, $secret) {
        return hash_hmac('sha256', $timestamp.'.'.$contentBody, $secret);
    }
}
