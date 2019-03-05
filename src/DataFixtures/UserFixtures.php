<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
     private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i<10;$i++){
            $length = 6;
            $password = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $password
            ));
            $user->setEmail("maxxxxxx".$i."@gmail.com");
            $user->setPlainPass($password);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
