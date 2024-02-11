<?php
declare(strict_types=1);

namespace App\Service\Mapper\Log;

use App\Data\LogData;

interface LogMapperInterface
{
    /**
     * @param iterable<mixed> $lines
     * 
     * @return \App\Data\LogData
     */
    public function map(iterable $lines): LogData;
}
