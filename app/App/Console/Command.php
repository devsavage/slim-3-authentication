<?php
namespace App\Console;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    protected $ci;

    private $input;

    private $output;

    public function __construct(ContainerInterface $ci)
    {
        parent::__construct();
        $this->ci = $ci;
    }

    protected function configure()
    {
        $this->setName($this->command)->setDescription($this->description);

        $this->addArguments();
        $this->addOptions();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        return $this->handle($input, $output);
    }

    protected function argument($name)
    {
        return $this->input->getArgument($name);
    }

    protected function option($name)
    {
        return $this->input->getOption($name);
    }

    protected function addArguments()
    {
        foreach ($this->arguments() as $argument) {
            $this->addArgument($argument[0], $argument[1], $argument[2]);
        }
    }

    protected function addOptions()
    {
        foreach ($this->options() as $option) {
            $this->addOption($option[0], $option[1], $option[2], $option[3], $option[4]);
        }
    }

    protected function info($value)
    {
        return $this->output->writeln('<info>' . $value . '</info>');
    }

    protected function error($value)
    {
        return $this->output->writeln('<error>' . $value . '</error>');
    }
}
