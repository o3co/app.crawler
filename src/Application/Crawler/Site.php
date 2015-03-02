<?php
namespace Application\Crawler;

use O3Com\Crawler\Site as BaseSite;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface,
	Psr\Log\NullLogger
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
abstract class Site extends BaseSite implements LoggerAwareInterface 
{
	/**
	 * logger 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $logger;
    
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
			$this->init(array_merge($loader->load($this->getSiteName()), $configs));
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
}

