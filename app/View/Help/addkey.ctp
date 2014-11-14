<?php
/**
 *
 * View class for APP/help/addkey for the SourceKettle system
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

echo $this->Bootstrap->page_header('Help! <small>How do I manage my SSH keys?</small>'); ?>

<div class="row">
	<div class="span2">
		<?= $this->element('Sidebar/help') ?>
	</div>
	<div class="span10">
      <div class="well">
      <h3>Adding SSH keys</h3>
      <p>
        At some point, if your project has a source code repository (most projects do), you'll probably want to start getting some code in and out of it.  SourceKettle does this using SSH (Secure Shell), a remote access protocol that encrypts both the authentication and data transfer from your machine to the server.  We're using SSH public/private key-based authentication to give you access to your data.
      </p>

      <h4>So, what's an SSH key?</h4>
      <p>
        To access your repository, you will need to create a <strong>public/private key pair</strong> and let us know the <strong>public</strong> part.  The private part should never be revealed to anyone - even if they ask really nicely!
      </p>

      <h4>Your ideas intrigue me and I would like to know more.  How do I get an SSH key?</h4>
      <p>
        To find out how to generate a keypair on your operating system of choice, head over to your favourite search engine and search for something like "How to setup SSH Keys on [operating system here]".  Most likely you'll want puttygen on Windows and ssh-keygen on unix-y things.
      </p>

      <h4>Hmm, it's asking me for an optional passphrase.  Do I need one? What is it?</h4>
      <p>
        With SSH key authentication, you can connect to an SSH server using your private key rather than giving a username and password.  This is nice and convenient, but it's a bit of a security risk - if somebody else can get at your private key, that means they can access your repository and pretend to be you! It's probably easier than you might think, too...
      </p>

      <p>
        If you protect your private key file with a passphrase, it will be encrypted on disk and can only be decrypted by typing in the correct passphrase.  You will be prompted for the passphrase whenever a program wants to use it.  If somebody gets hold of the private key file, they can't do any damage with it unless they also know the passphrase.  (Obvious note: much like your password, don't write your passphrase down anywhere, and make sure it's not easy to guess!)
      </p>

      <p>
        You may also want to look up <strong>SSH agents</strong> for your operating system.  An SSH agent is a program that can provide your SSH key to programs automatically, and only ask you for the passphrase the first time it loads.  Mac OSX and various Linux desktop environments provide some nice SSH agent and login keychain integration, so you can log in and without typing any more passwords your SSH key will be available to let you log into SourceKettle (and other SSH servers).
      </p>

      <h4>What do I need to upload?</h4>
      <p>
        The <strong>public part only</strong>.  This should end up in a file called something like <strong>id_rsa.pub</strong> or <strong>id_dsa.pub</strong>.  If you're having trouble finding it, or your key generator tool has produced something in a weird format, search around for how to get they key into a format usable in an <strong>authorized_keys</strong> file.
      </p>

      <p>
        The public key is one long line of text, and should look something like this:
        <pre>
          ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDI4eJO0CDBX9Vdl/6y8wJMjPnwYNHP...(some more random characters)...BN2eoQT99erxYAD myusername@myhostname
        </pre>

        The private key is in a very different format - if you find something that looks like the following, do NOT upload it, or give it to anyone!
        <pre>
          -----BEGIN RSA PRIVATE KEY-----
          MIIEowIBAAKCAQEAyOHiTtAgwV/VXZf+svMCTIz58GDRz/ypUmzB9lzsMeIOOu8O
          ...and many more lines of random characters, forming a square...
          -----END RSA PRIVATE KEY-----
        </pre>  
      </p>

      <h4>I still can't get at my code!</h4>
      <p>
        Whoa there, hold your horses! Due to the way SSH keys work, they have to be automatically synced every few minutes before you can actually access your SourceKettle project repositories.  By default this happens every 2 minutes, although the system administrator may have changed this.
      </p>

      <h4>No really, it's been over a day now and I still can't get in - what gives?</h4>
      <p>
        OK, something's probably broken - contact the <a href='mailto:<?=$sourcekettle_config['Users']['sysadmin_email']['value']?>'>system administrator</a> for help.
      </p>
      </div>

	</div>
</div>
