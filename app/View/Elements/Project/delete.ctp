<?php
/**
 *
 * Page for deletion confirmation in the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Project
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->Html->css('deletable', null, array ('inline' => false));
?>

<?= $this->Bootstrap->page_header('Are you sure you want to delete?') ?>
<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <div class="span10">
            <div class="row-fluid">
                <div class="well span8 offset2 deletable">
                    <?php
                        echo "<h4>";
                        if (sizeof($objects) < 1) {
                            echo "Deleting '".$object['name']."' is irreversable!";
                        } else {
                            echo "If you delete '".$object['name']."' you will also be deleting:";
                        }
                        echo "</h4>";
                        echo "<ul class='span6 offset3'>";
                        if (isset($objects['Collaborator'])) {
                            $size = sizeof($objects['Collaborator']);
                            $adj = 'collaborator';
                            if ($size > 1) {
                                $adj = Inflector::pluralize($adj);
                            }
                            if ($size > 100) {
                                $size = '+100';
                            } else if ($size > 50) {
                                $size = '+50';
                            } else if ($size > 20) {
                                $size = '+20';
                            }

                            if ($size > 0) {
                                echo "<li>A project with $size $adj</li>";
                            }
                        }

                        if (isset($objects['Attachment'])) {
                            $size = sizeof($objects['Attachment']);
                            $adj = 'attachment';
                            if ($size > 1) {
                                $adj = Inflector::pluralize($adj);
                            }
                            if ($size > 100) {
                                $size = '+100';
                            } else if ($size > 50) {
                                $size = '+50';
                            } else if ($size > 20) {
                                $size = '+20';
                            }

                            if ($size > 0) {
                                echo "<li>$size $adj</li>";
                            }
                        }

                        if (isset($objects['Time'])) {
                            $size = sizeof($objects['Time']);
                            $adj = 'piece';
                            if ($size > 1) {
                                $adj = Inflector::pluralize($adj);
                            }
                            if ($size > 100) {
                                $size = '+100';
                            } else if ($size > 50) {
                                $size = '+50';
                            } else if ($size > 20) {
                                $size = '+20';
                            }

                            if ($size > 0) {
                                echo "<li>$size $adj of allocated time</li>";
                            }
                        }

                        if (isset($objects['Task'])) {
                            $size = sizeof($objects['Task']);
                            $size2 = sizeof($objects['TaskComment']);
                            $adj  = 'task';
                            $adj2 = 'comment';
                            if ($size > 1) {
                                $adj = Inflector::pluralize($adj);
                            }
                            if ($size2 > 1) {
                                $adj2 = Inflector::pluralize($adj2);
                            }
                            if ($size > 100) {
                                $size = '+100';
                            } else if ($size > 50) {
                                $size = '+50';
                            } else if ($size > 20) {
                                $size = '+20';
                            }
                            if ($size2 > 100) {
                                $size2 = '+100';
                            } else if ($size2 > 50) {
                                $size2 = '+50';
                            } else if ($size2 > 20) {
                                $size2 = '+20';
                            }
                            if ($size > 0 & $size2 >0) {
                                echo "<li>$size $adj with $size2 $adj2</li>";
                            } else if ($size > 0) {
                                echo "<li>$size $adj</li>";
                            }
                        }

                        if (isset($objects['Milestone'])) {
                            $size = sizeof($objects['Milestone']);
                            $adj = 'milestone';
                            if ($size > 1) {
                                $adj = Inflector::pluralize($adj);
                            }
                            if ($size > 100) {
                                $size = '+100';
                            } else if ($size > 50) {
                                $size = '+50';
                            } else if ($size > 20) {
                                $size = '+20';
                            }

                            if ($size > 0) {
                                echo "<li>$size $adj</li>";
                            }
                        }

                        $url = array(
                            "controller" => $this->request['controller'],
                            "action" => $this->request['action'],
                            "project" => $this->request['project'],
                        );
                        if (isset($object['id'])) {
                            $url[] = $object['id'];
                        }
                        echo "</ul>";
                        echo $this->Bootstrap->button_form(
                            "I'm super sure. Delete!",
                            $url,
                            array(
                                "style" => "danger",
                                "size" => "large",
                                "class" => "deleteButton span12"
                            )
                        );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
