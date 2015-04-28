	<h3><?=__('Create a user story')?></h3>
		<?php
		echo $this->Form->create('Story', array('class' => 'well form-horizontal'));
		echo $this->Bootstrap->input("subject", array(
			"input" => $this->Form->text("subject", array("class" => "span9", "placeholder" => __('Short name for the story'), "maxlength" => 50, "autofocus" => "")),
			"label" => __('Subject'),
			"help_inline" => __("50 characters max")
		));
		echo $this->Bootstrap->input("subject", array(
			"input" => $this->Form->textarea("description", array("class" => "span9", "rows" => 12, "placeholder" => __('As a ... I want ... so that ...'))),
			"label" => __('Description'),
		));
		echo $this->Bootstrap->button(__('Save'), array("style" => "primary", 'class' => 'controls'));
		echo $this->Form->end();
