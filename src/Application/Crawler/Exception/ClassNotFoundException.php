<?php
namespace Application\Crawler\Exception;

/**
 * ClassNotFoundException 
 * 
 * @package { PACKAGE }
 * @copyright Copyrights (c) 1o1.co.jp, All Rights Reserved.
 * @author Yoshi<yoshi@1o1.co.jp> 
 * @license { LICENSE }
 */
class ClassNotFoundException extends \RuntimeException 
{
	/**
	 * __construct 
	 * 
	 * @param mixed $class 
	 * @param int $code 
	 * @param \Exception $prev 
	 * @access public
	 * @return void
	 */
	public function __construct($class, $code = 0, \Exception $prev = null)
	{
		parent::__construct(sprintf('Class "%s" is not found.', $class), $code, $prev);
	}
}

