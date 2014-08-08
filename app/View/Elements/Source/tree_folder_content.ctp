<div class="span10">
    <table class="well table table-striped">
        <tr>
            <th><?= $this->Bootstrap->icon(null) ?> <?=__("name")?></th>
            <th><?=__("edited")?></th>
            <th><?=__("message")?></th>
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
                        echo $this->Html->link(
							h($file['name']),
							$this->Source->fetchTreeUrl(
								$project['Project']['name'],
								$branch,
								$file['path']
							),
							array('escape' => false));

                    } else {
                        if ($file['remote'] != ''){
                            echo $this->Html->link(h($file['name']), 'http://'.$file['remote'], array('escape' => false));
                        } else {
                            echo h($file['name']);
                        }
                    }

					if ($file['type'] == 'blob') {
                        echo " ".$this->Html->link(
							$this->Bootstrap->icon('download'),
							$this->Source->fetchRawUrl(
								$project['Project']['name'],
								$branch,
								$file['path']
							),
							array('escape' => false));
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
					echo $this->Gravatar->image(
						$file['updated']['author']['email'],
						array('size' => 24),
						array('title' => $file['updated']['author']['name'].' ('.$file['updated']['author']['email'].')')
					);
					echo " ";

					// NB no h() as truncate() does it for us
                    $subject = $this->Text->truncate(
						$file['updated']['subject'], 100, array('exact' => false, 'html' => false)
					);
					echo $this->Html->link($subject, array(
						'controller' => 'source',
						'action' => 'commit',
						'project' => $project['Project']['name'],
						$file['updated']['hash']
					));
                ?>
            </td>
        </tr>
<?php
        }
    }
?>
    </table>
</div>
