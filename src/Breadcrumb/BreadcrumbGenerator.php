<?php 
namespace App\Breadcrumb;

use App\Breadcrumb\Breadcrumb;

class BreadcrumbGenerator
{

    public function __construct(
        private ?Breadcrumb $breadcrumb = null
    ){
        $this->generate();
    }

    private function breadcrumbStart(): string
    {
        return '';
    }

    private function breadcrumbEnd(): string
    {
        return '';
    }

    private function breadcrumbItem(): string
    {
        return '';
    }

    public function generate(): ?string
    {
        return '';
    }
}