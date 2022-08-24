<?php

namespace App\Command;

use App\Service\ScriptFileReader;
use App\Service\ScriptRunner;
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

    private ScriptRunner $runner;
    private ScriptFileReader $fileReader;

    public function __construct(
        ScriptRunner $runner,
        ScriptFileReader $fileReader
    ) {
        $this->runner = $runner;
        $this->fileReader = $fileReader;

        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();

        $this->addArgument('filename', InputArgument::REQUIRED, 'file name with experiment description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('welcome');

        $filename = $input->getArgument('filename');


        $gotFile = $this->fileReader->hasFile($filename);
        $output->writeln($filename . ' ' . ($gotFile ? 'exists' : 'absent'));

        $script = $this->fileReader->readFile($filename);

        $result = $this->runner->runScript($script);

        $output->writeln($result);

        return Command::SUCCESS;
    }
}
