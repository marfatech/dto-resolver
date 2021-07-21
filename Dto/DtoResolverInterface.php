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

use JsonSerializable;

interface DtoResolverInterface extends JsonSerializable
{
    /**
     * Returns Dto properties as array
     */
    public function toArray(bool $onlyDefinedData = true): array;

    /**
     * Returns Dto initial properties as array
     */
    public function getDefinedProperties(): array;
}
