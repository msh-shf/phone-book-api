<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testGetContactList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/contact?page=1&size=2');

        $content = $this->_testJsonContent($client);

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertCount(2, $data['data']['data']);
        $this->assertEquals(5, $data['data']['meta']['total']);
    }

    public function testSearchContactByName(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/contact?name=Sandy');

        $content = $this->_testJsonContent($client);

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertCount(1, $data['data']['data']);
        $this->assertEquals(1, $data['data']['meta']['total']);
    }

    public function testShowContact(): void {
        $client = static::createClient();
        $client->request('GET', '/api/contact/1');

        $content = $this->_testJsonContent($client);

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals(1, $data['data']['id']);
    }

    public function testCreateContact(): void {
        $client = static::createClient();
        $client->request('POST', '/api/contact', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "first_name" => "James",
                "last_name" => "Carter",
                "phone" => "+3165214782",
                "email" => "example@gmail.com",
                "birthday" => "1989-10-06",
                "address" => "",
                "picture" => ""
            ]),
        );

        $content = $this->_testJsonContent($client);

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals('James', $data['data']['first_name']);
    }

    public function testUpdateContact(): void {
        $client = static::createClient();
        $client->request('PUT', '/api/contact/1', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'first_name' => 'David',
                'last_name' => 'Carter',
            ]),
        );

        $content = $this->_testJsonContent($client);

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals('David', $data['data']['first_name']);
    }

    public function testDeleteContact(): void {
        $client = static::createClient();
        $client->request('DELETE', '/api/contact/1');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    private function _testJsonContent($client) {
        $this->assertResponseIsSuccessful();

        // Assert that return content type is application/json
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $content = $client->getResponse()->getContent();

        $this->assertJson($content);

        return $content;
    }
}
