<?php

declare(strict_types=1);

namespace App\Tests\Unit\Base\Pet\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use App\Base\Pet\Entity\Category;
use PHPUnit\Framework\TestCase;

#[CoversClass(Category::class)]
class CategoryTest extends TestCase
{
    public function testCanCreateCategory(): void
    {
        $id   = 123;
        $name = 'Cat';

        $category = new Category($id, $name);

        $this->assertEquals(
            ['id' => 123, 'name' => 'Cat'],
            $category->toArray()
        );
    }

    public function testGetId()
    {
        $id   = 123;
        $name = 'Cat';
        $category = new Category($id, $name);

        $this->assertEquals(123, $category->getId());
    }

    public function testGetName()
    {
        $id   = 123;
        $name = 'Cat';
        $category = new Category($id, $name);

        $this->assertEquals('Cat', $category->getName());
    }
}
