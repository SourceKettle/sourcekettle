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

    foreach ($options['left'] as $block) {
        echo '<div class="btn-group">';
        if (isset($pl)) {
            echo $this->TwitterBootstrap->button($this->TwitterBootstrap->icon($prefs[$c1]['icon'], 'white') .' '. $pl, array('class' => 'disabled btn-inverse'));
            unset($pl);
        }
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
