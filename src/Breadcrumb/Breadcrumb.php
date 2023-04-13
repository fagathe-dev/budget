<?php
namespace App\Breadcrumb;

use App\Breadcrumb\BreadcrumbItem;

class Breadcrumb
{

    public function __construct(
        /** 
         * @var BreadcrumbItem[] $items 
         */
        private array $items = [],
        private bool $homePage = true,
        private ?string $separator = "/",
        private ?array $options = []
    ) {
    }


    /**
     * Get the value of items
     *
     * @return BreadcrumbItem[] 
     */
    public function getItems():array
    {
        return $this->items;
    }

    /**
     * Get the value of homePage
     * 
     * @return boolean 
     */
    public function getHomePage():bool
    {
        return $this->homePage;
    }

    /**
     * Get the value of options
     * 
     * @return array|null
     */
    public function getOptions():?array
    {
        return $this->options;
    }

    /**
     * Get the value of separator
     * 
     * @return null|string
     */
    public function getSeparator():?string
    {
        return $this->separator;
    }
}