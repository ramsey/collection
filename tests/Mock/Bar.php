<?php

declare(strict_types=1);

namespace Ramsey\Collection\Test\Mock;

class Bar
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
