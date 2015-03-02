<?php
namespace Application\Crawler\Config;

interface Loader
{
	/**
	 * load 
	 * 
	 * @param mixed $resource 
	 * @access public
	 * @return void
	 */
	function load($resource);

	/**
	 * canLoad 
	 * 
	 * @param mixed $resource 
	 * @access public
	 * @return void
	 */
	function canLoad($resource);
}
