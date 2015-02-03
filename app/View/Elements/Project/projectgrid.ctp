<?php
if (!empty($projects)){
	$count = 0;
	echo '<div class="row-fluid">';
	foreach ($projects as $project){
		// TODO spurious extra row but meh, don't think I care
		if ($count % 3 == 0) {
			echo '</div>';
			echo '<div class="row-fluid">';
		}
		echo $this->Element('Project/block', array('project' => $project));
		$count++;
	}
	echo '</div>';
	echo '<div class="row-fluid">';
    	echo $this->element('pagination'); 
	echo '</div>';
} else {
	echo $this->element('Project/noprojectsalert', array('span' => true));
}
