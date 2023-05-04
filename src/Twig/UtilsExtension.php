<?php 
namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

final class UtilsExtension extends AbstractExtension
{

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_type', [$this, 'getType']),
            new TwigFilter('strlen', [$this, 'strLength']),
        ];
    }

    /**
     * @param mixed $var
     *
     * @return string
     */
    public function getType(mixed $var): string
    {
        return gettype($var);
    }

    /**
     * @param string|null $subject
     *
     * @return int
     */
    public function strLength(?string $subject): int
    {
        return strlen($subject);
    }

}