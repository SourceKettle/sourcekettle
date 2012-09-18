<?php
/**
 *
 * Attachment model for the DevTrack system
 * Stores attachments for a project in the system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Model
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @property Project $Project
 */
App::uses('AppModel', 'Model');
App::uses('File', 'Utility');

class Attachment extends AppModel {

    public $actsAs = array(
        'ProjectComponent',
        'ProjectHistory'
    );

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'id';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'project_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'size' => array(
            'range' => array(
                'rule' => array('range', 0, 2097152),
                'message' => 'Files must be less than 2Mb in size',
            ),
        ),
    );

    public $_mime_text = array(
        "text/plain",
        "text/richtext",
        "text/h323",
        "text/css",
        "text/x-setext",
        "text/x-component",
        "text/html",
        "text/webviewhtml",
        "text/scriptlet",
        "text/tab-separated-values",
        "text/iuls",
        "text/x-vcard",
    );

    public $_mime_image = array(
        "image/bmp",
        "image/x-cmx",
        "image/cis-cod",
        "image/gif",
        "image/x-icon",
        "image/ief",
        "image/pipeg",
        "image/jpeg",
        "image/png",
        "image/x-portable-bitmap",
        "image/x-portable-graymap",
        "image/x-portable-anymap",
        "image/x-cmu-raster",
        "image/x-rgb",
        "image/x-portable-pixmap",
        "image/svg+xml",
        "image/tiff",
        "image/x-xbitmap",
        "image/x-xpixmap",
        "image/x-xwindowdump",
    );

    public $_mime_audio = array(
        "audio/basic",
        "audio/x-aiff",
        "audio/mpeg",
        "audio/x-mpegurl",
        "audio/mid",
        "audio/x-pn-realaudio",
        "audio/x-wav",
    );

    public $_mime_video = array(
        "video/x-ms-asf",
        "video/x-msvideo",
        "video/x-la-asf",
        "video/quicktime",
        "video/x-sgi-movie",
        "video/mpeg",
    );

    public $_mime_others = array(
        "application/xml",
        "application/xhtml+xml",
        "application/internet-property-stream",
        "application/postscript",
        "application/olescript",
        "application/x-bcpio",
        "application/octet-stream",
        "application/vnd.ms-pkiseccat",
        "application/x-cdf",
        "application/x-x509-ca-cert",
        "application/x-msclip",
        "application/x-cpio",
        "application/x-mscardfile",
        "application/pkix-crl",
        "application/x-csh",
        "application/x-director",
        "application/x-msdownload",
        "application/msword",
        "application/x-dvi",
        "application/envoy",
        "application/fractals",
        "application/x-gtar",
        "application/x-gzip",
        "application/x-hdf",
        "application/winhlp",
        "application/mac-binhex40",
        "application/hta",
        "application/x-iphone",
        "application/x-internet-signup",
        "application/x-javascript",
        "application/x-latex",
        "application/x-msmediaview",
        "application/x-troff-man",
        "application/x-msaccess",
        "application/x-troff-me",
        "application/x-msmoney",
        "application/vnd.ms-project",
        "application/x-troff-ms",
        "application/oda",
        "application/pkcs10",
        "application/x-pkcs12",
        "application/x-pkcs7-certificates",
        "application/x-pkcs7-mime",
        "application/x-pkcs7-certreqresp",
        "application/x-pkcs7-signature",
        "application/pdf",
        "application/ynd.ms-pkipko",
        "application/x-perfmon",
        "application/vnd.ms-powerpoint",
        "application/pics-rules",
        "application/x-mspublisher",
        "application/x-troff",
        "application/rtf",
        "application/x-msschedule",
        "application/set-payment-initiation",
        "application/set-registration-initiation",
        "application/x-sh",
        "application/x-shar",
        "application/x-stuffit",
        "application/futuresplash",
        "application/x-wais-source",
        "application/vnd.ms-pkicertstore",
        "application/vnd.ms-pkistl",
        "application/x-sv4cpio",
        "application/x-sv4crc",
        "application/x-tar",
        "application/x-tcl",
        "application/x-tex",
        "application/x-texinfo",
        "application/x-compressed",
        "application/x-msterminal",
        "application/x-ustar",
        "application/vnd.ms-works",
        "application/x-msmetafile",
        "application/x-mswrite",
        "application/vnd.ms-excel",
        "application/x-compress",
        "application/zip"
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Project' => array(
            'className' => 'Project',
            'foreignKey' => 'project_id',
        )
    );

    public function renderable($mime = false) {
        if (!$mime) {
            if (!$this->id) {
                return null;
            }
            $mime = $this->field('mime');
        }
        if (in_array($mime, $this->_mime_image)) {
            return true;
        } else if (in_array($mime, $this->_mime_text)) {
            return true;
        } else if (in_array($mime, $this->_mime_video)) {
            return true;
        } else {
            return false;
        }
    }

    public function upload($data = null, $model = null, $model_id = null) {
        if (!$this->Project->id || !$data) {
            return null;
        }
        foreach ($data['Attachment'] as $name => $details) {
            $file = new File($details['tmp_name']);

            if ($file->exists()) {
                $row = array();

                $row['name']        = $details['name'];
                $row['mime']        = $file->mime();
                $row['content']     = $file->read();
                $row['size']        = $file->size();
                $row['md5']         = $file->md5();
                $row['project_id']  = $this->Project->id;
                $row['model']       = $model;
                $row['model_id']    = $model_id;

                if (!in_array($row['mime'], array_merge($this->_mime_audio, $this->_mime_others, $this->_mime_image, $this->_mime_text, $this->_mime_video))) {
                    return false;
                }

                $_data = array('Attachment' => $row);

                $this->create();
                $saved = $this->save($_data);

                // Clean up
                $file->delete();

                return $saved;
            }
        }
    }

    public function getTitleForHistory($id = null){
        $this->id = $id;
        if (!$this->exists()) {
            return null;
        } else {
            return $this->field('name');
        }
    }

}
