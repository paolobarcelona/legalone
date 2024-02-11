<?php
declare(strict_types=1);

namespace App\Service\Parser\Log;

interface LogParserInterface
{
    /**
     * @param string $filePath
     * 
     * @return iterable<\App\Message\LogData>
     */
    public function parseLocalFile(string $filePath): iterable;    
}
