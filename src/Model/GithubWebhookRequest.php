<?php


namespace BrandonlinU\CvsUpdaterBundler\Model;


use BrandonlinU\CvsUpdaterBundler\Exception\BadSignatureException;
use JsonException;

class GithubWebhookRequest
{
    /** @var array */
    private $payload;

    /**
     * GithubWebhookRequest constructor.
     * @param string $payload The JSON payload from the Webhook request
     * @param string|null $signature The signature header from the Webhook request
     * @param string|null $secret The shared secret of the signature
     * @throws BadSignatureException
     * @throws JsonException
     */
    public function __construct(string $payload, ?string $signature, ?string $secret)
    {
        if ($signature === null && $secret !== null) { throw new BadSignatureException('The payload was expected to have a signature'); }

        if ($signature !== null) {
            $signatureParams = explode('=', $signature);

            if(!hash_equals($signatureParams[1], hash_hmac($signatureParams[0], $payload, $secret))) {
                throw new BadSignatureException("The signature does not match with the payload");
            }
        }

        $this->payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
    }
}