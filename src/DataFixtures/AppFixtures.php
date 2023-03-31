<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Budget;
use DateTimeImmutable;
use App\Entity\Expense;
use App\Entity\Category;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    /**
     * @var Generator $faker
     */
    private $faker;

    /**
     * @var DateTimeImmutable $now
     */
    private $now;
    
    /**
     * @var Slugify $slugify
     */
    private $slugify;

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ){
        $this->faker = Factory::create('fr_FR');
        $this->now = new DateTimeImmutable;
        $this->slugify = new Slugify;
    }

    public function load(ObjectManager $manager): void
    {
        $listCategories = [];
        $listUsers = [];

        foreach ($this->categories() as $k => $c) {
            $category = new Category;

            $category->setCreatedAt($this->now)
                ->setUpdatedAt($this->now)
                ->setName($c['name'])
                ->setIcon($c['icon'])
                ->setDescription($c['description'])
                ->setSlug($this->slugify->slugify($category->getName()))
            ;

            $manager->persist($category);
            $listCategories[$k] = $category;
        }

        $admin = new User;

        $admin->setEmail($this->faker->email())
            ->setRoles(['ROLE_ADMIN'])
            ->setRegisteredAt($this->now)
            ->setUpdatedAt($this->now)
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setUsername($this->faker->userName())
            ->setIsConfirm(true)
        ;

        $manager->persist($admin);

        for ($u=0; $u < random_int(30, 50); $u++) { 
            $user = new User;

            $user->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setRegisteredAt($this->now)
                ->setUpdatedAt($this->now)
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setUsername($this->faker->userName())
                ->setIsConfirm(true)
            ;

            for ($i=0; $i < random_int(3, 6); $i++) { 
                $budget = new Budget;

                $budget->setAmount($this->faker->randomNumber(3, false))
                    ->setCategory($this->randomElement($listCategories));

                $user->addBudget($budget);
            }

            for ($i=0; $i < random_int(50, 100); $i++) { 
                $expense = new Expense;

                $expense->setAmount($this->faker->randomFloat(2, 1, 500))
                    ->setLabel($this->faker->words(random_int(1, 4), true))
                    ->setCategory($this->randomElement($listCategories))
                    ->setIsPaid($this->randomElement([true, false]))
                    ->setPaidAt($this->now)
                ;

                $user->addExpense($expense);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function categories():array 
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

    private function randomElement(array $elements):mixed 
    {
        shuffle($elements);
        
        return $elements[random_int(0, (count($elements) - 1))];
    }
}
