<?php
/**
 *
 * Behaviour for omponents of a project in the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Model.Behavior
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ProjectComponentBehavior extends ModelBehavior {

    var $settings = array();

    var $model = null;

    public function setup(Model $model, $settings = array()) {
        $this->settings[$model->name] = $settings;
        $this->model = &$model;
    }

    /**
     * open function.
     *
     * @access public
     * @param Model $Model
     * @param mixed $id (default: null)
     * @return void
     */
    public function open(Model $Model, $id = null) {
        $Model->id = $id;

        if (!$Model->exists()) {
            throw new NotFoundException(__('Invalid '.$Model->name));
        }
        if ($Model->Project->id && ($Model->field('project_id') != $Model->Project->id)) {
            throw new NotFoundException(__('Invalid '.$Model->name));
        }
        return $Model->read();
    }

}
