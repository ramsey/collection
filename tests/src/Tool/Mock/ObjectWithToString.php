<?php
namespace Ramsey\Collection\Test\Tool\Mock;

class ObjectWithToString
{
    public function __toString()
    {
        return 'BAZ';
    }
}
