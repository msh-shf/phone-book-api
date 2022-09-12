<?php

namespace App\Tests;

use App\Entity\Contact;
use App\EntityFactory\ContactFactory;
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
            'first_name' => 'David',
            'last_name' => 'Carter',
            'phone' => '+12345678900',
            'email' => 'example@gmail.com',
            'birthday' => '1989-10-06',
            'address' => 'Berline, Germany',
            'picture' => ''
        ]);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $insertedId = $entity->getId();
        $this->assertNotNull($insertedId);

        /** @var Contact $contact */
        $contact = $this->entityManager
            ->getRepository(Contact::class)
            ->findOneBy(["id" => $insertedId]);

        $this->assertEquals($insertedId, $contact->getId());
        $this->assertEquals("David", $contact->getFirstName());
    }
}
