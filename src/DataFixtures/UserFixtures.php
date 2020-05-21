<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;


    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail('qqq@qq.com')
            ->setPassword($this->passwordEncoder->encodePassword($user1, 'Ctdfcnjgjkm060599'))
            ->setName('qq')
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($user1);
        $this->addReference(User::class . '_1',$user1);

        $user2 = new User();
        $user2->setEmail('gg@qq.com')
            ->setPassword($this->passwordEncoder->encodePassword($user2, 'Ctdfcnjgjkm060599'))
            ->setName('gg')
            ->setRoles(['ROLE_USER']);
        $manager->persist($user2);
        $this->addReference(User::class . '_2',$user2);

        $manager->flush();
    }
}
