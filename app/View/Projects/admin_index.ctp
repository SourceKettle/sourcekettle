<?php
/**
 *
 * View class for APP/projects/admin_index for the DevTrack system
 * View will render lists of projects
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Projects
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Administration <small>da vinci code locator</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('admin_sidebar') ?>
    </div>
    <div class="span10">
       <div class="row-fluid">
            <div class="well">
            <h3>Search for a Project</h3>
            <form class="well form-search">
                <input type="text" class="span12 search-query" placeholder="Search for a project">
            </form>
            <table class="table table-striped">
                <tbody>
                <? foreach ( $projects as $project ) : ?>
                    <tr>
                        <td>
                            <?= $this->Html->link($project['Project']['name'], array('action' => 'view', $project['Project']['id']))?>
                        </td>
                        <td>
                        <?php
                            echo $this->Bootstrap->button_form(
                                $this->Bootstrap->icon('eject', 'white')." Remove",
                                $this->Html->url(array('controller' => 'projects', 'action' => 'admin_delete', $project['Project']['id']), true),
                                array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => 'pull-right'),
                                "Are you sure you want to delete " . $project['Project']['name'] . "?"
                            );
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
</div>
