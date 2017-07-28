<?php

namespace Drupal\quiz\Controller;

use Drupal\Core\Controller\ControllerBase;

class quizController extends ControllerBase {

	/**
	 * Display the markup.
	 *
	 * @return array
	 */
	public function content() {
		return array(
			'#theme'   => 'quiz',
			'#content' => 'from quz Controller',
		);
	}

}