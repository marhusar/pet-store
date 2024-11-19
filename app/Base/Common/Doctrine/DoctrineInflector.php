<?php

namespace App\Base\Common\Doctrine;

use App\Base\Common\Inflector;
use Doctrine\Inflector\LanguageInflectorFactory;

class DoctrineInflector implements Inflector
{

    /**
     * @var LanguageInflectorFactory
     */
    private LanguageInflectorFactory $inflectorFactory;

    /**
     * @param LanguageInflectorFactory $inflectorFactory
     */
    public function __construct(LanguageInflectorFactory $inflectorFactory)
    {
        $this->inflectorFactory = $inflectorFactory;
    }

    /**
     * @param string $word
     *
     * @return string
     */
    public function singularize(string $word): string
    {
        return $this->inflectorFactory->build()->singularize($word);
    }

    /**
     * @param string $word
     *
     * @return string
     */
    public function pluralize(string $word): string
    {
        return $this->inflectorFactory->build()->pluralize($word);
    }
}