<?php
/**
 *
 * View class for APP/help/collaborators for the SourceKettle system
 * Display the help page for logging time
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));

echo $this->Bootstrap->page_header('Help! <small>How do I manage collaborators?</small>'); ?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
		<div class="well">
          <h3>Project collaborators</h3>
          <p>
            Collaborators are your project team, the people who are working hard to get everything finished on time.  To add collaborators, click the <a href="#"><i class="icon-user"></i> Collaborators</a> link in the project sidebar.
          </p>

          <p>
            To add a collaborator to the project, enter their email address in the "Add a user" box (it will autocomplete to help you out).
          </p>

          <div class="alert alert-error">
            Note: the user must have an account on your SourceKettle system before you can add them as a collaborator.  Unfortunately, you can't just add any old email address - if your team member isn't showing up, tell them to get their skates on and head over to the registration page!
          </div>

          <h4>Managing collaborators and permissions</h4>
          <p>
            Once you've added a collaborator - or several - you will see them listed in the Collaborators page.  They will each have an associated role - newly-added collaborators will have User status by default.
          </p>

          <h4>Available project roles</h4>
          <ul>
            <li><strong>Guest:</strong> Read-only access.  Guests may browse the issues, source tree etc. but not create anything.</li>
            <li><strong>User:</strong> A normal project team member, with access to create milestones and tasks, log time, write to the source repository, etc.</li>
            <li><strong>Admin:</strong> A project administrator, a User with the ability to edit project settings and administer the collaborators and their roles.</li>
          </ul>

          <p>
            Note that user accounts may also be set as system administrators - this is a global setting, and allows total access to the whole system.  Generally this will be the person or people who set up and maintain the SourceKettle system.
          </p>

          <h4>Changing roles and removing collaborators</h4>
          <p>
            3 buttons are provided to change collaborator status:
            <ul>
              <li><strong><a href="#" class="btn btn-mini"><i class="icon-arrow-up"></i></a> Promote User:</strong> Increases a user's access level - Guest-&gt;User or User-&gt;Admin</li>
              <li><strong><a href="#" class="btn btn-mini"><i class="icon-arrow-down"></i></a> Demote User:</strong> Drops a user's access level - Admin-&gt;User or User-&gt;Guest</li>
              <li><strong><a href="#" class="btn btn-danger btn-mini"><i class="icon-eject"></i></a> Remove User:</strong> Removes a collaborator from the project (note that this will NOT delete their user account)</li>
            </ul>
          </p>
		</div>
	</div>
</div>
