<?php
declare(strict_types=1);

namespace App\Service\Importer\Log;

use App\Data\ImportResultData;

interface LogImporterInterface
{
    /**
     * @param string|null $filePath
     * 
     * @return \App\Data\ImportResultData
     */
    public function importLocalLogFile(?string $filePath = null): ImportResultData;
}
