<?php

/**
 *
 * Element for displaying a generic sidebar for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<ul class="well nav nav-list" style="padding: 8px 14px;">
<?php
    $c1 = $this->request['controller'];
    $a1 = $this->request['action'];

    if (isset($options['help'])){
        $help = $options['help'];
        unset($options['help']);
    }

    foreach ($options as $title => $section) {

        echo '<li class="nav-header">'.h($title).'</li>';

        // Iterate over the sidebar options in $sshkey
        foreach ( $section as $feature => $options ){

            // Logic to figure out if we are in the right place
            $c2 = $options['url']['controller'];
            $a2 = $options['url']['action'];
            $isFeat = false;

            // If the option is for more then one action
            if (is_array($a2)) {
                foreach ($a2 as $a2_i) {
                    $isFeat = ($c1==$c2 && ($a1==$a2_i || ($a1=='index' && $a2_i=='.') || $a2_i=='*'));
                    if ($isFeat) break;
                }

                // The first in the array is the default action
                $options['url']['action'] = $a2[0];
            } else {
                $isFeat = ($c1==$c2 && ($a1==$a2 || ($a1=='index' && $a2=='.') || $a2=='*'));
            }

            echo "<li ";
            if ($isFeat) echo 'class="active"';
            echo ">";

            if ($options['url']['action'] == '*') {
                $options['url']['action'] = '.';
            }

            echo $this->Html->link(
                $this->Bootstrap->icon($options['icon'], ($isFeat) ? 'white' : 'black').' '.h(ucwords($feature)),
                $options['url'],
                array('escape' => false)
            );

            echo "</li>";
        }
    }

    if (isset($help)){
        echo '<li class="divider"></li>';
        echo '<li>';
        echo $this->Html->link(
            $this->Bootstrap->icon('flag').' Help',
            array('controller' => 'help', 'action' => $help['action']),
            array('escape' => false)
        );

        echo '</li>';
    }
?>
</ul>
