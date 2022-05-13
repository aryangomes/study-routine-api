<?php

use Ramsey\Collection\Exception\InvalidArgumentException;

if (!function_exists('get_short_class_name')) {
    function get_short_class_name(Object $class)
    {
        if (!is_object($class)) {
            throw new InvalidArgumentException("The argument \$class must be a Object");
        }
        $reflectionClass = new \ReflectionClass($class);

        $shortClassName = $reflectionClass->getShortName();

        return $shortClassName;
    }
}
