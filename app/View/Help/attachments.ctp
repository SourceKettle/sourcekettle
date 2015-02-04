<?php
/**
 *
 * View class for APP/help/attachments for the SourceKettle system
 * Display the help page for logging time
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Help
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$this->Html->css('time.tempo', null, array ('inline' => false));
$this->Html->css('pages/help', null, array ('inline' => false));
?>

<div class="well">
  <h3>Attachments</h3>
  <p>
    Sometimes it may be helpful to add a file to the project, such as a design document of help video.  If the system administrator has enabled this feature, you can click the '<a href="#"><i class="icon-file"></i>Attachments</a>' link in the project sidebar to get started.
  </p>

  <p>
    To upload a file, click the aptly-named <button class="btn btn-mini btn-primary">Upload file</button> button at the top right.  Simply select a file from your computer and click upload; however, it must be under the file size limit and must be of a known type.
  </p>

  <p>
    Once you have uploaded one or more files, they will appear in a list, easily filtered by file type.  You can click on the file to download it, or click on the delete button (<a href="#" class="btn btn-danger btn-mini"><i class="icon-eject icon-white"></i></a>) to remove it from the project.
  </p>
</div>
