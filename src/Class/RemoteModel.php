<?php

declare(strict_types=1);

namespace Wexample\PhpRemoteAi\Class;

use DateTimeImmutable;

/**
 * A model as its provider describes it: what each provider says, under one
 * shape. What a provider does not say is left null.
 */
final readonly class RemoteModel
{
    public const string CAPABILITY_COMPLETION = 'completion';

    public const string CAPABILITY_EMBEDDING = 'embedding';

    /** Takes a sampling temperature. */
    public const string CAPABILITY_TEMPERATURE = 'temperature';

    public const string CAPABILITY_THINKING = 'thinking';

    public const string CAPABILITY_TOOLS = 'tools';

    public const string CAPABILITY_VISION = 'vision';

    /**
     * @param string $id The name the provider is called with (`gpt-4o-mini`, `llama3.2:latest`).
     * @param array<string, mixed> $raw What the provider answered, whole.
     * @param list<string>|null $capabilities What the model can do, among the
     *     CAPABILITY_* names, when the provider says it; null when it does not.
     */
    public function __construct(
        public string $id,
        public ?string $label = null,
        public ?DateTimeImmutable $createdAt = null,
        public ?string $ownedBy = null,
        public ?int $size = null,
        public array $raw = [],
        public ?array $capabilities = null,
    ) {
    }
}
