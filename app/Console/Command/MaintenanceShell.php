<?php


/**
 * Shell containing various maintenance commands, designed to be called from a cron job
 *
 * @author Andy
 */
class MaintenanceShell extends AppShell {

	public  $uses = array('LostPasswordKey', 'EmailConfirmationKey');

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addSubCommand('cleanupKeys', array(
			'help' => __('Clean up expired keys'),
			'parser' => array(
				'description' => array(
					__('Removes any lost password or email confirmation keys that have not been used before their expiry dates'),
				),
			),
		));

		return $parser;
	}

	public function main() {
		$this->out("You need to specify a command. Try 'cleanupKeys'.");
	}

	// Clean up expired lost password/email confirmation keys
	public function cleanupKeys() {

		$sourcekettleConfig = $this->getSourceKettleConfig();

		$maxAge = $sourcekettleConfig['Users']['max_activation_key_age']['value'];
		$expireBefore = new DateTime('now', new DateTimeZone('UTC'));
		$expireBefore->sub(new DateInterval("PT{$maxAge}S"));

		$expireBefore = $expireBefore->format('Y-m-d H:i:s');

		$this->LostPasswordKey->deleteAll(array('LostPasswordKey.created <=' => $expireBefore));
		$this->EmailConfirmationKey->deleteAll(array('EmailConfirmationKey.created <=' => $expireBefore));
	}

}

?>
