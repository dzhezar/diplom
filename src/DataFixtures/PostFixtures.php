<?php


namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 50 ; $i++) {
            $post = new Post();
            $category = $this->getReference(Category::class . '_' . $faker->numberBetween(1, 6));
            $author = $this->getReference(User::class . '_' . $faker->numberBetween(1, 2));

            $post->setTitle($faker->sentence(3))
                ->setText($faker->sentences(5,true))
                ->setCategory($category)
                ->setAuthor($author)
                ->setCreated(new DateTime())
                ->setIsVisible($faker->boolean(70))
                ->setImage('https://loremflickr.com/640/480?random='.$i);
            $manager->persist($post);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
