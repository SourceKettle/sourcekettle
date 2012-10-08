<?php
/**
 *
 * View for the application dashboard
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Dashboard
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

  $config = Configure::read('dtcore'); 

  echo $this->Bootstrap->page_header($config['pages']['dashboard']['index']['en']['header.text'] . " <small>" . $config['pages']['dashboard']['index']['en']['header.small.text'] . " " . strtolower($user_name) . "</small>");

  echo $this->element('beta_warning');
  $this->Html->css('tasks.index', null, array ('inline' => false));
?>


<div class="row">
    <div class='span5'>
      <h3>Recent events</h3>
        <? if (!empty($history)){
          ?><div class='alert alert-info'><strong>In progress!</strong> Source code events are not shown here yet.</div><?
          echo $this->element('history', array('context_global' => true, 'events' => $history));
        } else {
          ?><div class='alert alert-info'><strong>No events!</strong> There is no activity related to your account.</div><?
        }?>
    </div>
    <div class="span3">
      <h3>Assigned tasks</h3>
      <? if (!empty($tasks)){
        foreach ($tasks as $task){
          echo $this->element('Task/element_1', array('task' => $task));
        }
      } else {
        ?><div class='alert alert-success'>You have no assigned tasks! Why don't you assign yourself one?</div><?
      }?>
    </div>
    <div class="span4">
        <h3>Most recent projects</h3>
        <div class='row'>
          <?
             $options = array(
                'left' => array(
                    array(
                        array(
                            'text' => $this->DT->t('projects.viewall.text'),
                            'url' => array(
                                'action' => '.',
                                'controller' => 'projects',
                            ),
                        ),
                    ),
                ),
                'right' => array(
                    array(
                        array(
                            'text' => $this->DT->t('topbar.create.text', array('action' => 'topbar', 'controller' => 'projects')),
                            'url' => array(
                                'action' => 'add',
                                'controller' => 'projects',
                            ),
                            'props' => array('class' => 'btn-primary'),
                        ),
                    ),
                ),
            );
            echo $this->element('Topbar/button', array('options' => $options, 'span' => 4));

          ?>
        </div>

        <? if (!empty($projects)){
          echo $this->Element("Project/list", array('projects' => $projects, 'nospan' => true));
        } else {
          echo $this->element('Project/noprojectsalert');
        }?>
    </div>
</div>
