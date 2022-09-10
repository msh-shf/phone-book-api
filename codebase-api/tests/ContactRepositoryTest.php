<?php

namespace App\Tests;

use App\Entity\Contact;
use App\EntityFactory\ContactFactory;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testCreateContact(): void
    {
        $entity = ContactFactory::create([
            'first_name' => 'Masih',
            'last_name' => 'Shafiee',
            'phone' => '+989367237614',
            'email' => 'masih118shafiee@gmail.com',
            'birthday' => '1989-10-06',
            'address' => 'Mashhad, Iran',
            'picture' => ''
        ]);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $this->assertNotNull($entity->getId());

        $contact = $this->entityManager
            ->getRepository(Contact::class)
            ->findOneBy(["id" => $entity->getId()]);

        $this->assertEquals("Masih", $contact->getFirstName());
        $this->assertEquals("Shafiee", $contact->getLastName());
    }
}
