<?php


namespace BrandonlinU\CvsUpdaterBundler\Controller;


use BrandonlinU\CvsUpdaterBundler\Exception\BadSignatureException;
use BrandonlinU\CvsUpdaterBundler\Model\GithubWebhookRequest;
use JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class GithubController
 * @package BrandonlinU\CvsUpdaterBundler\Controller
 */
class GithubController
{
    /** @var string|null */
    private $secret;

    public function __construct(?string $githubSecret)
    {
        $this->secret = $githubSecret;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function webhookEndpoint(Request $request): Response
    {
        $contentType = $request->getContentType();
        if ($contentType === 'json') {
            $signature = $request->headers->get('X-Hub-Signature');
            $payload = $request->getContent();

            try {
                $githubRequest = new GithubWebhookRequest($payload, $signature, $this->secret);

                return new JsonResponse(['status' => 'ok']);
            } catch (BadSignatureException $e) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], Response::HTTP_UNAUTHORIZED);
            } catch (JsonException $e) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'The payload is not a valid JSON string',
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        throw new BadRequestHttpException("The Github endpoint only supports JSON payloads");
    }
}