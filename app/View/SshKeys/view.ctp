<?= $this->Bootstrap->page_header($this->request->data['User']['name']) ?>

<div class="row">
    <div class="span2">
        <?= $this->element('users_sidebar', array('action' => 'viewkeys')) ?>
    </div>

    <div class="span6 offset1 well">
        <h3>Current SSH keys</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>
                        Comment
                    </th>
                    <th>
                        Key
                    </th>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->request->data['SshKey'] as $key) {
                    echo "<tr>";
                    echo "<td>" . $key['comment'] . "</td>";
                    echo "<td  style='overflow: hidden; max-width: 388px; word-wrap: break-word;'>" . $key['key'] . "</td>";
                    echo "<td>" . $this->Bootstrap->button_form("Delete", $this->Html->url(array('controller' => 'sshKeys', 'action' => 'delete' , $key['id']), true), array('style' => 'danger'), "Are you sure you want to delete the SSH key '" . $key['comment'] . "'?") . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>