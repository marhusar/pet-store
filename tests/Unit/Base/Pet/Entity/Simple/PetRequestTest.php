<?php

declare(strict_types=1);

namespace App\Tests\Unit\Base\Pet\Entity\Simple;

use PHPUnit\Framework\Attributes\CoversClass;
use App\Base\Common\Collection\NumberCollection;
use App\Base\Common\Collection\TextCollection;
use App\Base\Pet\Entity\Simple\PetRequest;

use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PetRequest::class)]
#[UsesClass(NumberCollection::class)]
#[UsesClass(TextCollection::class)]
class PetRequestTest extends TestCase
{
    public function testCanCreatePetRequest(): void
    {
        // Arrange: Set up the test data
        $name       = 'Pet Name';
        $categoryId = 123;
        $statusId   = 456;
        $photoUrls  = $this->getMockBuilder(TextCollection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();
        $tagIds     = $this->getMockBuilder(NumberCollection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $expectedArray = [
            'name'       => $name,
            'categoryId' => $categoryId,
            'photoUrls'  => [],
            'tagIds'     => [],
            'statusId'   => $statusId,
        ];
        $createdPetRequest = new PetRequest($name, $categoryId, $photoUrls, $tagIds, $statusId);

        $this->assertEquals($expectedArray, $createdPetRequest->toArray());
    }

    public function testGetName()
    {
        $name = "Fido";
        $categoryId = 1;
        $photoUrls = $this->createMock(TextCollection::class);
        $tagIds = $this->createMock(NumberCollection::class);
        $statusId = 2;

        $petRequest = new PetRequest($name, $categoryId, $photoUrls, $tagIds, $statusId);

        $this->assertEquals($name, $petRequest->getName());
    }

    public function testGetCategoryId()
    {
        $name = "Fido";
        $categoryId = 1;
        $photoUrls = $this->createMock(TextCollection::class);
        $tagIds = $this->createMock(NumberCollection::class);
        $statusId = 2;

        $petRequest = new PetRequest($name, $categoryId, $photoUrls, $tagIds, $statusId);

        $this->assertEquals($categoryId, $petRequest->getCategoryId());
    }

    public function testCanGetPhotoUrls()
    {
        $name = "Fido";
        $categoryId = 1;
        $photoUrls = new TextCollection(['www.abc.sk', 'www.def.sk']);
        $tagIds = $this->createMock(NumberCollection::class);
        $statusId = 2;

        $petRequest = new PetRequest($name, $categoryId, $photoUrls, $tagIds, $statusId);

        $this->assertEquals(['www.abc.sk', 'www.def.sk'], $petRequest->getPhotoUrls()->toArray());
    }

    public function testCanGetTagIds()
    {
        $name = "Fido";
        $categoryId = 1;
        $photoUrls = $this->createMock(TextCollection::class);
        $tagIds = new NumberCollection([1, 2]);
        $statusId = 2;

        $petRequest = new PetRequest($name, $categoryId, $photoUrls, $tagIds, $statusId);

        $this->assertEquals([1, 2], $petRequest->getTagIds()->toArray());
    }

    public function testGetStatusId()
    {
        $name = "Fido";
        $categoryId = 1;
        $photoUrls = $this->createMock(TextCollection::class);
        $tagIds = $this->createMock(NumberCollection::class);
        $statusId = 2;

        $petRequest = new PetRequest($name, $categoryId, $photoUrls, $tagIds, $statusId);

        $this->assertEquals($statusId, $petRequest->getStatusId());
    }
}
