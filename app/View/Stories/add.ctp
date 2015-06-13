	<h3><?=__('Create a user story')?></h3>
		<?php
		echo $this->Form->create('Story', array('class' => 'well form-horizontal'));
		echo $this->Bootstrap->input("subject", array(
			"input" => $this->Form->text("subject", array("class" => "span9", "placeholder" => __('As a ... I want ... so that ...'), "maxlength" => 255, "autofocus" => "")),
			"label" => __('Subject'),
			"help_inline" => __("255 characters max")
		));
		echo $this->Bootstrap->input("description", array(
			"input" => $this->Form->textarea("description", array("class" => "span9", "rows" => 12, "placeholder" => __('Textual description of the story'))),
			"label" => __('Description'),
		));
		echo $this->Bootstrap->input("acceptance_criteria", array(
			"input" => $this->Form->textarea("acceptance_criteria", array("class" => "span9", "rows" => 12, "placeholder" => __('How do your testers confirm the story is complete? Possibly a list of "I can ..." or Gherkin syntax for more formal criteria'))),
			"label" => __('Acceptance criteria'),
		));
		echo $this->Bootstrap->button(__('Save'), array("style" => "primary", 'class' => 'controls'));
		echo $this->Form->end();
