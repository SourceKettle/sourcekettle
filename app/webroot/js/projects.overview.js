/**
*
* JS for the projects overview page.
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/SourceKettle/devtrack
* @package       DevTrack.webroot.js
* @since         DevTrack v 0.1
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

$(document).ready(function(){
  $('#view_more_button a').click(function(){
    $('#project_description').text($('#full_description').text());
  });
});