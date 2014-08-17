<?php
/**
 *
 * View class for APP/Source/commits for the SourceKettle system
 * Allows users to view commits
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Source
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('pages/source', null, array ('inline' => false));

// Base url for the view
$url = array('project' => $project['Project']['name'], 'action' => 'tree', $branch);
$this->Bootstrap->add_crumb($project['Project']['name'], $url);

// Create the base url to be used for all links and add breadcrumbs
foreach (explode('/',$path) as $crumb) {
    $url[] = $crumb;
    $this->Bootstrap->add_crumb($crumb, $url);
}
?>
<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Source/topbar') ?>
        <div class="span10">
            <?= $this->Bootstrap->breadcrumbs(array("divider" => "/")) ?>
        </div>
        <div class="span10">
            <div class="row-fluid">
                <?php
                    $date = null;
                    foreach ($commits as $commit) {
                        $newDate = date('M d, Y', strtotime($commit['date']));
                        if ($date != $newDate) {
                            if ($date != null) {
                                echo '</div>';
                            }
                            $date = $newDate;
                            echo '<div class="well commits">';
                            echo "<div class='dateHeader'><strong>$newDate</strong></div>";
                        }
                        echo $this->element('Source/commits_row', array('commit' => $commit));
                    }
                ?>
            </div>
            <ul class="pager">
                <? if ($page > 1) : ?>
                <li class="previous">
                    <?= $this->Html->link('&larr; Newer',
                        $this->Source->fetchHistoryUrl($project['Project']['name'], $branch, $path, $page - 1),
                        array('escape' => false)
                    ) ?>
                </li>
                <? endif; ?>
                <? if ($more_pages) : ?>
                <li class="next">
                    <?= $this->Html->link('Older  &rarr;',
                        $this->Source->fetchHistoryUrl($project['Project']['name'], $branch, $path, $page + 1),
                        array('escape' => false)
                    ) ?>
                </li>
                <? endif; ?>
            </ul>
        </div>
    </div>
</div>
