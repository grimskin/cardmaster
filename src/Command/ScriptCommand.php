<?php

namespace App\Command;

use App\Domain\TestAssistant;
use App\Helper\ResultsFileWriter;
use App\Helper\YamlScriptReader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cm:script'
)]
class ScriptCommand extends Command
{
    private YamlScriptReader $scriptReader;
    private TestAssistant $assistant;
    private ResultsFileWriter $fileWriter;

    public function __construct(
        YamlScriptReader $scriptReader,
        TestAssistant $assistant,
        ResultsFileWriter $fileWriter
    ) {
        $this->scriptReader = $scriptReader;
        $this->assistant = $assistant;
        $this->fileWriter = $fileWriter;

        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();

        $this->addArgument('script_name', InputArgument::REQUIRED, 'script name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = $input->getArgument('script_name');

        if (!$this->scriptReader->hasFile($filename)) {
            $output->writeln('No script named '.$filename.' found');
            return Command::FAILURE;
        }

        $this->scriptReader->configureFromFile($this->assistant, $filename);

        $result = $this->assistant->runSimulations();

        $this->fileWriter->save($filename, json_encode($result, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }
}
