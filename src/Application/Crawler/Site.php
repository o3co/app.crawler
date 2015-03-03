<?php
namespace Application\Crawler;

use O3Com\Crawler\Site as BaseSite;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface,
	Psr\Log\NullLogger
;

use Symfony\Component\DependencyInjection\ContainerInterface,
	Symfony\Component\DependencyInjection\ContainerAwareInterface
;

/**
 * Site 
 * 
 * @uses BaseSite
 * @package { PACKAGE }
 * @copyright Copyrights (c) 1o1.co.jp, All Rights Reserved.
 * @author Yoshi<yoshi@1o1.co.jp> 
 * @license { LICENSE }
 */
abstract class Site extends BaseSite implements LoggerAwareInterface, ContainerAwareInterface 
{
	private $container;

	/**
	 * logger 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $logger;

	private $debug;
    
    /**
     * getLogger 
     * 
     * @access public
     * @return void
     */
    public function getLogger()
    {
		if(!$this->logger) {
			$this->logger = new NullLogger();
		}
        return $this->logger;
    }
    
    /**
     * setLogger 
     * 
     * @param LoggerInterface $logger 
     * @access public
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

	/**
	 * initWithLoader 
	 * 
	 * @param mixed $loader 
	 * @access public
	 * @return void
	 */
	public function initWithLoader($loader, array $configs = array())
	{
		if($loader->canLoad($this->getSiteName())) {
			$configs = array_merge($loader->load($this->getSiteName()), $configs);

			$containerParameters = clone $this->getContainer()->getParameterBag();
			$containerParameters->add($configs);
			$containerParameters->resolve();

			$this->init($containerParameters->all());
		}
	}

	/**
	 * getSiteName 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function getSiteName();


	public function isDebug()
	{
		return (bool)$this->debug;
	}

	public function enableDebug()
	{
		$this->debug = true;
	}

    public function getContainer()
    {
        return $this->container;
    }
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        return $this;
    }

}

