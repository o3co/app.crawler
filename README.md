# PHP Crawler with Goutte


`o3com/app-crawler` is a SiteTraverse crawler which use Goutte inside.


## How to use

### 1. Create you own Site defined class
```
class Site extends BaseSite
{
	protected function doInit(array $configs)
	{
		$this
			->visit('http://google.com')
				// in "http://google.com", do
				->form('form')
					// select form and set values on the form
					->set('input[name=keyword]', 'keyword')
					->submit()
						// on the result, scrape the result entities
						->scrape(new ClosureScraper(function($crawler) {

							})
					->back()
					// back to the form, and set next keywork with loop
					->each($configs->get('keywords'))
						->set('input[name=keyword]', function($traversal) {
								return $traversal->get('condition');
							})
						->submit()
						->back()
					->end()
				->end()
			->back()
		;
	}
}
```

### 2. Construct you SiteTraverser and then traverse
```
$traverser = new Site();
$traverser->init(array('keyworkds' => array('keyworkd1', 'keyword2')));
$traverser->traverse();
```

## How to get the response

On Site class, you can set handlers and triggers.

```
->submit()
	->scrape(new ClosureScraper(function($crawler){ 
		
		})
	->scrape(new TextScraper('dt.title'), 'titles')
	->scrape(new GroupScraper('.group', array(
			'title' => $scraperFactory->create('text', '.title'),
			'url'   => $scraperFactory->create('attribute', 'a', 'href'),
		)), 'complex')
	// dump current page into the file.
	->dump($filepath)
	// handle all responses.
	->onResponse(function($traversal) {
			// default Scrape
			$default = $traversal->getContent();

			// complex scraper values
			$complex = $traversal->getContent('complex');
		})
->back()
```


## Download images?

$this
	->addTrigger('downloadImage', function($traversal){ 
		$urls = array();
		$urls = $traversal->getCrawler()->filter('img')->each(function($node) {
				return $node->attr('src');	
			});

		$files = $traversal->getClient()->download($urls);

		foreach($files as $file) {
			$file->move('/path/to/save');
		}
	})
	->visit($url)
		->downloadImage()
	->back();	
