<?php
namespace Ramsey\Collection\Test\Mock;

use Ramsey\Collection\AbstractCollection;

class FooCollection extends AbstractCollection
{
    public function getType()
    {
        return Foo::class;
    }
}
