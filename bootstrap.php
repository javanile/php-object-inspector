<?php

function debug_object_inspector($object)
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

function debug_object_inspector_recursive($array)
{
    $debugObject = $array;

    if (is_object($array)) {
        $debugObject = debug_object_inspector($array);
        foreach ($debugObject as $field => $value) {
            if (is_object($value) || is_array($value)) {
                $debugObject[$field] = debug_object_inspector_recursive($value);
            }
        }
    } elseif (is_array($array)) {
        foreach ($array as $key => $value) {
            $debugObject[$key] = debug_object_inspector_recursive($value);
        }
    }

    return $debugObject;
}
