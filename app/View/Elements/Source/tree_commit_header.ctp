    <div class="row-fluid">
        <div class="span12">
            <div class="well well-small">
                <div class="row-fluid">
                    <div class="span1">
                        <?= $this->Gravatar->image($commit['author']['email'], array(), array('class' => 'pull-right thumbnail')) ?>
                    </div>
                    <div class="span11">
                        <h4>
                            <?= $this->Text->truncate($this->Source->linkStringToTasks(
									$commit['subject'],
									$project['Project']['name'],
									array(
										'project' => $project['Project']['name'], 'action' => 'commit', $commit['hash']
									)
								), 50, array('exact' => false, 'html' => true)
							)?>
                            <small> modified <?= $this->Time->timeAgoinWords($commit['date']) ?></small>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
