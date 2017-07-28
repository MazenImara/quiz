<?php
namespace Drupal\quiz\Ajax;
use Drupal\core\Ajax\CommandInterface;

/**
* 
*/
class SlideDownCommand extends CommandInterface
{
	// constructs an slidedownCommand object 
	public function __construct($selector, $duration = NULL)
	{
		$this->selector = $selector;
		$this->duration = $duration;
	}

	// implements Drupal commandinterface:render()
	public public function render()
	{
		return array(
			'command' => 'slideDown',
			'method' => NULL,
			'selector' => $this->selector,
			'duration' => $this->duration,
		);
	}
}