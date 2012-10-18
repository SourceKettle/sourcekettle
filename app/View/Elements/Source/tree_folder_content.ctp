<div class="span10">
    <table class="well table table-striped">
        <tr>
            <th><?= $this->Bootstrap->icon(null) ?> name</th>
            <th>edited</th>
            <th>message</th>
        </tr>
<?php
    foreach ($files as $file) {
        if (is_array($file)) {
?>
        <tr>
            <td>
                <?= $this->Source->fetchIcon($file['type']) ?>
                <?php
                    if ($file['type'] != 'commit') {
                        echo $this->Html->link($file['name'], $this->Source->fetchTreeUrl($project['Project']['name'], $branch, $file['path']), array('escape' => false));
                    } else {
                        if ($file['remote'] != ''){
                            echo $this->Html->link($file['name'], 'http://'.$file['remote'], array('escape' => false));
                        } else {
                            echo $file['name'];
                        }
                    }
                ?>
            </td>
            <td>
                <?php
                    echo $this->Time->timeAgoInWords($file['updated']['date']);
                ?>
            </td>
            <td>
                <?php
                    echo $this->Text->truncate($file['updated']['subject'], 100, array('exact' => false, 'html' => false));
                ?>
            </td>
        </tr>
<?php
        }
    }
?>
    </table>
</div>
