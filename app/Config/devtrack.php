<?php
/**
 *
 * Central configuration file for the DevTrack system
 * Provides the central config for the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Config
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

 // Tool name
 // value will be string that will be shown across the system in place of 'DevTrack'
 $config['devtrack']['global']['alias'] = 'DevTrack';

 // Username for SSH access to git repositories
 $config['devtrack']['repo']['user'] = 'git';

 // Repository folder
 // set to folder where you would like repositories placed
 $config['devtrack']['repo']['base'] = '/home/git/repositories';

 $config['devtrack']['authenticate'] = array(
                // Try soton auth first
                'LDAPAuthCake.LDAP' => array(
                    'ldap_url'      => 'ldaps://nlbldap.soton.ac.uk',
                    'ldap_bind_dn'  => '',
                    'ldap_bind_pw'  => '',
                    'ldap_base_dn'  => 'ou=User,dc=soton,dc=ac,dc=uk',
                    'ldap_filter'   => '(| (proxyAddresses=SMTP:%USERNAME%) (proxyAddresses=smtp:%USERNAME%) )',
                    'form_fields'   => array ('username' => 'email', 'password' => 'password'),
                    'ldap_to_user'  => array(
                      'displayName' => 'name',
                      'mail'        => 'email',
                    ),
                ),

                // Then try form/db auth
                'Form' => array(
                    'fields' => array ('username' => 'email', 'password' => 'password')
                ),
            );
