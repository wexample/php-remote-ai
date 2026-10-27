<?php

declare(strict_types=1);

namespace Wexample\PhpRemoteAi\Class;

use DateTimeImmutable;
use Wexample\PhpApi\Common\Client;
use Wexample\PhpRemoteAi\Interface\ModelProviderClientInterface;

/**
 * OpenAI's api (https://api.openai.com/v1), and any service speaking it at
 * its own url — Mistral, Groq, OpenRouter, a llama.cpp or vLLM server: the
 * base url is the client's.
 */
class OpenAiClient extends Client implements ModelProviderClientInterface
{
    public const string BASE_URL = 'https://api.openai.com/v1';

    public const string PING_PATH = 'models';

    public function listModels(): array
    {
        $models = [];

        foreach ($this->requestJson('GET', 'models')['data'] ?? [] as $item) {
            $models[] = new RemoteModel(
                id: $item['id'],
                createdAt: isset($item['created']) ? (new DateTimeImmutable())->setTimestamp((int) $item['created']) : null,
                ownedBy: $item['owned_by'] ?? null,
                raw: $item,
            );
        }

        return $models;
    }
}
