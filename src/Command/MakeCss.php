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
        $scale = 2;
        $minHeight = 1.6;
        $step = 1;

        $fileName = 'div-h-x.scss';
        $content = [];

        $content[] = '.div-h-0 {';
        $content[] = '    height: 0.1rem !important;';
        $content[] = '    background-color: transparent !important;';
        $content[] = '}';

        for ($i=1; $i<90; $i++) {
            $content[] = sprintf(
                '.div-h-%d { height: %01.1Frem !important; }', $i, round($minHeight + ($scale * $step *  $i / 10), 2)
            );
        }
        $content[] = '';

        $content[] = '.div-2h-0 {';
        $content[] = '    height: 0.1rem !important;';
        $content[] = '    background-color: transparent !important;';
        $content[] = '}';

        $step = 2;
        for ($i=1; $i<90; $i++) {
            $content[] = sprintf(
                '.div-2h-%d { height: %01.1Frem !important; }', $i, round($minHeight + ($scale * $step * $i / 10), 2)
            );
        }
        $content[] = '';

        $content[] = '.div-w-0 {';
        $content[] = '    width: 0.1rem !important;';
        $content[] = '    background-color: transparent !important;';
        $content[] = '}';

        $minWidth = 2;
        $step = 1;
        $scale = 5;
        for ($i=1; $i<90; $i++) {
            $content[] = sprintf(
                '.div-w-%d { width: %01.1Frem !important; }', $i, round($minWidth + ($scale * $step * $i / 10), 2)
            );
        }
        $content[] = '';

        $content[] = '.div-2w-0 {';
        $content[] = '    width: 0.1rem !important;';
        $content[] = '    background-color: transparent !important;';
        $content[] = '}';

        $minWidth = 2;
        $step = 2;
        $scale = 5;
        for ($i=1; $i<90; $i++) {
            $content[] = sprintf(
                '.div-2w-%d { width: %01.1Frem !important; }', $i, round($minWidth + ($scale * $step * $i / 10), 2)
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
