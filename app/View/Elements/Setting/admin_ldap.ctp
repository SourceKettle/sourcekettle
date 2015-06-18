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
	<p><?=__('If you wish to link %s to your LDAP-based authentication system (e.g. Active Directory), set it up here!', $sourcekettle_config['UserInterface']['alias']['value'])?></p>
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
                    <?= $this->element('Setting/switch', array('lock' => false, 'id' => 'ldap-enabled', 'model' => 'Setting', 'name' => 'Ldap.enabled', 'url' => $this->Html->url(array('controller' => 'settings', 'action' => 'set', 'admin' => 'true')), 'sectionHide' => 'ldapSection', 'value' => $sourcekettle_config['Ldap']['enabled']['value'])) ?>
				</td>
			</tr>
        </thead>
        <tbody id="ldapSection">
			<?= $this->element('Setting/text_fields', array(
				'model' => 'Setting',
				'url' => array('controller' => 'settings', 'action' => 'set', 'admin' => true),
				'items' => array(
					array(
						'name' => 'Ldap.url',
						'label' => __('LDAP server URL'),
						'description' => __('e.g. ldaps://ldap.example.com'),
						'value' => $sourcekettle_config['Ldap']['url']['value'],
						'readOnly' => false,
					),
					array(
						'name' => 'Ldap.base_dn',
						'label' => __('Base DN for looking up user accounts'),
						'description' => __('e.g. ou=Users,dc=example,dc=com'),
						'value' => $sourcekettle_config['Ldap']['base_dn']['value'],
						'readOnly' => false,
					),
					array(
						'name' => 'Ldap.filter',
						'label' => __('LDAP filter'),
						'description' => __('e.g. mail=%USERNAME% (%USERNAME% will be replaced with the email address field)'),
						'value' => $sourcekettle_config['Ldap']['filter']['value'],
						'readOnly' => false,
					),
					array(
						'name' => 'Ldap.bind_dn',
						'label' => __('Bind DN'),
						'description' => __('e.g. cn=lookup,ou=Users,dc=example,dc=com'),
						'value' => $sourcekettle_config['Ldap']['bind_dn']['value'],
						'readOnly' => false,
					),
					array(
						'name' => 'Ldap.bind_pw',
						'label' => __('Bind password'),
						'description' => __('...the password for connecting to LDAP'),
						'value' => $sourcekettle_config['Ldap']['bind_pw']['value'],
						'readOnly' => false,
					),
				),
			)) ?>
        </tbody>
    </table>
</div>
