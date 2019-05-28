<?php

namespace Gestione\Component\Stdlib;

use Ausi\SlugGenerator\SlugGenerator;

class Slugger
{
    protected $generator;
    protected $locale;

    // Send locale to config options
    // https://github.com/ausi/slug-generator
    public function __construct(Locale $locale)
    {
        $this->locale = $locale;
    }

    protected function instance()
    {
        if($this->generator)
            return $this->generator;

        return $this->generator = new SlugGenerator;
    }

    public function generate($input)
    {
        return $this->instance()->generate($input);
    }
}
