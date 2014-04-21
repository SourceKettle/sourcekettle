<div class="row-fluid commitsRow">
    <div class="span1">
        <?= $this->Gravatar->image($commit['author']['email'], array(), array('class' => 'span10 pull-right thumbnail')) ?>
    </div>
    <div class="span9">
        <p>
            <strong>
                <?= // NB no h() as truncate() is doing this for us
					$this->Html->link(
                    $this->Text->truncate($commit['subject'], 50, array('exact' => false, 'html' => false)),
                    array('project' => $project['Project']['name'], 'action' => 'commit', $commit['hash'])
                )  ?>
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
