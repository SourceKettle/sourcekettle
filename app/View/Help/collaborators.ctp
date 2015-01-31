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

<div class="row-fluid">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">

		<div class="row-fluid">
		<div class="well">
          <h3>Project collaborators</h3>
          <p>
            Collaborators are your developers, the people who are working hard to get everything finished on time.  To add collaborators, click the <a href="#"><i class="icon-user"></i> Collaborators</a> link in the project sidebar.
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

	<div class="row-fluid">
		<div class="well">
          <h3>Collaborating teams</h3>
		  <p>
		    If you have a larger number of users, they may be divided up into teams to make administration easier. System administrators have control over which users are in which teams.
		  </p>
		  <p>
		    Teams can be given the same access levels (guest/user/admin) as ordinary users, except that the access level will be granted to <strong>all the members of the team</strong>.
		  </p>
		  <p>
		    When a user attempts to do something with the project (such as adding a task), <strong>all the user's roles</strong> are taken into account, and the highest access level is granted - so for example, if Bob is given "user" access, Jane is given "guest" access, and the "developers" team is given "admin" access, Bob will have "user" access and Jane will have "guest" access. However, if Jane is put into the "developers" team, Jane will now have "admin" access.
		  </p>
		</div>

	</div>
	<div class="row-fluid">
		<div class="well">
		<p>These tables show the effective permissions of some users on a project based on their roles/teams.</p>

		<table id="team-roles" class="table table-striped">
		<thead><tr><th>Team</th><th>Role on project</th><th>Role on project group</th></tr></thead>
		<tbody>
			<tr><td>Developers</td><td>Admin</td><td>(None)</td></tr>
			<tr><td>ProjectManagers</td><td>(None)</td><td>Admin</td></tr>
			<tr><td>Testers</td><td>User</td><td>(None)</td></tr>
			<tr><td>Observers</td><td>(None)</td><td>Guest</td></tr>
		</tbody>
		</table>

		<table id="users" class="table table-striped">
		<thead><tr><th>User</th><th>Role on project</th><th>Teams</th><th>Effective role</th><th>Why?</th></tr></thead>
		<tbody>
			<tr><td>Alice</td><td>Guest</td><td>(None)</td><td>Guest</td><td>Direct guest access to project</td></tr>
			<tr><td>Bob</td><td>User</td><td>Observers</td><td>User</td><td>Guest access from 'Observers' group overridden by direct User access</td></tr>
			<tr><td>Carol</td><td>(None)</td><td>Developers</td><td>Admin</td><td>Admin access granted on the project to Developers team</td></tr>
			<tr><td>Dave</td><td>(None)</td><td>Observers</td><td>Guest</td><td>Guest access granted on the project group to Observers team</td></tr>
			<tr><td>Eve</td><td>Guest</td><td>ProjectManagers</td><td>Admin</td><td>Guest access overridden by Admin access granted on the project group to ProjectManagers team</td></tr>
			<tr><td>Frank</td><td>(None)</td><td>Observers,ProjectManagers</td><td>Admin</td><td>Guest access to Observers team overridden by Admin access granted on the project group to ProjectManagers team</td></tr>
		</tbody>
		</table>

		</div>

	</div>
	<div class="row-fluid">
		<div class="well">
          <h3>Project groups and team access</h3>
		  <p>
		    If you have a large number of projects, they may be divided up into project groups to make administration easier. System administrators have control over which projects are in which groups.
		  </p>

		  <p>
		    Teams (but NOT individual users) may be granted access to entire project groups by a system administrator. From the project's point of view, this works as if the team is now collaborating on the project.
		  </p>

		  <p>
		  
		  </p>
		</div>

	</div>
	<div class="row-fluid">
		<div class="well">
          <h3>Good practice for assigning permissions</h3>
		  <p>
		    <strong>CAUTION:</strong> It is possible to grant permissions in 3 different ways here - individual collaborator, team collaborating on project, or team collaborating on project group. If you are not careful you may end up in a mess.
		  </p>
		  <p>We recommend:</p>
		  <ul>
		    <li><strong>If your organisation is very small (~5 people or less):</strong> Select individual collaborators only</li>
		    <li><strong>If your organisation is small to medium (~20-50 people):</strong> Create teams; grant teams access to projects, and add individual collaborators where needed</li>
		    <li><strong>If your organisation is larger:</strong> Create teams, and make sure every user is in at least one team; Create project groups, and make sure every project is in at least one group; grant teams access to project groups; avoid adding teams or individuals to projects directly</li>
		  </ul>

		  <p>
		  The sizes here are approximate; you should use your judgment. If in doubt, create teams, it may save you some effort later!
		  </p>

		</div>
	</div>
	</div>
</div>
