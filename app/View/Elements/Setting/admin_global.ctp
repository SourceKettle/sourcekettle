<?php
/**
 *
 * Settings element for APP/settings/admin_index for the SourceKettle system
 * View will render global configuration settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Setting
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="well">
    <h3><?= __('System-wide configuration options') ?></h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="60%"><?= __('Description') ?></th>
                <th><?= __('Options') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <h4><?= __('Allow Registration') ?> <small>- <?= __('allow new users to create accounts') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/on_off_buttons', array('action'=>'setRegistration', 'value' => $register)) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= __('Admin email address') ?> <small>- <?= __('emails sent by the system will come from this address; users will be told to email this address for tech support') ?></small></h4>
                </td>
                <td>
					<?= $this->element('Setting/text_fields', array('action'=>'setEmail', 'name' => 'sysadmin_email', 'value' => $sysadmin_email)) ?>
                </td>
            </tr>

			<tr>
				<td>
					<h4><?= __('Enable LDAP authentication') ?> <small>- <?= __('authenticate users against an LDAP system such as Active Directory') ?></small></h4>
				</td>
				<td>
					<?= $this->element('Setting/on_off_buttons', array('action'=>'setLDAPEnabled', 'value' => $ldap['ldap_enabled'])) ?>
				</td>
			</tr>

			<tr>
				<td>
					<h5><?= __('LDAP URL') ?> <small>- <?= __('the ldaps:// URL of the LDAP system') ?></small></h5>
				</td>
				<td>
					<?= $this->element('Setting/text_fields', array('action'=>'setLdapUrl', 'name' => 'ldap_url', 'value' => $ldap['ldap_url'])) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h5><?= __('LDAP base DN') ?> <small>- <?= __('the LDAP base DN containing the user accounts') ?></small></h5>
				</td>
				<td>
				<?= $this->element('Setting/text_fields', array('action'=>'setLdapBaseDN', 'name' => 'ldap_base_dn', 'value' => $ldap['ldap_base_dn'])) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h5><?= __('LDAP filter') ?> <small>- <?= __('the LDAP search filter to look up user accounts') ?></small></h5>
				</td>
				<td>
				<?= $this->element('Setting/text_fields', array('action'=>'setLdapFilter', 'name' => 'ldap_filter', 'value' => $ldap['ldap_filter'])) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h5><?= __('LDAP bind DN') ?> <small>- <?= __('the DN of a service account to bind with, if anonymous searching is not supported') ?></small></h5>
				</td>
				<td>
				<?= $this->element('Setting/text_fields', array('action'=>'setLdapBindDN', 'name' => 'ldap_bind_dn', 'value' => $ldap['ldap_bind_dn'])) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h5><?= __('LDAP bind password') ?> <small>- <?= __('the password for the LDAP service account') ?></small></h5>
				</td>
				<td>
				<?= $this->element('Setting/text_fields', array('action'=>'setLdapBindPW', 'name' => 'ldap_bind_pw', 'value' => $ldap['ldap_bind_pw'])) ?>
				</td>
			</tr>

        </tbody>
    </table>
</div>
