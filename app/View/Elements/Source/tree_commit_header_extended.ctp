<div class="well well-small">
    <div class="row-fluid">
        <div class="span1">
            <?= $this->Gravatar->image($commit['author']['email'], array(), array('class' => 'span10 pull-right thumbnail')) ?>
        </div>
        <div class="span11">
            <div class="row-fluid">
                <div class="span10">
                    <h4>
                        <?= $this->Source->linkStringToTasks($commit['subject'], $project['Project']['name']) ?>
                    </h4>
                    <h5>
                        <small><?= $this->Source->linkStringToTasks($commit['body'], $project['Project']['name']) ?></small>
                    </h5>
                </div>
                <div class="span2">
                <?php
                    echo $this->Bootstrap->button_link('View Tree',
                        $this->Source->fetchTreeUrl($project['Project']['name'], $commit['hash'], ''),
                        array('size' => 'small', "class" => "pull-right btn")
                    );
                ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span10">
                     <p>
                        <?= h($commit['author']['name']).' &lt;'.h($commit['author']['email']).'&gt;' ?>
                        <small class="muted">authored <?= $this->Time->timeAgoinWords($commit['date']) ?></small>
                    </p>
                </div>
                <div class="span2">
                    <p class="pull-right">
                        abbv: <abbr title="<?= h($commit['hash']) ?>"><?= h($commit['abbv']) ?></abbr>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
