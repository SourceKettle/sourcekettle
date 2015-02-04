<?php
/**
 *
 * View class for APP/ssh_keys/add for the SourceKettle system
 * Displays a form to let the user add a SSH Key.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link		  https://github.com/SourceKettle/sourcekettle
 * @package	   SourceKettle.View.SSH_Keys
 * @since		 SourceKettle v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div class="row-fluid">
	<div class="span7">
		<?php
		echo $this->Form->create('SshKey', array('class' => 'well form-horizontal', 'url' => array('controller' => 'sshKeys', 'action' => 'add')));
		echo '<h3>Add a new SSH key</h3>';
		echo $this->Bootstrap->input("key", array(
			"input" => $this->Form->textarea("key", array("class" => "span12 input-xlarge")),
			"label" => "Public SSH Key",
		));
	
		echo $this->Bootstrap->input("comment", array(
			"input" => $this->Form->text("comment", array("class" => "span12 input-xlarge")),
		));
	
		echo $this->Bootstrap->button("Add", array("style" => "primary", "size" => "large", 'class' => 'controls'));
	
		echo $this->Form->end();
		?>
	</div>
	<div class="span3">
		<h3>What be all the hype about SSH keys?</h3>
		<p>
			Here at SourceKettle, we think the world of computers moves pretty fast! It's hard for us to keep up with all the lastest and greatest tech, and as such,
			if you would like to know about SSH keys and how they work on your device, please head over to Google*.
		</p>
		<p>
			You'll get far with a search such as:<br>'How to setup SSH Keys on [operating system here]'<br>
		</p>
		<p>
			We can however tell you the following:
			<dl>
				<dt>Public keys only</dt>
				<dd>Please don't give us your private keys! That's like giving out the PIN for your credit card!</dd>
				<dt>Public keys look somthing like this</dt>
				<dd>
				ssh-rsa A3AAB3j7nxirGz8Z2bddNdMm0UB/uEFZa
				tasKgDQrOEvJ9LQjMq2qolTBzROgdg6Mo9DsWZCq4
				Q48p06JyQLbMx7hKuZkBH0d5jxeTGEGW4utk3E/==
				<br>
				But longer...
			</dd>
			</dl>
		</p>
	<p><small>* Other search engines are available.</small></p>
	</div>
</div>
