<div class="row-fluid">
    <div class="span8">
        <?php
        echo $this->Form->create('Milestone', array('class' => 'well form-horizontal'));

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
		$start_date = date('Y-m-d', time());
		if(isset($this->data['Milestone']['starts']) && $this->data['Milestone']['starts'] != '0000-00-00'){
			$start_date = $this->data['Milestone']['starts'];
		}

		// If we have a due date use it, otherwise default to 1 week in the future
		$due_date = date('Y-m-d', time() + (7 * 24 * 60 * 60));
		if(isset($this->data['Milestone']['due'])){
			$due_date = $this->data['Milestone']['due'];
		}

        echo $this->Bootstrap->input("starts", array(
            "input" => $this->Form->text("starts", array(
                "class" => "dp1",
				"value" => $start_date,
                "data-date-format" => "yyyy-mm-dd")
            ),
            "label" => __("Start date"),
            "help_block" => __("When work on the milestone will begin")
        ));

        echo $this->Bootstrap->input("due", array(
            "input" => $this->Form->text("due", array(
                "class" => "dp1",
				"value" => $due_date,
                "data-date-format" => "yyyy-mm-dd")
            ),
            "label" => __("Completion target"),
            "help_block" => __("When the milestone should be complete")
        ));

        echo $this->Bootstrap->button(__("Submit"), array("style" => "primary", "size" => "normal", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>

    <div class="span4">
        <h3><?=__("What is a 'Milestone'?")?></h3>
        <div>
            <p><?=__('At SourceKettle, we define a Milestone as a target. It guides us in what work should be done by a certain time. A Milestone can help to prevent you, and your team mates, from straying from the path of progress.')?></p>
			<ul>
            <li><strong><?=__('Step 1)')?></strong> <?=__('Pick a date to start work, and a target date for completion')?></li>
            <li><strong><?=__('Step 2)')?></strong> <?=__('Plan your milestone before the start date: add tasks that you want to get done')?></li>
            <li><strong><?=__('Step 3)')?></strong> <?=__('Prioritise your tasks in the "plan" view')?></li>
            <li><strong><?=__('Step 4)')?></strong> <?=__('Stick to your target completion date: drop lower-priority tasks if you need to')?></li>
			</ul>
        </div>
    </div>
</div>
