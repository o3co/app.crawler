<?php
namespace Application\Crawler;

use O3Com\Crawler\SiteRegistry as BaseRegistry;
use Application\Crawler\Config\Loader;
use Application\Crawler\Exception\ClassNotFoundException;

class SiteRegistry extends BaseRegistry  
{
	public function __construct(Loader $loader)
	{
		$sites = $loader->load('sites');
		foreach($sites['sites'] as $name => $class) {
			if(!class_exists($class)) {
				throw new ClassNotFoundException($class);
			}
			$this->addSite($name, new $class());
		}
	}
}

