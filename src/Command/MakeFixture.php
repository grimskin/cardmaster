<?php


namespace App\Command;


use App\Model\CardData;
use App\Service\DataLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MakeFixture extends Command
{
    protected static $defaultName = 'cm:make:fixture';
    private string $projectDir;
    private DataLoader $dataLoader;

    public function __construct(
        string $projectDir,
        DataLoader $dataLoader
    ) {
        $this->projectDir = $projectDir;
        $this->dataLoader = $dataLoader;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Creates test fixture');
        $this->addArgument('set_name', InputArgument::REQUIRED, '3-letter set name');
        $this->addArgument('card_name', InputArgument::REQUIRED, 'Card name in double quotes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $setName = $input->getArgument('set_name');
        $cardName = $input->getArgument('card_name');

        $output->writeln([$setName, $cardName]);

        $cards = $this->dataLoader->loadSet($setName, true);

        if (!count($cards['cards'])) return $this->fail($output);

        $cardJson = null;
        /** @var CardData $card */
        foreach ($cards['cards'] as $card) {
            if ($card->getName() !== $cardName) continue;

            $output->writeln('got the card');
            $cardJson = $card->getJson();
            break;
        }

        if (!$cardJson) return $this->fail($output);

        $fileName = $this->projectDir . '/tests/Fixtures/' . $cardName . '.json';

        $fs = new Filesystem();
        $fs->dumpFile($fileName, $cardJson);

        $output->writeln('Success!');

        return Command::SUCCESS;
    }

    private function fail(OutputInterface $output): int
    {
        $output->writeln('Failed');

        return Command::FAILURE;
    }
}
