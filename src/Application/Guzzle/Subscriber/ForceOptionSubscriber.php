<?php
namespace Application\Guzzle\Subscriber;

use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\SubscriberInterface;

class ForceOptionSubscriber implements SubscriberInterface 
{
	private $options;

    public function __construct(array $config = array())
	{
		$this->options = $config;
	}

    public function getEvents()
    {
        return [
            'before' => ['onRequest', RequestEvents::PREPARE_REQUEST],
        ];
    }

	public function onRequest(BeforeEvent $event)
	{
		$request = $event->getRequest();

		foreach($this->options as $key => $value) {
			$request->getConfig()->set($key, $value);
		}
	}
}

