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

use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_combine;
use function array_intersect_key;
use function array_keys;
use function get_class;
use function get_class_vars;
use function get_object_vars;
use function lcfirst;
use function property_exists;
use function str_replace;
use function strpos;
use function ucwords;

trait DtoResolverTrait
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var string[]
     */
    private $definedProperties = [];

    /**
     * @throws ExceptionInterface
     */
    public function __construct(array $data = [], ?OptionsResolver $resolver = null)
    {
        $properties = $this->getProperties();

        if ($resolver) {
            $properties = array_unique(array_merge($properties, $resolver->getDefinedOptions()));
        }

        $this->definedProperties = array_combine($properties, $properties);

        $resolver = $resolver ?? new OptionsResolver();
        $resolver->setDefined($properties);

        $this->configureOptions($resolver);

        $this->optionsResolver = $resolver;

        $this->resolve($data);
    }

    /**
     * @return string[]
     */
    public function getDefinedProperties(): array
    {
        return $this->definedProperties;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * TODO toArray with $onlyDefinedData = false returns only defined data, this bug need fix
     */
    public function toArray(bool $onlyDefinedData = true): array
    {
        $data = $this->getObjectVars();

        if ($onlyDefinedData) {
            $data = $this->getOnlyDefinedData($data);
        }

        return $data;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
    }

    protected function getProperties(): array
    {
        $data = $this->getClassVars();

        return array_keys($data);
    }

    protected function getObjectVars(): array
    {
        $data = get_object_vars($this);

        unset($data['optionsResolver'], $data['definedProperties']);

        return $data;
    }

    protected function getClassVars(): array
    {
        $data = get_class_vars(get_class($this));

        unset($data['optionsResolver'], $data['definedProperties']);

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getOnlyDefinedData(array $data): array
    {
        return array_intersect_key($data, $this->definedProperties);
    }

    /**
     * @return OptionsResolver
     */
    protected function getOptionResolver(): OptionsResolver
    {
        return $this->optionsResolver;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function normalizeDefinedKey(string $key): string
    {
        $needNormalization = false;

        if (strpos($key, '_') !== false) {
            $key = str_replace('_', ' ', $key);
            $needNormalization = true;
        }

        if (strpos($key, '-') !== false) {
            $key = str_replace('-', ' ', $key);
            $needNormalization = true;
        }

        if ($needNormalization) {
            $key = ucwords($key);
            $key = str_replace(' ', '', $key);
            $key = lcfirst($key);
        }

        return $key;
    }

    /**
     * @param array $data
     */
    protected function resolve(array $data): void
    {
        $normalizedData = [];

        foreach ($data as $propertyName => $value) {
            $normalizedPropertyName = $this->normalizeDefinedKey($propertyName);
            $normalizedData[$normalizedPropertyName] = $value;
        }

        $onlyDefinedData = $this->getOnlyDefinedData($normalizedData);

        $resolvedData = $this->getOptionResolver()->resolve($onlyDefinedData);
        foreach ($resolvedData as $propertyName => $value) {
            if (property_exists($this, $propertyName)) {
                $this->$propertyName = $value;
            }
        }
    }
}
