<?php

declare(strict_types=1);

namespace App\Tests\Unit\Base\Pet\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use App\Base\Pet\Entity\Status;
use PHPUnit\Framework\TestCase;

#[CoversClass(Status::class)]
class StatusTest extends TestCase
{
    public function testCanCreateStatus(): void
    {
        $id   = 1;
        $name = 'Sold';

        $status = new Status($id, $name);

        $this->assertEquals(
            ['id' => 1, 'name' => 'Sold'],
            $status->toArray()
        );
    }

    public function testGetId()
    {
        $id   = 1;
        $name = 'Sold';
        $status = new Status($id, $name);

        $this->assertEquals(1, $status->getId());
    }

    public function testGetName()
    {
        $id   = 1;
        $name = 'Sold';
        $status = new Status($id, $name);

        $this->assertEquals('Sold', $status->getName());
    }
}
