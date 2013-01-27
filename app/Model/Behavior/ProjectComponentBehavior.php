<?php
/**
 *
 * Behaviour for omponents of a project in the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
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
     * @param mixed $ownerRequired (default: null)
     * @return void
     */
    public function open(Model $Model, $id = null, $ownerRequired = false) {
        if ($id == null) {
            if ($Model->id == null) {
                throw new NotFoundException(__('Invalid '.$Model->name));
            }
        }

        // Enable when public_id's are used
        // if ($Model->hasField('public_id', true) && $_virtual = $Model->findByPublicId($id)) {
        //     $id = $_virtual[$Model->name]['id'];
        // }

        $Model->id = $id;

        if (!$Model->exists()) {
            throw new NotFoundException(__('Invalid '.$Model->name));
        }

        $object = $Model->findById($Model->id);

        if ($Model->Project->id && ($object[$Model->name]['project_id'] != $Model->Project->id)) {
            throw new NotFoundException(__('Invalid '.$Model->name));
        }

        if ($ownerRequired) {
            $_is_not_owner = ($object[$Model->name]['user_id'] != $Model->_auth_user_id);
            $_is_not_admin = !$Model->Project->isAdmin();

            if ($_is_not_owner && $_is_not_admin) {
                throw new ForbiddenException(__('Ownership required'));
            }
        }

        return $object;
    }

    /**
     * afterSave function.
     *
     * @access public
     * @param bool $created (default: false)
     * @return void
     */
    public function afterSave(Model $Model, $created = false) {
        $Model->Project->set('modified', date('Y-m-d H:i:s'));
        $Model->Project->save();
        return true;
    }

    /**
     * afterDelete function.
     *
     * @access public
     * @return void
     */
    public function afterDelete(Model $Model) {
        $Model->Project->set('modified', date('Y-m-d H:i:s'));
        $Model->Project->save();
        return true;
    }

}
