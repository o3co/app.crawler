<?php
namespace Application\Crawler\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface,
	Symfony\Component\Console\Input\InputArgument,
	Symfony\Component\Console\Input\InputOption
;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\FileLocator;
use Application\Crawler\Config\Loader\YamlFileLoader;
use Psr\Log\LoggerAwareInterface;
use O3Com\Crawler\Client;
use O3Com\Crawler\Exception\TerminateException;

// Guzzle Log
use GuzzleHttp\Subscriber\Log\LogSubscriber;
use GuzzleHttp\Subscriber\Log\Formatter;

use Symfony\Component\DependencyInjection\ContainerInterface,
	Symfony\Component\DependencyInjection\ContainerAwareInterface
;

class CrawlerExecuteCommand extends Command implements ContainerAwareInterface 
{
	private $container;

	protected function configure()
	{
		$this
			->setName('crawler:execute')
			->setDefinition(array(
					new InputArgument('name', InputArgument::REQUIRED, 'Traverser name'),
				))
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = $input->getArgument('name');
		$container = $this->getContainer();
		$registry = $container->get('crawler.site_registry');
		$traverser = $registry->getSite($name);
		$client = new Client();

		if($traverser instanceof ContainerAwareInterface) {
			$traverser->setContainer($this->getContainer());
		}
		
		if($container->has('logger') && ($traverser instanceof LoggerAwareInterface)) {
			$logger = $container->get('logger');

			$traverser->setLogger($logger);

			// Get verbose level
			if($output->isDebug()) {
				$client->getClient()->getEmitter()->attach(new LogSubscriber($logger, Formatter::CLF));
			}
		}

		$traversalConfigs = array();
		$traverserConfigs = array();

		if($input->hasOption('verbose')) {
			$traverserConfigs['verbose'] = $input->getOption('verbose');
		}

		$traverserConfigs['output'] = $output;

		// initialize with configuration loader
		$loader = new YamlFileLoader(new FileLocator($container->getParameter('kernel.config_dir')));

		$traverser->initWithLoader($loader, $traverserConfigs);


		try {
			$traverser->traverse($client, $traversalConfigs);
		} catch(TerminateException $ex) {
			$prev = $ex->getPrevious();
			if($prev){
				throw $prev;
			}

			if($this->getContainer()->has('logger')) {
				$this->getContainer()->get('logger')->info($ex->getMessage());
			}
		}
	}
    
    public function getContainer()
    {
		if(!$this->container) {
			throw new \Exception('Container is not initialized');
		}
        return $this->container;
    }
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        return $this;
    }
}

