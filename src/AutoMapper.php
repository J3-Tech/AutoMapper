<?php

namespace AutoMapper;

use ReflectionClass;
use stdClass;

class AutoMapper
{
    public function map($source, $destination)
    {
        if ($source instanceof stdClass) {
            return $this->mapStdClass($source, $destination);
        }
        $reflectionClass = new ReflectionClass($source);
        foreach ($reflectionClass->getProperties() as $key => $property) {
            $property->setAccessible(true);
            $value = $property->getValue($source);
            $this->setValue($destination, $property->getName(), $value);
        }

        return $destination;
    }

    private function setValue($object, $propertyName, $value)
    {
        $reflectionClass = new ReflectionClass($object);
        if ($reflectionClass->hasProperty($propertyName)) {
            $property = $reflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }
    }

    private function mapStdClass(stdClass $source, $destination)
    {
        foreach ($source as $key => $value) {
            $this->setValue($destination, $key, $value);
        }

        return $destination;
    }
}
