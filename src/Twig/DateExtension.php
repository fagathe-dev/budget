<?php 
namespace App\Twig;

use App\Utils\ServiceTrait;
use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class DateExtension extends AbstractExtension
{

    use ServiceTrait;

    public function getFilters()
    {
        return [
            new TwigFilter('since', [$this, 'since'], ['is_safe' => ['html']]),
        ];
    }

    public function since(?DateTimeImmutable $registeredAt):string
    {
        $since = 'moins d\'une minute';
        $diff = $this->now()->diff($registeredAt);
        $year = $diff->y;
        $month = $diff->m;
        $day = $diff->d;
        $hour = $diff->h;
        $minute = $diff->i;

        if ($year > 0) {
            $since = $year . ' ' . ($year === 1 ? 'an' : 'ans');
        }
        if ($year === 0 && $month > 0) {
            $since = $month . ' ' . 'mois';
        }
        if ($year === 0 && $month === 0 && $day > 0) {
            $since = $day . ' ' . ($day === 1 ? 'jour' : 'jours');
        }
        if ($year === 0 && $month === 0 && $day === 0 && $hour > 0) {
            $since = $hour . ' ' . ($hour === 1 ? 'heure' : 'heures');
        }
        if ($year === 0 && $month === 0 && $day === 0 && $hour === 0 && $minute > 0) {
            $since = $minute . ' ' . ($minute === 1 ? 'minute' : 'minutes');
        }
        
        return '<strong>'. $since .'</strong>';
    }

}