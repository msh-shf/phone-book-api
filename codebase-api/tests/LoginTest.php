<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    /** @test */
    public function getAccessTokenSuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "username" => "admin",
                "password" => "admin",
            ]),
        );

        // Assert that return content type is application/json
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $content = $client->getResponse()->getContent();

        $this->assertJson($content);

        $data = json_decode($content, true);
        $this->assertArrayHasKey('token', $data);
    }

    /** @test */
    public function getAccessTokenFailure(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "username" => "wrong username",
                "password" => "wrong password",
            ]),
        );

        // Assert that return content type is application/json
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $content = $client->getResponse()->getContent();

        $this->assertJson($content);
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

}
