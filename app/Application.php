<?php
use Symfony\Component\Console\Application as BaseApplication;

// Extensional Functionality of Application

// Command to add
use Application\Crawler\Command\CrawlerExecuteCommand;
use Symfony\Component\Console\Input\InputInterface,
	Symfony\Component\Console\Input\InputOption
;
use Symfony\Component\Console\Output\OutputInterface,
	Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
/**
 * Application 
 * 
 * @uses BaseApplication
 * @package { PACKAGE }
 * @copyright Copyrights (c) 1o1.co.jp, All Rights Reserved.
 * @author Yoshi<yoshi@1o1.co.jp> 
 * @license { LICENSE }
 */
class Application extends BaseApplication
{
	private $kernel;

	public function __construct(AppKernel $kernel)
	{
		$this->kernel = $kernel;
		parent::__construct('O3 Crawler', '1.0');

        $this->getDefinition()->addOption(new InputOption('--debug', '-d', InputOption::VALUE_NONE, 'Run on debug mode.'));
	}

	public function get($name)
	{
		$command = parent::get($name);

		if($command instanceof ContainerAwareInterface) {
			$command->setContainer($this->getContainer());
		}

		return $command;
	}

	public function getContainer()
	{
		return $this->kernel->getContainer();
	}

	protected function getDefaultCommands()
	{
		$commands = array();
		foreach($this->getContainer()->findTaggedServiceIds('command') as $id => $tags) {
			$commands[]  = $this->getContainer()->get($id);	
		}
		return array_merge(parent::getDefaultCommands(), $commands);
	}

    public function run(InputInterface $input = null, OutputInterface $output = null)
	{
        if (null === $output) {
            $output = new ConsoleOutput();
        }

		if(($output instanceof ConsoleOutput) && $this->getContainer()->has('logger')) {
			// set ConsoleHandler
			$this->getContainer()->get('logger')->pushHandler(new ConsoleHandler($output));
		}

		return parent::run($input, $output);
	}
    
    public function getKernel()
    {
        return $this->kernel;
    }
}
