 <?= $this->Bootstrap->page_header('Administration <small>who\'s working together?</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
        <div class="row-fluid">

            <?php
                $_promote_icon = $this->Bootstrap->icon('arrow-up');
                $_demote_icon  = $this->Bootstrap->icon('arrow-down');
                $_delete_icon  = $this->Bootstrap->icon('eject', 'white');
                $_edit_icon    = $this->Bootstrap->icon('pencil');
                echo $this->Form->create('User',
                    array(
                        'class' => 'form-inline input-append'
                    )
                );

                echo $this->element('components/team_typeahead_input',
                    array(
                        'name' => 'name',
                        'properties' => array(
                            'id' => 'appendedInputButton',
                            'class' => 'span11',
                            "placeholder" => __("Start typing to search..."),
                            'label' => false,
							'autocomplete' => 'off'
                        )
                    )
                );
                echo $this->Bootstrap->button('Search', array('escape' => false, 'style' => 'primary'));

                echo $this->Form->end();
            ?>
            <table class="well table table-striped">
                <thead>
                    <tr>
                        <th width="85%"><?=__("Team name/description")?></th>
                        <th><?=__("Actions")?></th>
                    </tr>
                </thead>
				<tbody>
                <? foreach ( $teams as $team ) : ?>
                    <tr>
                        <td>
                            <?= $this->Html->link(
								$team['Team']['name'] . ' (' . $team['Team']['description'] . ')',
								array('action' => 'view', $team['Team']['id'])
							)?>
                        </td>
                        <td>
                        <?php
								$_edit_url = $this->Html->url(
									array(
											'controller' => 'teams',
											'action' => 'admin_edit',
											$team['Team']['id']
										 ), true
								);

                                $_delete_url = $this->Html->url(
									array(
										'controller' => 'teams',
										'action' => 'admin_delete',
										$team['Team']['id']
									), true
								);


							echo "<div class='btn-group'>\n";
                            echo $this->Bootstrap->button_link(
								$_edit_icon, $_edit_url,
                                array('escape'=>false, 'size' => 'mini', 'title' => __('Edit team details'))
                            );

                            echo $this->Bootstrap->button_form(
								$_delete_icon, $_delete_url,
                                array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => '', 'title' => __('Delete team')),
                                __("Are you sure you want to delete")." " . h($team['Team']['name']) . "?"
                            );
							echo "</div>\n";
                        ?>
                        </td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
            <?= $this->element('pagination') ?>

		</div>
	</div>
</div>
