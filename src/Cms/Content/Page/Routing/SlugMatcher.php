<?php

namespace Gestione\Cms\Content\Page\Routing;

use Gestione\Cms\Routing\SlugMatcherInterface;
use Gestione\Cms\Routing\MatchInterface;
use Gestione\Cms\Routing\Match;
use Gestione\Cms\Content\Page\PageManager;
use Gestione\Cms\Content\Page\Http\Controller\Front\PageController;

class SlugMatcher implements SlugMatcherInterface
{
    protected $pageManager;

    public function __construct(PageManager $pageManager)
    {
        $this->pageManager = $pageManager;
    }

    public function match(string $slug): MatchInterface
    {
        $match = new Match;
        $page = $this->pageManager->findBySlug($slug);

        if(! $page)
            return $match;

        $match->setController(PageController::class.'::page');
        $match->setAttribute('page', $page);
        $match->setAttribute('slug-matcher-type', 'page');

        return $match;
    }

    public function supportsPagination(): bool
    {
        return false;
    }
}
