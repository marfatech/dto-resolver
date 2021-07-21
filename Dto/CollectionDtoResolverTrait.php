<?php

declare(strict_types=1);

/*
 * This file is part of the DtoResolver package.
 *
 * (c) MarfaTech <https://marfa-tech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MarfaTech\Component\DtoResolver\Dto;

use MarfaTech\Component\DtoResolver\Exception\FieldForIndexNotFoundException;
use MarfaTech\Component\DtoResolver\Exception\InvalidCollectionItemException;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function key;
use function next;
use function reset;

trait CollectionDtoResolverTrait
{
    /**
     * @var DtoResolverInterface[]
     */
    private $collection = [];

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var string|null
     */
    private $indexBy;

    /**
     * @var string|null
     */
    private $className;

    public function __construct(?OptionsResolver $resolver = null, ?string $indexBy = null)
    {
        $this->optionsResolver = $resolver;
        $this->indexBy = $indexBy;

        $this->className = self::getItemDtoClassName();

        if (!is_subclass_of($this->className, DtoResolverInterface::class)) {
            throw new InvalidCollectionItemException(DtoResolverInterface::class);
        }
    }

    /**
     * Returns name of the supported collection {@see DtoResolverInterface}
     */
    abstract public static function getItemDtoClassName(): string;

    public function add(array $item): void
    {
        $collectionItem = new $this->className($item, $this->optionsResolver);

        if ($this->indexBy === null) {
            $this->collection[] = $collectionItem;

            return;
        }

        $key = $item[$this->indexBy] ?? null;

        if (null === $key) {
            throw new FieldForIndexNotFoundException($this->indexBy);
        }

        $this->collection[$key] = $collectionItem;
    }

    public function toArray(bool $onlyDefinedData = true): array
    {
        $result = [];

        foreach ($this->collection as $key => $item) {
            $result[$key] = $item->toArray($onlyDefinedData);
        }

        return $result;
    }

    public function next(): void
    {
        next($this->collection);
    }

    public function current(): DtoResolverInterface
    {
        return $this->collection[$this->key()];
    }

    public function rewind(): void
    {
        reset($this->collection);
    }

    /**
     * @return int|string|null
     */
    public function key()
    {
        return key($this->collection);
    }

    public function valid(): bool
    {
        return isset($this->collection[$this->key()]);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
