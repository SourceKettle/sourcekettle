<?php
/**
 *
 * View class for APP/Source/commit for the SourceKettle system
 * Allows users to view a commit
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
$this->Html->css('pages/diff', null, array ('inline' => false));

$ajaxUrl = $this->Html->url(array(
    'ajax' => true,
    'project' => $project['Project']['name'],
    'action' => 'diff',
    'controller' => 'source'
));
$this->Html->scriptBlock("
    $('.fileDiff').find ('.moreButton').click (function() {
        var details = {};
        details['file'] = $(this).attr('data-file');
        details['parent'] = '{$commit['parent']}';
        details['hash'] = '{$commit['hash']}';
        var element = $(this).parents('.fileDiff');

        $.ajax({
            url: '{$ajaxUrl}',
            type: 'POST',
            data: details,
            success: function(data){
                $(element).html(data);
                $(element).focus();
            }
        });
        return false;
    });
", array('inline' => false));
?>

<?= $this->DT->pHeader() ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Source/topbar_commit') ?>
        <div class="span10">
            <?= $this->element('Source/tree_commit_header_extended') ?>
        </div>
        <div class="span10">
            <div class="row-fluid">
            <?php
                $i = 0;
                foreach ($commit['changeset'] as $file) {
                    if (isset($commit['diff'][$file])) {
                        if (isset($commit['diff'][$file]['folder'])) {
                            echo $this->element('Source/commit_changeset_item_folder', array('file' => $file, 'diff' => $commit['diff'][$file]));
                        } else if (isset($commit['diff'][$file]['hunks'])) {
                            echo $this->element('Source/commit_changeset_item', array('file' => $file, 'diff' => $commit['diff'][$file]));
                        }
                    } else {
                        echo $this->element('Source/commit_changeset_item_ajax', array('file' => $file, 'no' => $i++));
                    }
                }
            ?>
            </div>
        </div>
    </div>
</div>
