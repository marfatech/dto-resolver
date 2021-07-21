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

use Iterator;
use JsonSerializable;
use MarfaTech\Component\DtoResolver\Exception\InvalidCollectionItemException;

interface CollectionDtoResolverInterface extends Iterator, JsonSerializable
{
    /**
     * Add item to the collection
     *
     * @throws InvalidCollectionItemException When received unsupported collection item
     */
    public function add(array $item): void;

    /**
     * Returns name of the supported collection {@see DtoResolverInterface}
     */
    public static function getItemDtoClassName(): string;

    public function toArray(bool $onlyDefinedData = true): array;
}
