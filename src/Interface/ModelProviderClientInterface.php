<?php

declare(strict_types=1);

namespace Wexample\PhpRemoteAi\Interface;

use Wexample\PhpApi\Exceptions\ApiException;
use Wexample\PhpRemoteAi\Class\RemoteModel;

/**
 * A service serving AI models — an api called with a key, an Ollama server —,
 * asked which models it has.
 */
interface ModelProviderClientInterface
{
    /**
     * Every model the provider offers the client's key, or holds locally.
     *
     * @return list<RemoteModel>
     *
     * @throws ApiException
     */
    public function listModels(): array;
}
