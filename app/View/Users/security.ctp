<?php
/**
 *
 * View class for APP/users/security for the SourceKettle system
 * Displays a form to let the user update their password.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  https://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.Users
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 ?>

<div class="span8">
	<?php
	echo $this->Form->create('User', array('class' => 'well form-horizontal', 'type' => 'post'));
	echo '<h3>'.__("Change your password").'</h3>';

	echo $this->Bootstrap->input(__("Current password"), array(
		"input" => $this->Form->password("password_current"),
	));

	echo $this->Bootstrap->input(__("new password"), array(
		"input" => $this->Form->password("password"),
	));

	echo $this->Bootstrap->input(__("Confirm password"), array(
		"input" => $this->Form->password("password_confirm"),
	));
	echo $this->Bootstrap->button(__("Save"), array("style" => "primary", "size" => "large", 'class' => 'controls'));

	echo $this->Form->end();
	?>
</div>
<div class="span4">
	<h3><?=__("What makes a super s3cr3t_pa55W0rd?")?></h3>
	<p>
		<?=__("Here at %s, we aren't your parents.", $sourcekettle_config['UserInterface']['alias']['value'])?><br>
		<?=__("However we do know a thing or two (maybe more) about what makes a secure password.")?>
	</p>
	<p>
		<?=__("We also love the sound of our own voices, so we've put together some handy hints:")?>
		<dl>
			<dt><?=__("Avoid:")?></dt>
			<dd>
			<ul>
				<li><?=__("Dictionary words in any language.")?></li>
				<li><?=__("Words spelled backwards, common misspellings, and abbreviations.")?></li>
				<li><?=__("Sequences or repeated characters or adjacent letters on your keyboard.")?></li>
				<li><?=__("Personal information. Your name, birthday, driver's license, passport number, or similar information.")?></li>
			</ul>
			</dd>
			<dt><?=__("Most importantly")?></dt>
			<dd><?=__("The longer a password is the harder it is to crack.")?></dd>
		</dl>
	</p>
	<p>
		<?=__("In summary, 'password' is not a good password.")?>
	</p>
</div>
