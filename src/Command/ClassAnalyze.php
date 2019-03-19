<?php

/*
 * This file is part of the "default-project" package.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer\Command;

use Greeflas\StaticAnalyzer\Analyzer\ClassAnalyzeExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class created to run command for getting information about class
 *
 * @author Alexey Baranov <lekha.baranov@gmail.com>
 */
class ClassAnalyze extends Command
{
    protected function configure()
    {
        $this
            ->setName('class-analyze')
            ->setDescription('Shows information about class and included properties and methods')
            ->addArgument(
                'fullClassName',
                InputArgument::REQUIRED,
                'Full class name with namespace'
            )
        ;
    }

    /**
     * method, which format output
     *
     * @param $result
     *
     * @return string
     */
    private function formatOutput($result): string
    {
        $output='';
        $output.='Class: ' . $result['className'] . ' is ' . $result['classType'] . \PHP_EOL;

        $output.='Properties:' . \PHP_EOL;
        $output.= "\t" . ' public: ' . $result['classProperties']['public']['count'] . (($result['classProperties']['public']['countStatic'] > 0) ? ', ' . $result['classProperties']['public']['countStatic'] . ' static' . \PHP_EOL : \PHP_EOL);
        $output.="\t" . ' protected: ' . $result['classProperties']['protected']['count'] . (($result['classProperties']['protected']['countStatic'] > 0) ? ', ' . $result['classProperties']['protected']['countStatic'] . ' static \n ' : \PHP_EOL);
        $output.="\t" . ' private: ' . $result['classProperties']['private']['count'] . (($result['classProperties']['private']['countStatic'] > 0) ? ', ' . $result['classProperties']['private']['countStatic'] . ' static \n ' : \PHP_EOL);

        $output.='Methods:' . \PHP_EOL;
        $output.="\t" . ' public: ' . $result['classMethods']['public']['count'] . (($result['classMethods']['public']['countStatic'] > 0) ? ', ' . $result['classMethods']['public']['countStatic'] . ' static' . \PHP_EOL : \PHP_EOL);
        $output.="\t" . ' protected: ' . $result['classMethods']['protected']['count'] . (($result['classMethods']['protected']['countStatic'] > 0) ? ', ' . $result['classMethods']['protected']['countStatic'] . ' static' . \PHP_EOL : \PHP_EOL);
        $output.="\t" . ' private: ' . $result['classMethods']['private']['count'] . (($result['classMethods']['private']['countStatic'] > 0) ? ', ' . $result['classMethods']['private']['countStatic'] . ' static' . \PHP_EOL : \PHP_EOL);

        return $output;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fullClassName = $input->getArgument('fullClassName');
        $analyzer = new ClassAnalyzeExecutor($fullClassName);

        $result = $analyzer->analyze();

        $output->writeln($this->formatOutput($result));
    }
}
