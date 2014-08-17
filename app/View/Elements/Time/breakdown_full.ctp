
<?=$this->Html->script('jquery.color-2.1.2.min', array('inline' => false))?>
<?=$this->Html->script('jquery.flot.min', array('inline' => false))?>
<?=$this->Html->script('jquery.flot.pie.min', array('inline' => false))?>
<?=$this->Html->script('times.breakdown', array('inline' => false))?>
<?=$this->Html->css('time.breakdown', null, array('inline' => false))?>

<div class="span6">
    <div class="well" id="piechart">
    </div>
</div>

<div class="span6">
    <table id="usertimes" class="well table table-striped pull-right">
        <thead>
            <tr>
                <th><?= __('User') ?></th>
                <th><?= __('Total time') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($users as $user) {
                echo "<tr><td>";
                echo $this->Html->link(
                    $this->Gravatar->image(
                        $user['User']['email'],
                        array('size' => 24),
                        array('alt' => $user['User']['name'])
                    ),
                    array('controller' => 'users', 'action' => 'view', $user['User']['id']),
                    array('escape' => false)
                );
                echo ' <span class="userlink">';
                echo $this->Html->link(
                    $user['User']['name'],
                    array('controller' => 'users', 'action' => 'view', $user['User']['id'])
                );
                echo "</span> ";
                echo "<small>".$this->Html->link(
                    "[view time log]",
                    array('controller' => 'times', 'action' => 'userlog', 'project' => $project['Project']['name'], $user['User']['id'])
                )."</small>";
				echo "</td><td data-minutes=\"".h($user['Time']['time']['t'])."\">";
                echo h($user['Time']['time']['s']);
                echo "</td></tr>";
            }
        ?>
        </tbody>
    </table>
</div>


