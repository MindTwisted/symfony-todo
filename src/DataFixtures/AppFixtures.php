<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Todo;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('John Walker');
        $user->setEmail('john@example.com');
        $password = $this->encoder->encodePassword($user, 'secret');
        $user->setPassword($password);
        $manager->persist($user);

        for ($i = 1; $i <= 20; $i++) 
        {
            $category = new Category();
            $category->setName("Category $i");
            $manager->persist($category);

            for ($j = 1; $j <= 20; $j++)
            {
                $todo = new Todo();
                $todo->setTitle("Todo $i-$j");
                $todo->setBody("Default text");
                $todo->setCategory($category);
                $manager->persist($todo);
            }
        }

        $manager->flush();
    }
}
