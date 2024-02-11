<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\Importer\Log\LogImporterInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'log:import-data',
    description: 'Persists all entries from logs.log.',
    hidden: false,
    aliases: ['log:import-data']
)]
class ImportLogDataCommand extends Command
{
    public function __construct(private LogImporterInterface $importer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importer->importLocalLogFile();

        $output->writeln('Logs are now being saved in the background.');

        return Command::SUCCESS;
    }
}