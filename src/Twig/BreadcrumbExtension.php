<?php
namespace App\Twig;

use App\Breadcrumb\Breadcrumb;
use App\Breadcrumb\BreadcrumbGenerator;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class BreadcrumbExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('generate_breadcrumb', [$this, 'generateBreadcrumb'], ['is_safe' => ['html']]),
        ];
    }

    public function generateBreadcrumb(?Breadcrumb $breadcrumb):string
    {
        return (new BreadcrumbGenerator($breadcrumb))->generate();
    }
}