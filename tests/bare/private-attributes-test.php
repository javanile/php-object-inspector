<?php

require_once 'bootstrap.php';

$jsonFlags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

class MyClass1
{
    private $privateAttribute = 'private';
    protected $protectedAttribute = 'protected';
    public $publicAttribute = 'public';

    protected $internalAttribute = null;

    public function __construct()
    {
        $this->internalAttribute = new MyClass2();
    }

    /**
     * @return string
     */
    public function publicFunction()
    {
        return "publicFunction";
    }
}

class MyClass2
{
    private $privateAttribute = 'private';
    protected $protectedAttribute = 'protected';
    public $publicAttribute = 'public';

    private $internalAttribute2 = null;

    public function __construct()
    {
        $this->internalAttribute2 = [
            new MyClass3(),
            new MyClass3(),
        ];
    }
}

class MyClass3
{
    private $privateAttribute = 'private';
    protected $protectedAttribute = 'protected';
    public $publicAttribute = 'public';

    public static function staticFunction($a, $b = null)
    {
        return "staticFunction";
    }
}

$myObject = new MyClass1();

echo json_encode(debug_object_inspector_recursive($myObject), $jsonFlags)."\n";
