<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LogControllerTest extends WebTestCase
{
    public function testCount(): void
    {
        $client = static::createClient();

        // Request a specific page
        $crawler = $client->request('GET', '/logs/count?statusCode=201');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
    }
}