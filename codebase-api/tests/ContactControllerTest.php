<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testGetContactList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/contact?page=1&size=2');

        $content = $this->testJsonContent($client);

        $data = json_decode($content, true);

        $this->assertArrayHasKey('data', $data);

        $this->assertCount(2, $data['data']);
        $this->assertEquals(5, $data['total']);
    }

    public function testSearchContactByName(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/contact?name=Sandy');

        $content = $this->testJsonContent($client);

        $data = json_decode($content, true);

        $this->assertArrayHasKey('data', $data);

        $this->assertCount(1, $data['data']);
        $this->assertEquals(1, $data['total']);
    }

    public function testShowContact(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/contact/1');

        $content = $this->testJsonContent($client);

        $data = json_decode($content, true);

        $this->assertArrayHasKey('id', $data);
        $this->assertEquals(1, $data['id']);
    }

    public function testCreateContact(): void {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/contact', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "first_name" => "Masih",
                "last_name" => "Shafiee",
                "phone" => "+3165214782",
                "email" => "masih118shafiee@gmail.com",
                "birthday" => "1989-10-06",
                "address" => "",
                "picture" => ""
            ]),
        );

        $content = $this->testJsonContent($client);

        $data = json_decode($content, true);

        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('Masih', $data['first_name']);
    }

    public function testUpdateContact(): void {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/contact/1', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'first_name' => 'David',
                'last_name' => 'Carter',
            ]),
        );

        $content = $this->testJsonContent($client);

        $data = json_decode($content, true);

        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('David', $data['first_name']);
    }

    public function testDeleteContact(): void {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/contact/1');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    private function testJsonContent($client) {
        $this->assertResponseIsSuccessful();

        // Assert that return content type is application/json
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $content = $client->getResponse()->getContent();

        $this->assertJson($content);

        return $content;
    }
}
