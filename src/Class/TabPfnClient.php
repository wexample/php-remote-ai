<?php

declare(strict_types=1);

namespace Wexample\PhpRemoteAi\Class;

use Wexample\PhpApi\Common\Client;
use Wexample\PhpRemoteAi\Interface\ModelProviderClientInterface;

/**
 * Prior Labs' TabPFN api (https://api.priorlabs.ai): tabular prediction,
 * a model version fitted on a training set then asked for predictions. Its
 * key is a bearer token; the limits route lists the versions the key may use.
 */
class TabPfnClient extends Client implements ModelProviderClientInterface
{
    public const string BASE_URL = 'https://api.priorlabs.ai';

    public const string PING_PATH = 'tabpfn/get_model_limits';

    /** The model versions, each with the most it takes: rows, columns, classes. */
    public function listModels(): array
    {
        $models = [];

        foreach ($this->requestJson('GET', self::PING_PATH)['model_limits'] ?? [] as $version => $limits) {
            $models[] = new RemoteModel(
                id: $version,
                label: 'TabPFN '.$version,
                ownedBy: 'priorlabs',
                raw: $limits,
            );
        }

        return $models;
    }
}
