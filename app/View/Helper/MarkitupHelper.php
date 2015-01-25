<?php
/**
 *
 * Helper for markup higlighing for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.View.Helper
 * @since		SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class MarkitupHelper extends AppHelper {

	public $helpers = array('Html', 'Form');

	public $vendors = array('markdown' => 'Markdown');

/**
 * editor function.
 *
 * @access public
 * @param mixed $name
 * @param array $settings (default: array())
 * @return void
 */
	public function editor($name, $settings = array()) {
		$default = array(
			'set' => 'markdown',
			'skin' => 'simple',
			'settings' => 'mySettings',
			'parser' => array(
				'controller' => 'markups',
				'action' => 'preview',
				'admin' => false,
				'api' => false,
			)
		);
		$settings = array_merge($default, $settings);

		$this->Html->script('/markitup/jquery.markitup', array('inline' => false));
		$this->Html->css("/markitup/skins/{$settings['skin']}/style", null, array('inline' => false));
		$this->Html->css("/markitup/sets/{$settings['set']}/style", null, array('inline' => false));
		$this->Html->script("/markitup/sets/{$settings['set']}/set", array('inline' => false));

		$id = "MarkItUp_{$name}";

		if (!isset($settings['class'])) $settings['class'] = '';
		$settings['class'] .= " {$id}";

		$textarea = array_diff_key($settings, $default);
		$textarea = array_merge($textarea, array('type' => 'textarea'));

		/*$this->Html->scriptBlock("
			jQuery(function() {
				$('.{$id}').markItUp(
					{$settings['settings']},
					{
						previewParserPath: '" . $this->Html->url($settings['parser']) . "',
						previewAutoRefresh: false,
						previewInElement: '#markitup_input',
						afterInsert: 'afterInsert'
					}
				);
			});
		", array ("inline" => false));*/

		$html = '
		<div class="tabbable tabs-below">
			<div class="tab-content">
				<div class="tab-pane active" id="markitup_edit">' . $this->Form->input($name, $textarea) . '</div>
				<div class="tab-pane" id="markitup_view"><div class="span9" id="markitup_input"></div></div>
			</div>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#markitup_edit" data-toggle="tab">Edit</a></li>
				<li><a href="#markitup_view" data-toggle="tab">Preview</a></li>
			</ul>
		</div>
		';

		return $this->output($html);
	}

/**
 * parse function.
 *
 * @access public
 * @param mixed $content
 * @return void
 */
	public function parse($content) {
		App::import('Vendor', 'Markdown/markdown');
		return Markdown($content);
	}
}
