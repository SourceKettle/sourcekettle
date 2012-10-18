<div class="span10">
    <div class="row-fluid">
        <div class="span12">
            <div class="well well-small">
                <div class="row-fluid">
                    <div class="span1">
                        <?= $this->Gravatar->image($commit['author']['email'], array(), array('class' => 'span10 pull-right thumbnail')) ?>
                    </div>
                    <div class="span11">
                        <h4>
                            <?= $this->Html->link(
                                $this->Text->truncate($commit['subject'], 50, array('exact' => false, 'html' => false)),
                                array('project' => $project['Project']['name'], 'action' => 'commit', $commit['hash'])
                            ) ?>
                            <small> modified <?= $this->Time->timeAgoinWords($commit['date']) ?></small>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>