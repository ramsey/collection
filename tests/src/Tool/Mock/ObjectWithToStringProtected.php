<?php
namespace Ramsey\Collection\Test\Tool\Mock;

class ObjectWithToStringProtected
{
    protected function __toString()
    {
        return 'BAZ';
    }
}