<?php
namespace Application\Util;

class ArrayTool 
{
	static public function cartesianProduct()
	{
		$arguments = func_get_args();

		return self::cartesianProductIn($arguments);
	}


	static public function cartesianProductIn(array $arguments)
	{
		$result = array();

	    while (list($key, $values) = each($arguments)) {
	        if (empty($values)) {
	     	   continue;
	        }

	        if (empty($result)) {
	     	   foreach($values as $value) {
	     		   $result[] = array($key => $value);
	     	   }
	        } else {
	     	   $append = array();

	     	   foreach($result as &$product) {
	     		   $product[$key] = array_shift($values);

	     		   $copy = $product;

	     		   foreach($values as $item) {
	     			   $copy[$key] = $item;
	     			   $append[] = $copy;
	     		   }

	     		   array_unshift($values, $product[$key]);
	     	   }

	     	   $result = array_merge($result, $append);
	        }
	    }

	    return $result;


	}
}

