<?php
namespace App\Breadcrumb;

use App\Breadcrumb\Breadcrumb;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BreadcrumbGenerator
{

    /**
     * @var Request $request
     */
    private $request;

    public function __construct(
        private ?Breadcrumb $breadcrumb = null
    ) {
        $this->request = Request::createFromGlobals();
    }
    
    /**
     * breadcrumbStart
     *
     * @return string
     */
    private function breadcrumbStart(): string
    {

        return "<nav style=\"--bs-breadcrumb-divider: '{$this->breadcrumb->getSeparator()}';\" aria-label=\"breadcrumb\">
            <ol class=\"breadcrumb\">";
    }
    
    /**
     * breadcrumbEnd
     *
     * @return string
     */
    private function breadcrumbEnd(): string
    {
        return "</ol>
            </nav>";
    }
    
    /**
     * breadcrumbItem
     *
     * @param  mixed $item
     * @param  mixed $isActive
     * @return string
     */
    private function breadcrumbItem(BreadcrumbItem $item, bool $isActive = false): string
    {
        $active = $isActive ? " active\" aria-current=\"page\"" :  "";
        $link = $item->getLink() ?? '#';
        return "<li class=\"breadcrumb-item{$active}\">
            <a href=\"{$link}\">
                {$item->getName()}
            </a>
        </li>";
    }
    
    /**
     * lastKey
     *
     * @param  mixed $values
     * @return int
     */
    private function lastKey(array $values = []):int {
        $count = count($values);
        if ($count === 0 || $count === 1) {
            return $count;
        }
        return ($count - 1);
    }
    
    /**
     * generate
     *
     * @return string
     */
    public function generate(): ?string
    {
        $html = $this->breadcrumbStart();
        $lastKey = $this->lastKey($this->breadcrumb->getItems());
        $path = $this->request->getPathInfo();

        if ($this->breadcrumb->getHomePage()) {
            if (str_starts_with($path, '/admin')) {
                $route = '/admin';
            } else {
                $route = '/';
            }

            $html .= $this->breadcrumbItem(new BreadcrumbItem('Accueil', $route), $lastKey === 0);
        }   
        foreach ($this->breadcrumb->getItems() as $key => $item) {
            $html .= $this->breadcrumbItem($item, $key === $lastKey);
        }
        $html .= $this->breadcrumbEnd();
        
        return $html;
    }
}