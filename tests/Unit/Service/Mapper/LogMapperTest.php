<?php
declare(strict_types=1);

namespace App\Tests\Service\Mapper;

use App\Service\Mapper\Log\LogMapper;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LogMapperTest extends KernelTestCase
{
    public function testMap(): void
    {
        $parts = [
            'USER-SERVICE',
            '-', 
            '-', 
            '[18/Aug/2018:10:33:59',
            '+0000]',
            '"POST',
            '/users', 
            'HTTP/1.1"',
            '201'
        ];

        $logMapper = new LogMapper();

        $mapped = $logMapper->map($parts);

        self::assertEquals('USER-SERVICE', $mapped->getServiceName());
        self::assertEquals(new DateTime('18/Aug/2018:10:33:59 +0000'), $mapped->getTimestamp());
        self::assertEquals('POST', $mapped->getRequestMethod());
        self::assertEquals('/users', $mapped->getRequestUri());
        self::assertEquals('201', $mapped->getStatusCode());
        self::assertEquals('HTTP/1.1', $mapped->getRequestHeader());
    }
}
