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
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Topbar
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div class="span10">
    <div class="btn-toolbar">
<?php
    $prefs = array(
        'collaborators' => array('icon' => 'user'),
        'times'         => array('icon' => 'time'),
        'source'       => array('icon' => 'pencil'),
        'tasks'         => array('icon' => 'file'),
        'milestones'   => array('icon' => 'road'),
    );

    $c1 = $this->request['controller'];
    $a1 = $this->request['action'];

    if (isset($options['back'])) {
        echo '<div class="btn-group">';
            echo $this->Bootstrap->button_link($this->Bootstrap->icon('backward'), $options['back'], array('escape'=>false));
        echo '</div>';
    }

    foreach ($options['left'] as $block) {
        echo '<div class="btn-group">';
        if (isset($pl)) {
            echo $this->Bootstrap->button($this->Bootstrap->icon($prefs[$c1]['icon'], 'white') .' '. $pl, array('class' => 'disabled btn-inverse'));
            unset($pl);
        }
        foreach ($block as $option) {
            if ($option == null) continue;
            // Create correct URL
            if (is_array($option['url'])) {
                $option['url']['project'] = $this->params['project'];
            }

            $properties = (isset($option['props'])) ? $option['props'] : array();
            $properties['escape'] = false;

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
            }

            if ($isFeat) {
                $option['text'] = '<strong>'.$option['text'].'</strong>';
            }

            if (isset($option['type'])) {
                echo $this->Bootstrap->$option['type']($option['text'], $option['url'], $properties);
            } else {
                echo $this->Bootstrap->button_link($option['text'], $option['url'], $properties);
            }
        }
        echo '</div>';
    }
    foreach (array_reverse($options['right']) as $block) {
        echo '<div class="btn-group pull-right">';
        foreach ($block as $option) {
            // Create correct URL
            if (is_array($option['url'])) {
                $option['url']['project'] = $this->params['project'];
            }

            if (isset($option['type'])) {
                echo $this->Bootstrap->$option['type']($option['text'], $option['url'], (isset($option['props'])) ? $option['props'] : null);
            } else {
                echo $this->Bootstrap->button_link($option['text'], $option['url'], (isset($option['props'])) ? $option['props'] : null);
            }
        }
        echo '</div>';
    }
?>

    </div>
</div>
