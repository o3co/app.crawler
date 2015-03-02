<?php
namespace Application\Guzzle\Subscriber;

use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\SubscriberInterface;

class IntervalSubscriber implements SubscriberInterface 
{
	private $interval;

	private $latest;

    public function __construct($interval = 1000)
	{
		$this->interval = $interval;
	}

    public function getEvents()
    {
        return [
            'before' => ['onRequest', RequestEvents::PREPARE_REQUEST],
        ];
    }

	public function onRequest(BeforeEvent $event)
	{
		if(!$this->latest) {
			$this->latest = microtime();
		} else {
			$diff = microtime() - $this->latest;
			if($diff < $this->interval) {
				usleep($this->interval - $diff);
			}

			$this->latest = microtime();
		}	
	}
}

