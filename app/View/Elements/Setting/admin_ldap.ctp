<?php
/**
 *
 * Settings element for APP/settings/admin_index for the SourceKettle system
 * View will render global configuration settings
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Setting
 * @since         SourceKettle v 1.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="well">
    <h3><?= __('LDAP authentication settings') ?></h3>
	<p><?=__('If you wish to link SourceKettle to your LDAP-based authentication system (e.g. Active Directory), set it up here!')?></p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="60%"><?= __('Description') ?></th>
                <th><?= __('Options') ?></th>
            </tr>
			<tr>
				<td>
					<h4><?= __('Enable LDAP authentication') ?></h4>
				</td>
				<td>
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'ldap-enabled', 'name' => 'Ldap,enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true')), 'sectionHide' => 'ldapSection', 'value' => $sourcekettle_config['Ldap']['enabled']['value'])) ?>
				</td>
			</tr>
        </thead>
        <tbody id="ldapSection">

			<tr>
				<td>
					<h5><?= __('LDAP server URL') ?> <small>- <?= __('...something like ldaps://ldap.example.com') ?></small></h5>
				</td>
				<td>
					<?= $this->element('Setting/text_fields', array('action'=>'setLdapUrl', 'items' => array(
						'ldap_url' => $sourcekettle_config['Ldap']['url']['value'],
					))) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h5><?= __('Base DN for looking up user accounts') ?> <small>- <?= __('...soemthing like ou=Users,dc=example,dc=com') ?></small></h5>
				</td>
				<td>
					<?= $this->element('Setting/text_fields', array('action'=>'setLdapUrl', 'items' => array(
						'ldap_base_dn' => $sourcekettle_config['Ldap']['base_dn']['value'],
					))) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h5><?= __('LDAP filter') ?> <small>- <?= __('...something like mail=%USERNAME%') ?></small></h5>
				</td>
				<td>
					<?= $this->element('Setting/text_fields', array('action'=>'setLdapUrl', 'items' => array(
						'ldap_filter' => $sourcekettle_config['Ldap']['filter']['value'],
					))) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h5><?= __('Bind DN') ?> <small>- <?= __('...something like cn=lookup,ou=Users,dc=example,dc=com') ?></small></h5>
				</td>
				<td>
					<?= $this->element('Setting/text_fields', array('action'=>'setLdapUrl', 'items' => array(
						'ldap_bind_dn' => $sourcekettle_config['Ldap']['bind_dn']['value'],
					))) ?>
				</td>
			</tr>
			<tr>
				<td>
					<h5><?= __('Bind password') ?> <small>- <?= __('...the password for connecting to LDAP') ?></small></h5>
				</td>
				<td>
					<?= $this->element('Setting/text_fields', array('action'=>'setLdapUrl', 'items' => array(
						'ldap_bind_pw' => $sourcekettle_config['Ldap']['bind_pw']['value'],
					))) ?>
				</td>
			</tr>
			</div>
        </tbody>
    </table>
</div>
