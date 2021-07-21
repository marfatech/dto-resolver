<?php

declare(strict_types=1);

namespace MarfaTech\Component\DtoResolver\Tests\Mock;

use ArrayAccess;
use MarfaTech\Component\DtoResolver\Dto\DtoArrayAccessTrait;
use MarfaTech\Component\DtoResolver\Dto\DtoResolverInterface;
use MarfaTech\Component\DtoResolver\Dto\DtoResolverTrait;

class DtoClass implements DtoResolverInterface, ArrayAccess
{
    use DtoResolverTrait;
    use DtoArrayAccessTrait;

    public $publicProperty;
    protected $protectedProperty;
    private $privateProperty;

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
}
