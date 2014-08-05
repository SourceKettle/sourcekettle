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

		// If we have a due date use it, otherwise default to 1 week in the future
		$due_date = date('Y-m-d', time() + (7 * 24 * 60 * 60));
		if(isset($this->data['Milestone']['due'])){
			$due_date = $this->data['Milestone']['due'];
		}

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
        <h3>What is a 'Milestone'?</h3>
        <div>
            <p>At SourceKettle, we define a Milestone as a sort of target. It guides us in what work should be done by a certain time. A Milestone can help to prevent you, and your team mates, from straying from the path of progress.</p>
            <br>
            <p><strong>Step 1)</strong> Pick a target, or list of features/things you would like done by a certain time.</p>
            <br>
            <p><strong>Step 2)</strong> Stick to that target; drop less-important features to stay on track</p>
        </div>
    </div>
</div>
