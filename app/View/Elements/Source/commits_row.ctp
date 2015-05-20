<div class="row-fluid commitsRow">
    <div class="span1">
        <?= $this->Gravatar->image($commit['author']['email'], array(), array('class' => 'pull-right thumbnail')) ?>
    </div>
    <div class="span9">
        <p>
            <strong>
                <?= $this->Text->truncate($this->Source->linkStringToTasks(
						$commit['subject'],
						$project['Project']['name'],
						array(
							'project' => $project['Project']['name'], 'action' => 'commit', $commit['hash']
						)
					), 50, array('exact' => false, 'html' => true)
				)?>
            </strong>
            <br>
            <small class="muted">
                <?= h($commit['author']['name']).' &lt;'.h($commit['author']['email']).'&gt;' ?>
                authored <?= $this->Time->timeAgoinWords($commit['date']) ?>
            </small>
        </p>
    </div>
    <div class="span2">
        <div class="btn-group pull-right">
            <?php
                echo $this->Bootstrap->button_link(($path != '') ? 'View File' : 'View Tree',
                    $this->Source->fetchTreeUrl($project['Project']['name'], $commit['hash'], $path),
                    array('size' => 'small', "class" => "btn")
                );
            ?>
        </div>
    </div>
</div>
