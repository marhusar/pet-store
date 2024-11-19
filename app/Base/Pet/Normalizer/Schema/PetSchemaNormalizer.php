<?php

namespace App\Base\Pet\Normalizer\Schema;

use App\Base\Common\Collection\DataCollection;
use App\Base\Common\Collection\NumberCollection;
use App\Base\Common\Collection\TextCollection;
use App\Base\Pet\Entity\Simple\PetRequest;
use App\Base\Pet\Normalizer\PetNormalizer;
use App\Base\Pet\Repository\CategoryRepository;
use App\Base\Pet\Repository\StatusRepository;
use App\Base\Pet\Repository\TagRepository;
use Nette\Schema\Elements\Structure;
use Nette\Schema\Elements\Type;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\ValidationException;
use Nette\Schema\Schema;

class PetSchemaNormalizer implements PetNormalizer
{
    /**
     * @var Processor
     */
    private Processor $processor;

    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * @var TagRepository
     */
    private TagRepository $tagRepository;

    /**
     * @var StatusRepository
     */
    private StatusRepository $statusRepository;

    /**
     * @param Processor          $processor
     * @param CategoryRepository $categoryRepository
     * @param TagRepository      $tagRepository
     * @param StatusRepository   $statusRepository
     */
    public function __construct(Processor $processor, CategoryRepository $categoryRepository, TagRepository $tagRepository, StatusRepository $statusRepository)
    {
        $this->processor          = $processor;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository      = $tagRepository;
        $this->statusRepository   = $statusRepository;
    }

    private function createSchema(): Structure
    {
        $schema = Expect::structure([
            'name' => Expect::string(),
            'categoryId' => Expect::structure([
                'id' => Expect::int()->required(),
                'name' => Expect::string()->required(),
            ])->transform(function ($category) {
                return $category->id;
            })->assert(function ($id) {
                return $this->categoryRepository->findById($id) !== null;
            }),
            'photoUrls' => Expect::arrayOf('string')->transform(function (array $urls) {
                return new TextCollection($urls);
            }),
            'tagIds' => Expect::arrayOf(Expect::structure([
                'id' => Expect::int()->required(),
                'name' => Expect::string()->required(),
            ]))->transform(function ($tags) {
                $tagIds = array_map(fn($tag) => $tag->id, $tags);
                return new NumberCollection($tagIds);
            })->assert(function ($tagIds) {
                foreach ($tagIds as $tagId) {
                    if (!$this->tagRepository->findById($tagId)) {
                        return false;
                    }
                }
                return true;
            }),
            'statusId' => Expect::string()->transform(function (string $name){
                $name = $this->statusRepository->findByName($name);

                if (!$name) {
                    throw new ValidationException('Status with name ' . $name . ' does not exists');
                }
                return $name;
            }),
        ])->before(function (array $request) {
            $request['categoryId'] = $request['category'] ?? [];
            $request['tagIds'] = $request['tags'] ?? [];
            $request['statusId'] = $request['status'] ?? '';
            unset($request['id'], $request['category'], $request['tags'], $request['status']);
            return $request;
        })->castTo(PetRequest::class);

        return $schema;
    }

    /**
     * @param DataCollection $data
     *
     * @return PetRequest
     */
    public function denormalize(DataCollection $data): PetRequest
    {
        return $this->processor->process($this->createSchema(), $data->toArray());
    }

    /**
     *
     * @return DataCollection
     */
    public function getSchema(): DataCollection
    {
        $schema = [
            'name' => 'string',
            'category' => ['id' => 'int', 'name' => 'string'],
            'photoUrls' => ['string', 'string'],
            'tags' => [['id' => 'int', 'name' => 'string']],
            'status' => 'string',
        ];

        return new DataCollection($schema);
    }
}