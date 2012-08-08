<div class="span3">
    <div class="well">
        <div class="span4">
            <?= $this->Html->link($this->Gravatar->image($email, array('size' => 40), array('alt' => $name)), array('controller' => 'users', 'action' => 'view', $id), array('escape' => false)) ?>
        </div>
        <div>
            <p>
                <?= $this->Html->link($name, array('controller' => 'users', 'action' => 'view', $id)) ?>
            </br>
            <?= $this->Bootstrap->label($time['time']['s'], "warning") ?>
            </p>
        </div>
    </div>
</div>
