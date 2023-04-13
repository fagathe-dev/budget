<?php
namespace App\Breadcrumb;

final class BreadcrumbItem
{

    public function __construct(
        private string $name = '',
        private string $link = ''
    ) {
    }


    /**
     * Get the value of link
     * 
     * @return string
     */
    public function getLink():string
    {
        return $this->link;
    }

    /**
     * Get the value of name
     * 
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }
}