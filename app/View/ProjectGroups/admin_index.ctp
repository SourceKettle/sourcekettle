
<div class="row-fluid">
    <?php
        $_delete_icon  = $this->Bootstrap->icon('eject', 'white');
        $_edit_icon    = $this->Bootstrap->icon('pencil');
        echo $this->Form->create('ProjectGroup',
            array(
                'class' => 'form-inline input-append'
            )
        );

       	echo $this->element('typeahead_input',
            array(
					'url' => array(
    				'api' => true,
				    'controller' => 'project_groups',
    				'action' => 'autocomplete',
					),
                'name' => 'name',
					'jsonListName' => 'projectGroups',
                'properties' => array(
                    'id' => 'appendedInputButton',
                    'class' => 'span11',
                    'placeholder' => __("Start typing to search..."),
                    'label' => false,
                )
            )
        );
        echo $this->Bootstrap->button('Search', array('escape' => false, 'style' => 'primary'));

        echo $this->Form->end();
    ?>
    <table class="well table table-striped">
        <thead>
            <tr>
                <th width="85%"><?=__("Project group name/description")?></th>
                <th><?=__("Actions")?></th>
            </tr>
        </thead>

			<tbody>
        <? foreach ( $projectGroups as $projectGroup ) : ?>
            <tr>
                <td>
                    <?= $this->Html->link(
							$projectGroup['ProjectGroup']['name'] . ' (' . $projectGroup['ProjectGroup']['description'] . ')',
							array('action' => 'view', $projectGroup['ProjectGroup']['id'])
						)?>
                </td>
                <td>
                <?php
							$_edit_url = $this->Html->url(
								array(
										'controller' => 'project groups',
										'action' => 'admin_edit',
										$projectGroup['ProjectGroup']['id']
									 ), true
							);

                        $_delete_url = $this->Html->url(
								array(
									'controller' => 'project groups',
									'action' => 'admin_delete',
									$projectGroup['ProjectGroup']['id']
								), true
							);


						echo "<div class='btn-group'>\n";
                    echo $this->Bootstrap->button_link(
							$_edit_icon, $_edit_url,
                        array('escape'=>false, 'size' => 'mini', 'title' => __('Edit project group details'))
                    );

                    echo $this->Bootstrap->button_form(
							$_delete_icon, $_delete_url,
                        array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => '', 'title' => __('Delete project group')),
                        __("Are you sure you want to delete")." " . h($projectGroup['ProjectGroup']['name']) . "?"
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
