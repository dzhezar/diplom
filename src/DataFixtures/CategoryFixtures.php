<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1,6) as $item) {
            $faker = Factory::create();
            $category = new Category();
            $slugger = new AsciiSlugger();
            $name = $faker->word();
            $category->setName($name)
                ->setSlug($slugger->slug($name))
                ->setIsVisible(true);
            $manager->persist($category);
            $this->addReference(Category::class . '_' . $item, $category);
        }
        $manager->flush();
    }
}
