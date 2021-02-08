<?php


namespace BrandonlinU\CvsUpdaterBundler\Tests\Unit\Controller;


use BrandonlinU\CvsUpdaterBundler\Controller\GithubController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GithubControllerTest extends TestCase
{
    private const SIGNATURE_ALGORITHMS = ['sha1', 'sha256', 'sha512'];

    public function testPlainRequestEndpoint(): void
    {
        $controller = new GithubController(null);
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'plain/text'], '');

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('The Github endpoint only supports JSON payloads');
        $controller->webhookEndpoint($request);
    }

    public function testEndpointWithMalformedJson(): void
    {
        $controller = new GithubController(null);
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], '{this_is: "a malformed JSON"}');
        $response = $controller->webhookEndpoint($request);

        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertEquals('{"status":"error","message":"The payload is not a valid JSON string"}', $response->getContent());
    }

    public function testEndpointWithoutSignature(): void
    {
        $controller = new GithubController(null);
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $controller->webhookEndpoint($request);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertEquals('{"status":"ok"}', $response->getContent());
    }

    public function testEndpointWithValidSignature(): void
    {
        $data = '{}';
        $secret = 'ThisIsASecret';
        $controller = new GithubController($secret);

        foreach (self::SIGNATURE_ALGORITHMS as $algorithm) {
            $signature = hash_hmac($algorithm, $data, $secret);
            $request = new Request([], [], [], [], [], [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_HUB_SIGNATURE' => "$algorithm=$signature",
            ], $data);
            $response = $controller->webhookEndpoint($request);

            self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
            self::assertEquals('{"status":"ok"}', $response->getContent());
        }
    }

    public function testEndpointWithInvalidSignature(): void
    {
        $controller = new GithubController('WrongSecret');
        $data = '{}';

        foreach (self::SIGNATURE_ALGORITHMS as $algorithm) {
            $signature = hash_hmac($algorithm, $data, 'ThisIsASecret');
            $request = new Request([], [], [], [], [], [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_HUB_SIGNATURE' => "$algorithm=$signature",
            ], $data);
            $response = $controller->webhookEndpoint($request);

            self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
            self::assertEquals('{"status":"error","message":"The signature does not match with the payload"}', $response->getContent());
        }
    }

    public function testEndpointExpectsSignature(): void
    {
        $controller = new GithubController('ThisIsASecret');
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $controller->webhookEndpoint($request);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertEquals('{"status":"error","message":"The payload was expected to have a signature"}', $response->getContent());
    }
}