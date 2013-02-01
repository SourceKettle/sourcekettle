<div class="span6">
    <div class="well" style="text-align:center">
        <h4><?= $this->DT->t('pie.header') ?></h4>
        <?php
        // Create large pie chart
        $times = $names = array();
        foreach ($users as $user) {
            $times[] = $user['Time']['time']['t'];
            $names[] = $user['User']['name'];
        }
        echo $this->GoogleChart->create()->setType('pie', array('3d'))->setSize(600, 220)->setMargins(0, 0, 0, 0)->addData($times)->setPieChartLabels($names);
        ?>
        <h5><small><?= str_replace(array('{hours}', '{mins}'), array($total_time['h'], $total_time['m']), $this->DT->t('pie.total')) ?></small></h5>
    </div>
</div>
<div class="span6">
    <table class="well table table-striped pull-right">
        <thead>
            <tr>
                <th><?= $this->DT->t('table.header.user') ?></th>
                <th><?= $this->DT->t('table.header.time') ?></th>
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
                echo ' ';
                echo $this->Html->link(
                    $user['User']['name'],
                    array('controller' => 'users', 'action' => 'view', $user['User']['id'])
                );
                echo "</td><td>";
                echo h($user['Time']['time']['s']);
                echo "</td></tr>";
            }
        ?>
        </tbody>
    </table>
</div>
