<?php

namespace Javanile\ObjectInspector;

class ObjectInspector
{
    public static function inspect($object)
    {
        if (!is_object($object)) {
            return $object;
        }

        $debugObject = [];
        $reflection = new \ReflectionClass(get_class($object));

        foreach ($reflection->getProperties() as $property) {
            $prefix = '';
            if ($property->isPrivate()) {
                $prefix = '$[private] ';
            } elseif ($property->isProtected()) {
                $prefix = '$[protected] ';
            }

            $name = $property->getName();

            $property->setAccessible(true);
            $value = $property->getValue($object);

            $debugObject[$prefix.$name] = $value;
        }

        foreach ($reflection->getMethods() as $method) {
            $prefix = '@[';
            if ($method->isStatic()) {
                $prefix .= 'static ';
            }
            if ($method->isPrivate()) {
                $prefix .= 'private ';
            } elseif ($method->isProtected()) {
                $prefix .= 'protected ';
            }
            $prefix .= 'function] ';
            $name = $method->getName();
            $debugParams = [];
            $params = $method->getParameters();
            foreach ($params as $param) {
                $debugParams[] = $param->isOptional() ? '$'.$param->getName().' = '.json_encode($param->getDefaultValue()) : '$'.$param->getName();
            }
            $debugObject[$prefix.$name.'('.implode(', ', $debugParams).')'] = 'Declared at '.$method->getFileName().':'.$method->getStartLine();
        }

        ksort($debugObject);

        return $debugObject;
    }

    public static function inspectRecursive($array)
    {
        $debugObject = $array;

        if (is_object($array)) {
            $debugObject = self::inspect($array);
            foreach ($debugObject as $field => $value) {
                if (is_object($value) || is_array($value)) {
                    $debugObject[$field] = self::inspectRecursive($value);
                }
            }
        } elseif (is_array($array)) {
            foreach ($array as $key => $value) {
                $debugObject[$key] = self::inspectRecursive($value);
            }
        }

        return $debugObject;
    }
}
