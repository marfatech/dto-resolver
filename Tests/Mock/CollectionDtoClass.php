<?php

declare(strict_types=1);

namespace MarfaTech\Component\DtoResolver\Tests\Mock;

use MarfaTech\Component\DtoResolver\Dto\CollectionDtoResolverInterface;
use MarfaTech\Component\DtoResolver\Dto\CollectionDtoResolverTrait;

class CollectionDtoClass implements CollectionDtoResolverInterface
{
    use CollectionDtoResolverTrait;

    public static function getItemDtoClassName(): string
    {
        return DtoClass::class;
    }
}
