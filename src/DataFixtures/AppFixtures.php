<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Budget;
use DateTimeImmutable;
use App\Entity\Expense;
use App\Entity\Category;
use App\Utils\FakerTrait;
use Cocur\Slugify\Slugify;
use App\Utils\Data\CategoryData;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    use FakerTrait;

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

        foreach (CategoryData::getData() as $k => $c) {
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

        for ($u=0; $u < random_int(30, 50); $u++) { 
            $user = new User;
            $userBudgets = $this->randomElements($listCategories);

            $user->setEmail($this->faker->email())
                ->setRoles([$u === 0 ? 'ROLE_ADMIN' : 'ROLE_USER'])
                ->setRegisteredAt($this->now)
                ->setUpdatedAt($this->now)
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setUsername($this->faker->userName())
                ->setConfirm(true)
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
            ;

            foreach ($userBudgets as $k => $userBudget) { 
                $budget = new Budget;

                $budget->setAmount($this->faker->randomNumber(3, false))
                    ->setCategory($userBudget);

                $user->addBudget($budget);
            }

            for ($i=0; $i < random_int(100, 150); $i++) { 
                $expense = new Expense;

                $expense->setAmount($this->faker->randomFloat(2, 5, 500))
                    ->setLabel($this->faker->words(random_int(1, 4), true))
                    ->setCategory($this->randomElement($listCategories))
                    ->setIsPaid($this->randomElement([true, false]))
                    ->setCreatedAt($this->setDateTimeBetween('-4 months'))
                    ->setPaidAt($this->setDateTimeAfter($expense->getCreatedAt()))
                ;

                $user->addExpense($expense);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

}
