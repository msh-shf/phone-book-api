<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    private $jwt_token = null;
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    private function logIn() {
        $this->client->request('POST', '/login', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "username" => "admin",
                "password" => "admin",
            ]),
        );

        $content = $this->client->getResponse()->getContent();
        $data = json_decode($content, true);

        $this->jwt_token = $data['token'];
    }

    public function testGetContactList(): void
    {
        $this->logIn();

        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$this->jwt_token}",
        ];
        $this->client->request('GET', '/contact?page=1&size=2', [], [], $headers);

        $content = $this->_testJsonContent();

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertCount(2, $data['data']['data']);
        $this->assertEquals(5, $data['data']['meta']['total']);
    }

    public function testSearchContactByName(): void
    {
        $this->logIn();

        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$this->jwt_token}",
        ];
        $this->client->request('GET', '/contact?name=Sandy', [], [], $headers);

        $content = $this->_testJsonContent();

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertCount(1, $data['data']['data']);
        $this->assertEquals(1, $data['data']['meta']['total']);
    }

    public function testShowContact(): void
    {
        $this->logIn();

        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$this->jwt_token}",
        ];
        $this->client->request('GET', '/contact/1', [], [], $headers);

        $content = $this->_testJsonContent();

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals(1, $data['data']['id']);
    }

    public function testCreateContact(): void
    {
        $this->logIn();

        $this->client->request('POST', '/contact', [], [],
            [
                'HTTP_AUTHORIZATION' => "Bearer {$this->jwt_token}",
                'CONTENT_TYPE' => 'application/json',
            ],
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

        $content = $this->_testJsonContent();

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals('James', $data['data']['first_name']);
    }

    public function testUpdateContact(): void
    {
        $this->logIn();

        $this->client->request('PUT', '/contact/1', [], [],
            [
                'HTTP_AUTHORIZATION' => "Bearer {$this->jwt_token}",
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'first_name' => 'David',
                'last_name' => 'Carter',
            ]),
        );

        $content = $this->_testJsonContent();

        $data = json_decode($content, true);
        $this->assertArrayHasKey('data', $data);

        $this->assertArrayHasKey('id', $data['data']);
        $this->assertEquals('David', $data['data']['first_name']);
    }

    public function testDeleteContact(): void
    {
        $this->logIn();

        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$this->jwt_token}",
        ];

        $this->client->request('DELETE', '/contact/1', [], [], $headers);

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    private function _testJsonContent() {
        $this->assertResponseIsSuccessful();

        // Assert that return content type is application/json
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $content = $this->client->getResponse()->getContent();

        $this->assertJson($content);

        return $content;
    }
}
