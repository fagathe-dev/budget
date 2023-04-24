<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Category;
use App\Utils\FakerTrait;
use Cocur\Slugify\Slugify;
use App\Utils\Data\CategoryData;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProdFixtures extends Fixture
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

        foreach (CategoryData::getData() as $c) {
            $category = new Category;

            $category->setCreatedAt($this->now)
                ->setUpdatedAt($this->now)
                ->setName($c['name'])
                ->setIcon($c['icon'])
                ->setDescription($c['description'])
                ->setSlug($this->slugify->slugify($category->getName()))
            ;

            $manager->persist($category);
        }

        $manager->flush();
    }

}
