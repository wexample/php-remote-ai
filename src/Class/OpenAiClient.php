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
                capabilities: $this->readCapabilities($item),
            );
        }

        return $models;
    }

    /**
     * OpenAI says nothing of what its models can do; OpenRouter, speaking its
     * api, lists the parameters each one takes and what it reads.
     *
     * @param array<string, mixed> $item
     *
     * @return list<string>|null
     */
    private function readCapabilities(array $item): ?array
    {
        if (! isset($item['supported_parameters'])) {
            return null;
        }

        $parameters = $item['supported_parameters'];
        $capabilities = [RemoteModel::CAPABILITY_COMPLETION];

        if (in_array('tools', $parameters, true)) {
            $capabilities[] = RemoteModel::CAPABILITY_TOOLS;
        }

        if (in_array('temperature', $parameters, true)) {
            $capabilities[] = RemoteModel::CAPABILITY_TEMPERATURE;
        }

        if (in_array('reasoning', $parameters, true) || in_array('include_reasoning', $parameters, true)) {
            $capabilities[] = RemoteModel::CAPABILITY_THINKING;
        }

        if (in_array('image', $item['architecture']['input_modalities'] ?? [], true)) {
            $capabilities[] = RemoteModel::CAPABILITY_VISION;
        }

        return $capabilities;
    }
}
