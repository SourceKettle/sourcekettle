<?php
/**
 * Gravatar Helper
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE. It is also available through the world-wide-web
 * at this URL: http://www.opensource.org/licenses/bsd-license
 *
 * @category   Helpers
 * @package    CakePHP
 * @subpackage PHP
 * @copyright  Copyright (c) 2011 Signified (http://signified.com.au)
 * @license    http://www.opensource.org/licenses/bsd-license    New BSD License
 * @version    1.0
 */

/**
 * GravatarHelper class
 *
 * Gravatar Helper class for easy display of Gravatars
 *
 * @category   Helpers
 * @package    CakePHP
 * @subpackage PHP
 * @copyright  Copyright (c) 2011 Signified (http://signified.com.au)
 * @license    http://www.opensource.org/licenses/bsd-license    New BSD License
 */
class GravatarHelper extends AppHelper
{
    /**
     * Helpers used by GravatarHelper
     *
     * @var array
     * @access public
     */
    public $helpers = array('Html');

    /**
     * Create a Gravatar
     *
     * @param string $email Email address of the user.
     * @param array $options Array of Gravatar options.
     * @param array $attributes Array of HTML attributes.
     * @return string completed img tag
     * @access public
     * @link http://gravatar.com/site/implement/images/
     */
    public function image($email = null, $options = array(), $attributes = array())
    {
        $attributes['height'] = 80;
        $attributes['width'] = 80;
        $hash = md5(strtolower(trim($email)));
        $query = null;
        $url = 'http://www.gravatar.com/avatar/';
        if (isset($options['s'])) {
            $attributes['height'] = $options['s'];
            $attributes['width'] = $options['s'];
        } elseif (isset($options['size'])) {
            $attributes['height'] = $options['size'];
            $attributes['width'] = $options['size'];
        }
        if (!empty($options)) {
            $query = '?' . http_build_query($options);
        }
        if (env('HTTPS')) {
            $url = 'https://secure.gravatar.com/avatar/';
        }
        $path = $url . $hash . '.jpg' . $query;
        return $this->Html->image($path, $attributes);
    }
}
