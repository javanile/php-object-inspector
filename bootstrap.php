<?php

use Javanile\ObjectInspector\ObjectInspector;

function debug_object_inspector($object)
{
    return ObjectInspector::inspect($object);
}

function debug_object_inspector_recursive($array, $depth = 256)
{
    return ObjectInspector::inspectRecursive($array, $depth);
}
