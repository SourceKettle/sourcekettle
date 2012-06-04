<?php
/**
 *
 * Pagination Helper for the DevTrack system
 * Will create Bootstrap compliant pagination blocks
 *
 * Original Code found at: https://gist.github.com/1263853
 * Original Designed By: @slywalker
 * Modified By: @pwhittlesea
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 
if (!isset($modules)) {
	$modulus = 11;
}
if (!isset($model)) {
	$model = Inflector::classify($this->params['controller']);
}
?>
<div class="pagination pagination-centered">
	<ul>
		<?php echo $this->Paginator->first('<<', array('tag' => 'li')); ?>
		<?php echo $this->Paginator->prev('<', array(
			'tag' => 'li',
			'class' => 'prev',
		), $this->Paginator->link('<', array()), array(
			'tag' => 'li',
			'escape' => false,
			'class' => 'prev disabled',
		));
		$page = $this->params['paging'][$model]['page'];
		$pageCount = $this->params['paging'][$model]['pageCount'];
		if ($modulus > $pageCount) {
			$modulus = $pageCount;
		}
		$start = $page - intval($modulus / 2);
		if ($start < 1) {
			$start = 1;
		}
		$end = $start + $modulus;
		if ($end > $pageCount) {
			$end = $pageCount + 1;
			$start = $end - $modulus;
		}
		for ($i = $start; $i < $end; $i++) {
			$url = array('page' => $i);
			$class = null;
			if ($i == $page) {
				$url = array();
				$class = 'active';
			}
			echo $this->Html->tag('li', $this->Paginator->link($i, $url), array(
				'class' => $class,
			));
		}
		?>
		<?php echo $this->Paginator->next('>', array(
			'tag' => 'li',
			'class' => 'next',
		), $this->Paginator->link('>', array()), array(
			'tag' => 'li',
			'escape' => false,
			'class' => 'next disabled',
		)); ?>
		<?php echo str_replace('<>', '', $this->Html->tag('li', $this->Paginator->last('>>', array(
			'tag' => null,
		)), array(
			'class' => 'next',
		))); ?>
	</ul>
</div>