<?php


namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class MakeCss extends Command
{
    protected static $defaultName = 'cm:make:css';
    private string $projectDir;
    private string $cssPath = '/assets/css/';

    public function __construct(
        string $projectDir
    ) {
        $this->projectDir = $projectDir;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Generates CSS files used for dynamic elements size configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = 'div-h-x.scss';
        $content = [];

        $content[] = '$scale: 2;';
        $content[] = '$minHeight: 1.6rem;';
        $content[] = '';
        $content[] = '.div-h-0 {';
        $content[] = '    height: 0.1rem !important;';
        $content[] = '    background-color: transparent !important;';
        $content[] = '}';

        for ($i=1; $i<90; $i++) {
            $content[] = sprintf(
                '.div-h-%d { height: $minHeight + $scale * %01.1Frem !important; }',
                $i,
                round($i / 10, 2)
            );
        }
        $content[] = '';

        $fs = new Filesystem();
        $fs->dumpFile(
            $this->projectDir . $this->cssPath . $fileName,
            implode("\n", $content)
        );

        return Command::SUCCESS;
    }
}
