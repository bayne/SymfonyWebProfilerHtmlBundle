<?php

namespace Bayne\SymfonyWebProfilerHtmlBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpProfilerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('bayne:dump_profiler')
            ->addArgument('token', InputArgument::REQUIRED, 'The token id of the profile to load')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $input->getArgument('token');
        $this->getContainer()->get('bayne.symfony_web_profiler_html_bundle.outputter')->write($token, 'output');
    }

}
