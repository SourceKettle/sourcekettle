<?php
/**
 *
 * Generic TopBar Element for the DevTrack system
 * Provides a navigation bar for the top of pages
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Topbar
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="span10">
    <ul class="nav nav-pills">
<?php
        $c1 = $this->request['controller'];
        $a1 = $this->request['action'];

        foreach ($options as $a => $option) {

            // If a complex URL is set, lets deal with it
            if (is_array($option['url'])) {

                // Logic to figure out if we are in the right place
                $c2 = $option['url']['controller'];
                $a2 = $option['url']['action'];
                $isFeat = false;

                // If the option is for more then one action
                if (is_array($a2)) {
                    foreach ($a2 as $a2_i) {
                        $isFeat = ($c1==$c2 && ($a1==$a2_i || ($a1=='index' && $a2_i=='.') || $a2_i=='*'));
                        if ($isFeat) break;
                    }

                    // The first in the array is the default action
                    $option['url']['action'] = $a2[0];
                } else {
                    $isFeat = ($c1==$c2 && ($a1==$a2 || ($a1=='index' && $a2=='.') || $a2=='*'));
                }
            } else {
                $isFeat = true;
            }

            echo "<li class=\"";
            if ($isFeat) echo ' active ';
            if (isset($option['align']) && $option['align'] == 'right') echo ' pull-right ';
            if (isset($option['dropdown'])) echo ' dropdown ';
            echo "\">";

            if (is_array($option['url']) && $option['url']['action'] == '*') {
                $option['url']['action'] = '.';
            }

            if (!isset($option['dropdown'])) {

                // Create correct URL
                if (is_array($option['url'])) {
                    $url = array(
                        'action' => $option['url']['action'],
                        'controller' => $option['url']['controller'],
                        'project' => $this->params['project']
                    );
                } else {
                    $url = $option['url'];
                }

                $prop = array();
                // Create correct properties
                if (isset($option['data-toggle'])) {
                    $prop['data-toggle'] = $option['data-toggle'];
                }
                if (isset($option['class'])) {
                    $prop['class'] = $option['class'];
                }

                // Print the link
                echo $this->Html->link($a, $url, $prop);
            } else {
                echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$a.'<b class="caret"></b></a>';
                echo '<ul class="dropdown-menu">';
                foreach ($option['dropdown'] as $b => $down) {
                    echo '<li>';
                    echo $this->Html->link(
                        $b,
                        array(
                            'project' => $this->params['project'],
                            'action' => $down['action'],
                            'controller' => $down['controller'],
                            $down['value']
                        )
                    );
                    echo '</li>';
                }
                echo '</ul>';
            }
            echo '</li>';
        }
?>
    </ul>
</div>
