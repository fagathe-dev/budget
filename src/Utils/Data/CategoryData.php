<?php 
namespace App\Utils\Data;

final class CategoryData 
{
    
    /**
     * getData
     *
     * @return array
     */
    public static function getData():array 
    {
        return [
            [
                'name' => 'Logement',
                'icon' => 'ri-community-line',
                'description' => 'Loyers, travaux, ...',
            ],
            [
                'name' => 'Achats & shopping',
                'icon' => 'ri-shopping-bag-line',
                'description' => 'Shopping, Dépenses du quotidien, ...',
            ],
            [
                'name' => 'Abonnements',
                'icon' => 'ri-calendar-event-line',
                'description' => 'Les abonnements téléphone, netflix, ...',
            ],
            [
                'name' => 'Voiture',
                'icon' => 'ri-car-line',
                'description' => 'Loyers, travaux, ...',
            ],
            [
                'name' => 'Transports',
                'icon' => 'ri-bus-line',
                'description' => null,
            ],
            [
                'name' => 'Économies',
                'icon' => 'ri-coins-line',
                'description' => null,
            ],
            [
                'name' => 'Santé',
                'icon' => 'ri-hospital-line',
                'description' => null,
            ],
            [
                'name' => 'Impôts, taxes, frais',
                'icon' => 'ri-bank-line',
                'description' => null,
            ],
            [
                'name' => 'Vacances & loisirs',
                'icon' => 'ri-suitcase-line',
                'description' => 'Sorties',
            ],
            [
                'name' => 'Énergies',
                'icon' => 'ri-water-flash-line',
                'description' => null,
            ],
            [
                'name' => 'Autres',
                'icon' => 'ri-calculator-line',
                'description' => null,
            ],
        ];
    } 
}