<?php

use Javanile\ObjectInspector\ObjectInspector;

function debug_object_inspector($object)
{
    return ObjectInspector::inspect($object);
}

function debug_object_inspector_recursive($array)
{
    return ObjectInspector::inspectRecursive($array);
}
