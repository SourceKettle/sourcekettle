<div class="row-fluid">
    <div class="span8">
        <?php
        echo $this->Form->create('Milestone', array('class' => 'well form-horizontal'));

	// If we've been given a list of tasks to be added, add hidden fields for them
	if (isset($this->request->data['Task'])) {
		foreach ($this->request->data['Task'] as $publicId) {
			echo $this->Form->hidden("Task", array("name" => "data[Task][]", "value" => $publicId));
		}
	}

        echo $this->Bootstrap->input("subject", array(
            "input" => $this->Form->text("subject", array("class" => "span12", "placeholder" => __("e.g. Sprint 1"), "autofocus" => "")),
            "label" => __("Short name"),
        ));

        echo $this->Bootstrap->input("description", array(
            "input" => $this->Form->textarea("description", array("placeholder" => __("Overall goals of the milestone"), 'class' => 'span12')),
            "label" => __("Description")
        ));

        echo "<br>";

		// If we have a start date use it, otherwise default to today
		$startDate = date('Y-m-d', time());
		if(isset($this->data['Milestone']['starts']) && $this->data['Milestone']['starts'] != '0000-00-00'){
			$startDate = $this->data['Milestone']['starts'];
		}

		// If we have a due date use it, otherwise default to 1 week in the future
		$dueDate = date('Y-m-d', time() + (7 * 24 * 60 * 60));
		if(isset($this->data['Milestone']['due'])){
			$dueDate = $this->data['Milestone']['due'];
		}

		echo $this->element('datepicker', array(
			'name' => 'starts',
			'classes' => array('span12'),
			'value' => $startDate,
			'label' => __("Start date"),
			'helpBlock' => __("When work on the milestone will begin"),
		));

		echo $this->element('datepicker', array(
			'name' => 'due',
			'classes' => array('span12'),
			'value' => $dueDate,
			'label' => __("Completion date"),
			'helpBlock' => __("When work on the milestone will end"),
		));

        echo $this->Bootstrap->button(__("Submit"), array("style" => "primary", "size" => "normal", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>

    <div class="span4">
        <h3><?=__("What is a 'Milestone'?")?></h3>
        <div>
            <p><?=__('A Milestone (or "sprint", or "timebox") is a period of time during which a set of tasks will be completed. A Milestone can help to prevent you, and your team mates, from straying from the path of progress.')?></p>
			<ul>
            <li><strong><?=__('Step 1)')?></strong> <?=__('Pick a date to start work, and a target date for completion')?></li>
            <li><strong><?=__('Step 2)')?></strong> <?=__('Plan your milestone before the start date: add tasks that you want to get done')?></li>
            <li><strong><?=__('Step 3)')?></strong> <?=__('Prioritise your tasks in the "plan" view and assign work to your team')?></li>
            <li><strong><?=__('Step 4)')?></strong> <?=__('Stick to your target completion date: drop lower-priority tasks if you need to')?></li>
			</ul>
        </div>
    </div>
</div>
