<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppKernel 
{
	private $container;

	private $env;

	private $debug;

	public function __construct($env, $debug = false)
	{
		$this->initContainer();

		$this->env = $env;
		$this->debug = (bool)$debug;
	}

	protected function getDirs()
	{
		return array(
				'kernel.root_dir' => __DIR__,
				'kernel.config_dir' => __DIR__ . '/config',
				'kernel.log_dir' => __DIR__ . '/../logs',
			);
	}

	protected function initContainer()
	{
		$this->container = new ContainerBuilder();
		
		foreach($this->getDirs() as $key => $value) {
			$this->container->setParameter($key, $value);
		}

		$loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/config'));
		$loader->load('services.yml');
	}
    
    public function getContainer()
    {
        return $this->container;
    }
    
    public function getEnvironment()
    {
        return $this->env;
    }
    
    public function isDebug()
    {
        return (bool)$this->debug;
    }
}

