<?php
/**
 *
 * View class for APP/collaborators/index for the DevTrack system
 * Allows modification of collaborators
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Collaborators
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$smallText = " <small>" . $project['Project']['description'] . " </small>";
$pname = $project['Project']['name'];

echo $this->Bootstrap->page_header($pname . $smallText);?>

<div class="row">
    <div class="span2">
        <?= $this->element('project_sidebar', array('project' => $pname, 'action' => 'collaborators')) ?>
    </div>
    <div class="row">
        <div class="span10">
            <div class="well">
                <h3>Source</h3>
                <table class="table table-striped">
                <? foreach ($source_files as $source_file) : ?>
                <tr>
                    <td>
                    <?php
                    $source_file['url']['project'] = $project['Project']['name'];
                    $source_file['url']['action'] = 'index';
                    echo $this->Html->link(
                        $source_file['name'], 
                        $source_file['url'], 
                        array('escape' => false)
                    ); 
                    ?></td>
                </tr>
                <? endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
