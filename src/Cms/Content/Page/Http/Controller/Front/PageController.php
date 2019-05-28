<?php

namespace Gestione\Cms\Content\Page\Http\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Gestione\Framework\Controller\AbstractController;
use Gestione\Cms\Content\Page\PageInterface;
use Gestione\Cms\Content\Page\HookerAwarePage;

class PageController extends AbstractController
{
    public function page(Request $request, PageInterface $page)
    {
        $page = HookerAwarePage::fromPage($page, $this->getHooker());

        $this->setupPage($page);

        return $this->render([
            '@theme/page-'.$page->getId().'.tpl',
            '@cms/page-'.$page->getId().'.tpl',
            //taxonomies = '@theme/page-'.$page->getId().'.tpl',
            //taxonomies = '@cms/page-'.$page->getId().'.tpl',
            '@theme/page-'.$page->getType().'.tpl',
            '@cms/page-'.$page->getType().'.tpl',
            '@theme/page.tpl',
            '@cms/page.tpl',
        ], [
            'page' => $page
        ]);
    }
}
