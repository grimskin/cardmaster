<?php


namespace App\Command;


use App\Service\FS\SaveSet;
use App\Service\Http\SetDownloader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchSetData extends Command
{
    protected static $defaultName = 'cm:fetch-set-data';
    private SetDownloader $downloader;
    private SaveSet $saver;

    public function __construct(
        SetDownloader $downloader,
        SaveSet $saver
    ) {
        $this->downloader = $downloader;
        $this->saver = $saver;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Fetches JSON file with set data from mtgjson.com');
        $this->addArgument('set_name', InputArgument::REQUIRED, '3-letter set name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $setName = $input->getArgument('set_name');
        $output->writeln('Attempting to download '.$setName.' from mtgjson.com');

        if (!$this->downloader->download($setName)) {
            $output->writeln('Failed to download '.$setName);

            return Command::FAILURE;
        }

        $output->writeln(
            sprintf('Retrieved %d bytes', strlen($this->downloader->getData()))
        );

        $this->saver->save($setName, $this->downloader->getData());

        $output->writeln('Done!');

        return Command::SUCCESS;
    }
}
