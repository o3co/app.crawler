<?php
namespace Application\Crawler\Config\Loader;

use Application\Crawler\Config\Loader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;

use Application\Crawler\Exception\FileNotExistsException;

class YamlFileLoader implements Loader 
{
	private $locator;

	public function __construct(FileLocator $locator)
	{
		$this->locator = $locator;
	}

	public function canLoad($resource)
	{
		$locator = $this->getLocator();

		try {
			$locator->locate($resource);
		} catch(\InvalidArgumentException $ex) {
			try {
				$locator->locate($resource . '.yml');
			} catch(\InvalidArgumentException $ex) {
				return false;
			}
		}

		return true;
	}

	public function load($resource)
	{
		$locator = $this->getLocator();
		try {
			$path = $locator->locate($resource);
		} catch(\InvalidArgumentException $ex) {
			try {
				$path = $locator->locate($resource . '.yml');
			} catch(\InvalidArgumentException $ex) {
				throw new FileNotExistsException(sprintf('File for resource "%s" is not exists.', $resource));
			}
		}

		return Yaml::parse($path);
	}
    
    public function getLocator()
    {
        return $this->locator;
    }
}

