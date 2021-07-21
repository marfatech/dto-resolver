<?php

declare(strict_types=1);

namespace MarfaTech\Component\DtoResolver\Tests\Mock;

use MarfaTech\Component\DtoResolver\Dto\DtoResolverInterface;
use MarfaTech\Component\DtoResolver\Dto\DtoResolverTrait;

class TypedPropertyDtoClass implements DtoResolverInterface
{
    use DtoResolverTrait;

    public $publicProperty;
    protected $protectedProperty;
    private $privateProperty;
    public string $publicTypedProperty;
    protected string $protectedTypedProperty;
    private string $privateTypedProperty;

    public function getPublicProperty()
    {
        return $this->publicProperty;
    }

    public function getProtectedProperty()
    {
        return $this->protectedProperty;
    }

    public function getPrivateProperty()
    {
        return $this->privateProperty;
    }

    public function getPublicTypedProperty(): string
    {
        return $this->publicTypedProperty;
    }

    public function getProtectedTypedProperty(): string
    {
        return $this->protectedTypedProperty;
    }

    public function getPrivateTypedProperty(): string
    {
        return $this->privateTypedProperty;
    }
}
