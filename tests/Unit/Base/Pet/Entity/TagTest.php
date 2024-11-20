<?php

declare(strict_types=1);

namespace App\Tests\Unit\Base\Pet\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use App\Base\Pet\Entity\Tag;
use PHPUnit\Framework\TestCase;

#[CoversClass(Tag::class)]
class TagTest extends TestCase
{
    public function testCanCreateTag(): void
    {
        $id   = 1;
        $name = 'Male';

        $tag = new Tag($id, $name);

        $this->assertEquals(
            ['id' => 1, 'name' => 'Male'],
            $tag->toArray()
        );
    }

    public function testGetId()
    {
        $id   = 1;
        $name = 'Male';
        $tag = new Tag($id, $name);

        $this->assertEquals(1, $tag->getId());
    }

    public function testGetName()
    {
        $id   = 1;
        $name = 'Male';
        $tag = new Tag($id, $name);

        $this->assertEquals('Male', $tag->getName());
    }
}
