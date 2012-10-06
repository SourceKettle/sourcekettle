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

  echo $this->Bootstrap->page_header($config['pages']['dashboard']['index']['en']['header.text'] . " <small>" . $config['pages']['dashboard']['index']['en']['header.small'] . " " . strtolower($user_name) . "</small>");

  echo $this->element('beta_warning');
?>


<div class="row">
    <div class="span8">
      Stuff
    </div>

    <div class="span4">
        <h3>Your recent projects</h3>
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
                                'action' => 'new',
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
        <?= $this->Element("Project/list", array('projects' => $projects, 'nospan' => true)) ?>  
    </div>
</div>