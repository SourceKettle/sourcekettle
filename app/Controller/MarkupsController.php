<?php
App::uses('AppController', 'Controller');
/**
 * Markups Controller
 *
 */
class MarkupsController extends AppController {

	public function preview() {
		$this->layout = 'ajax';
		$content = '';
		if (isset($this->request->query['data'])) {
			$content = $this->request->query['data'];
		} elseif (isset($this->request->data)) {
			$content = $this->request->data;
		}
		$this->set(compact('content'));
		$this->render('/Elements/Markitup/preview');
	}

}
