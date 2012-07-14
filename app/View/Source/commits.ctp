<?php
/**
 *
 * View class for APP/Source/commits for the DevTrack system
 * Allows users to view commits
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Source
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->set('css_for_layout', array('pages/source'));

$smallText = " <small>" . $project['Project']['description'] . " </small>";
$pname = $project['Project']['name'];

// Base url for the view
$url = array('project' => $project['Project']['name'], 'action' => 'commits', $branch);
$this->Bootstrap->add_crumb($project['Project']['name'], $url);
$this->Bootstrap->add_crumb("Commit History", $url);

// Header for the page
echo $this->Bootstrap->page_header($pname . $smallText);

?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Source/topbar', array('branches' => $branches, 'branch' => $branch)) ?>
        <div class="span10">
            <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
        </div>
        <div class="span10">
            <table class="well table table-striped">
            <? foreach ($commits as $commit) : ?>
                <? $text = ucfirst((strlen($commit['Commit']['subject']) > 100) ? substr($commit['Commit']['subject'], 0, 100).'...' : $commit['Commit']['subject']); ?>
                <tr>
                    <td>
                        <h4><?= $this->Html->link($text, array('project'=>$project['Project']['name'],'action'=>'commit',$commit['Commit']['hash'])) ?></h4>
                        <h5><?= $commit['Commit']['author']['name'].' &lt;'.$commit['Commit']['author']['email'].'&gt;' ?> <small>authored <?= $this->Time->timeAgoinWords($commit['Commit']['date']) ?></small></h5>
                    </td>
                </tr>
            <? endforeach; ?>
            </table>
            <ul class="pager">
                <? if ($page > 1) : ?>
                <li class="previous">
                    <?= $this->Html->link('&larr; Newer', array('project' => $project['Project']['name'], 'action' => 'commits', $branch, 'page' => ($page - 1)), array('escape' => false)) ?>
                </li>
                <? endif; ?>
                <? if ($more_pages) : ?>
                <li class="next">
                    <?= $this->Html->link('Older  &rarr;', array('project' => $project['Project']['name'], 'action' => 'commits', $branch, 'page' => ($page + 1)), array('escape' => false)) ?>
                </li>
                <? endif; ?>
            </ul>
        </div>
    </div>
</div>
