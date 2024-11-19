<?php

namespace App\Base\Common;

interface Inflector
{
    /**
     * @param string $word
     *
     * @return string
     */
    public function singularize(string $word): string;

    /**
     * @param string $word
     *
     * @return string
     */
    public function pluralize(string $word): string;
}