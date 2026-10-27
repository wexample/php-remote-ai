<?php

declare(strict_types=1);

namespace Wexample\PhpRemoteAi\Class;

use DateTimeImmutable;
use Wexample\PhpApi\Common\Client;
use Wexample\PhpRemoteAi\Interface\ModelProviderClientInterface;

/**
 * Anthropic's api (https://api.anthropic.com/v1). Its key travels in an
 * `x-api-key` header, not as a bearer token, with the version of the api the
 * client speaks.
 */
class AnthropicClient extends Client implements ModelProviderClientInterface
{
    public const string BASE_URL = 'https://api.anthropic.com/v1';

    public const string PING_PATH = 'models';

    public const string API_VERSION = '2023-06-01';

    /** The most a page lists. */
    private const int PAGE_SIZE = 1000;

    public function setApiKey(string $apiKey): void
    {
        $this->setDefaultHeader('x-api-key', $apiKey);
        $this->setDefaultHeader('anthropic-version', self::API_VERSION);
    }

    public function listModels(): array
    {
        $models = [];
        $afterId = null;

        do {
            $page = $this->requestJson('GET', 'models', [
                'query' => array_filter(['limit' => self::PAGE_SIZE, 'after_id' => $afterId]),
            ]);

            foreach ($page['data'] ?? [] as $item) {
                $models[] = new RemoteModel(
                    id: $item['id'],
                    label: $item['display_name'] ?? null,
                    createdAt: isset($item['created_at']) ? new DateTimeImmutable($item['created_at']) : null,
                    ownedBy: 'anthropic',
                    raw: $item,
                );
            }

            $afterId = $page['last_id'] ?? null;
        } while (($page['has_more'] ?? false) && $afterId);

        return $models;
    }
}
