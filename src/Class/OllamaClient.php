<?php

declare(strict_types=1);

namespace Wexample\PhpRemoteAi\Class;

use DateTimeImmutable;
use Wexample\PhpApi\Common\Client;
use Wexample\PhpRemoteAi\Interface\ModelProviderClientInterface;

/**
 * An Ollama server (http://<host>:11434), which holds the models it pulled.
 * It asks no key.
 */
class OllamaClient extends Client implements ModelProviderClientInterface
{
    public const string PING_PATH = 'api/version';

    /** The models the server holds, pulled and ready to run. */
    public function listModels(): array
    {
        $models = [];

        foreach ($this->requestJson('GET', 'api/tags')['models'] ?? [] as $item) {
            $models[] = new RemoteModel(
                id: $item['name'],
                label: $item['details']['parameter_size'] ?? null,
                createdAt: isset($item['modified_at']) ? new DateTimeImmutable($item['modified_at']) : null,
                size: isset($item['size']) ? (int) $item['size'] : null,
                raw: $item,
            );
        }

        return $models;
    }
}
