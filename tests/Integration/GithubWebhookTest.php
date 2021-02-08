<?php


namespace BrandonlinU\CvsUpdaterBundler\Tests\Integration;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class GithubWebhookTest extends WebTestCase
{
    public function testEndpoint(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_POST, '/update/github', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], '{}');
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/json');
        self::assertEquals('{"status":"ok"}', $response->getContent());
    }
}