<?php
declare(strict_types=1);

namespace App\Service\Mapper\Log;

use App\Data\LogData;
use DateTIme;

final class LogMapper implements LogMapperInterface
{
    /**
     * @inheritDoc
     */
    public function map(iterable $parts): LogData
    {
        $serviceName = $parts[0] ?? '';
        $timestamp = new DateTime(\ltrim($parts[3] ?? '', '[') . ' ' . rtrim($parts[4] ?? '', ']'));
        $requestMethod = \trim($parts[5] ?? '', '"');
        $requestUri = $parts[6] ?? '';
        $requestHeader = \trim($parts[7] ?? '', '"');
        $responseCode = (int)($parts[8] ?? 0);

        return new LogData(
            $serviceName, 
            $timestamp, 
            $requestMethod, 
            $requestUri, 
            $requestHeader, 
            $responseCode
        );
    }
}
