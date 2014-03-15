<?php
/**
 *
 * Settings element for APP/settings/admin_index for the DevTrack system
 * View will render global configuration settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Setting
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="well">
    <h3><?= $this->DT->t('global.header.text') ?></h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="60%"><?= $this->DT->t('table.col1') ?></th>
                <th><?= $this->DT->t('table.col2') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <h4><?= $this->DT->t('global.register.text') ?> <small>- <?= $this->DT->t('global.register.description') ?></small></h4>
                </td>
                <td>
                    <?= $this->element('Setting/on_off_buttons', array('action'=>'setRegistration', 'value' => $register)) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?= $this->DT->t('global.email.text') ?> <small>- <?= $this->DT->t('global.email.description') ?></small></h4>
                </td>
                <td>
					<?= $this->element('Setting/text_fields', array('action'=>'setEmail', 'name' => 'sysadmin_email', 'value' => $sysadmin_email)) ?>
                </td>
            </tr>

			<tr>
				<td>
					<h4><?= $this->DT->t('global.ldap.text') ?> <small>- <?= $this->DT->t('global.ldap.description') ?></small></h4>
				</td>
				<td>
					<?= $this->element('Setting/on_off_buttons', array('action'=>'setLDAPEnabled', 'value' => $ldap['ldap_enabled'])) ?>
				</td>
			</tr>

			<tr>
				<td>
					<h4><?= $this->DT->t('global.ldap_url.text') ?> <small>- <?= $this->DT->t('global.ldap_url.description') ?></small></h4>
				</td>
				<td>
					<?= $this->element('Setting/text_fields', array('action'=>'setLdapUrl', 'name' => 'ldap_url', 'value' => $ldap['ldap_url'])) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h4><?= $this->DT->t('global.ldap_base_dn.text') ?> <small>- <?= $this->DT->t('global.ldap_base_dn.description') ?></small></h4>
				</td>
				<td>
				<?= $this->element('Setting/text_fields', array('action'=>'setLdapBaseDN', 'name' => 'ldap_base_dn', 'value' => $ldap['ldap_base_dn'])) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h4><?= $this->DT->t('global.ldap_filter.text') ?> <small>- <?= $this->DT->t('global.ldap_filter.description') ?></small></h4>
				</td>
				<td>
				<?= $this->element('Setting/text_fields', array('action'=>'setLdapFilter', 'name' => 'ldap_filter', 'value' => $ldap['ldap_filter'])) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h4><?= $this->DT->t('global.ldap_bind_dn.text') ?> <small>- <?= $this->DT->t('global.ldap_bind_dn.description') ?></small></h4>
				</td>
				<td>
				<?= $this->element('Setting/text_fields', array('action'=>'setLdapBindDN', 'name' => 'ldap_bind_dn', 'value' => $ldap['ldap_bind_dn'])) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h4><?= $this->DT->t('global.ldap_bind_pw.text') ?> <small>- <?= $this->DT->t('global.ldap_bind_pw.description') ?></small></h4>
				</td>
				<td>
				<?= $this->element('Setting/text_fields', array('action'=>'setLdapBindPW', 'name' => 'ldap_bind_pw', 'value' => $ldap['ldap_bind_pw'])) ?>
				</td>
			</tr>

        </tbody>
    </table>
</div>
