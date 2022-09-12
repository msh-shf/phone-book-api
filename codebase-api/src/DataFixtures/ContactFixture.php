<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ContactFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // For unit testing purposes, then faker produces the same results
        $faker->seed(123456);

        for($i = 0; $i < 5; $i++) {
            $contact = new Contact();
            $contact->setFirstName($faker->firstName);
            $contact->setLastName($faker->lastName);
            $contact->setPhone($faker->phoneNumber);
            $contact->setEmail($faker->email);
            $contact->setBirthday($faker->dateTime('2000-01-01'));
            $contact->setAddress($faker->address);
            $contact->setPicture($faker->imageUrl(300, 300, 'people'));

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
