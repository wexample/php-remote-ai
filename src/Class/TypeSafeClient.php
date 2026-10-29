<?php

declare(strict_types=1);

namespace Wexample\PhpRemoteAi\Class;

use DateTimeImmutable;
use Wexample\PhpApi\Common\Client;
use Wexample\PhpRemoteAi\Interface\ModelProviderClientInterface;

/**
 * TypeSafe AI's api (https://api.typesafe.ai/v1): Jev, a decision model
 * answering typed questions on a state with probabilities, not text. Its
 * key is a bearer token; its models list is its own, not OpenAI's.
 */
class TypeSafeClient extends Client implements ModelProviderClientInterface
{
    public const string BASE_URL = 'https://api.typesafe.ai/v1';

    public const string PING_PATH = 'models';

    /** The model versions the key may ask, `jev-latest` and the others. */
    public function listModels(): array
    {
        $models = [];

        foreach ($this->requestJson('GET', self::PING_PATH)['models'] ?? [] as $item) {
            $models[] = new RemoteModel(
                id: $item['name'],
                label: $item['description'] ?? null,
                ownedBy: 'typesafe',
                createdAt: isset($item['release_date']) ? new DateTimeImmutable($item['release_date']) : null,
                raw: $item,
            );
        }

        return $models;
    }
}
