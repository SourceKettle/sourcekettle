<?php

/**
 * Description of XMLShell
 *
 * @author pwhittlesea
 */

App::uses('AuthComponent', 'Controller/Component');
class XMLShell extends AppShell {

    public $uses = array('Project', 'User');

    // Cache
    var $_element = null;
    var $_content = null;
    var $_collaborators = array();

    var $domain;
    var $date;

    // Booleans
    var $_in_project = false;
    var $_in_user = false;

    // XML format
    var $_format = array(
        'devtrackHeader' => array(
            'created',
            'domain',
        ),
        'devtrackBody' => array(
            'project' => array(
                'name',
                'description',
                'isPublic',
                'created',
                'tasks',
                'source',
//                'collaborator',
            ),
            'user' => array(
                'email',
                'name',
                'password',
                'is_active',
                'is_admin',
                'created',
//                'sshKey',
            ),
        )
    );

    /**
     * main function.
     *
     * @access public
     * @return void
     */
    public function main() {
        $vars = array_merge($_SERVER, $_ENV);

        $file = $vars['argv'][sizeof($vars['argv']) - 1];

        if (is_null($file) || $file == '' || !file_exists($file)) {
            print "Please specify a valid XML file to parse\n";
            exit(1);
        }

        $this->date = date('Y-m-d H:i:s', time());
        /* create the parser */
        $parser = xml_parser_create();
        xml_set_element_handler($parser, array($this,'startElemHandler'), array($this,'endElemHandler'));
        xml_set_character_data_handler($parser, array($this,'character_data'));
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);

        $string = file_get_contents($file);
        $string = preg_replace('/[\x00-\x1F\x7F]/', '', $string);

        // output each link
        xml_parse($parser, $string);

        // clean up - we're done
        xml_parser_free($parser);
    }

    /**
     * startElemHandler function.
     *
     * @access private
     * @param mixed $parser
     * @param mixed $name
     * @param mixed $attribs
     * @return void
     */
    private function startElemHandler($parser, $name, $attribs) {
        if ( $name == 'project' ) {
            $this->_element = array(
                'Collaborators' => array()
            );
            $this->_in_project = true;
        } else if ( $name == 'user' ) {
            $this->_element = array(
                'SshKeys' => array()
            );
            $this->_in_user = true;
        }
    }

    /**
     * endElemHandler function.
     *
     * @access private
     * @param mixed $parser
     * @param mixed $name
     * @return void
     */
    private function endElemHandler($parser, $name) {
        if ( $this->_in_project ) {
            // Project handling code
            if ( in_array($name, $this->_format['devtrackBody']['project']) ) {
                $this->_element[$name] = $this->_content;
            } else if ( $name == 'collaborator' ) {
                $this->_element['Collaborators'][] = $this->_content;
            } else if ( $name == 'project' ) {

                // Handle the Project
                $this->_in_project = false;
                $this->handleProject();

            }
        } else if ( $this->_in_user ) {
            // User handling code
            if ( in_array($name, $this->_format['devtrackBody']['user']) ) {
                $this->_element[$name] = $this->_content;
            } else if ( $name == 'sshKey' ) {
                $this->_element['SshKeys'][] = $this->_content;
            } else if ( $name == 'user' ) {

                // Handle the User
                $this->_in_user = false;
                $this->handleUser();

            }
        } else {
            if ( $name == 'domain' ) {
                $this->domain = $this->_content;
            }
        }
    }

    /**
     * character_data function.
     *
     * @access private
     * @param mixed $parser
     * @param mixed $data
     * @return void
     */
    private function character_data($parser, $data) {
        $this->_content = $data;
    }

    /**
     * handleProject function.
     *
     * @access private
     * @return void
     */
    private function handleProject() {
        $errs = false;

        // Validate the Project
        foreach ($this->_format['devtrackBody']['project'] as $val) {

            if ( !isset($this->_element[$val]) ) {
                echo "[ERROR] Project ".$this->_element['name']." is missing element ${val}\n";
                $errs = true;
            } else if ( is_null($this->_element[$val]) ) {
                echo "[WARN] Project ".$this->_element['name']." has null ${val}\n";
            }

        }

        // If the project Validates continue
        if (!$errs) {
            $this->_element['repo_type'] = (int) 3;
            $this->_element['created'] = date('Y-m-d H:i:s', (int) $this->_element['created']);
            $this->_element['modified'] = $this->date;

            $this->_collaborators[$this->_element['name']] = $this->_element['Collaborators'];
            unset($this->_element['Collaborators']);

            // Store the Project in the DB
            $this->Project->create();
            if (!$this->Project->save(array('Project'=>$this->_element))) {
                echo "[ERROR] Project \"".$this->_element['name']."\" was not saved\n";
                echo "[ERROR] Validation failed:\n";
                foreach ($this->Project->invalidFields() as $x => $err) {
                    foreach (array_unique($err) as $e) {
                        echo "[ERROR] Field \"${x}\" -> ${e}\n";
                    }
                }
            } else {
                echo "[INFO] Project \"".$this->_element['name']."\" saved successfully\n";
            }
        }
    }

    /**
     * handleUser function.
     *
     * @access private
     * @return void
     */
    private function handleUser() {
        $errs = false;

        // Validate the User
        foreach ($this->_format['devtrackBody']['user'] as $val) {

            if ( !isset($this->_element[$val]) ) {
                echo "[ERROR] User ".$this->_element['email']." is missing element ${val}\n";
                $errs = true;
            } else if ( is_null($this->_element[$val]) ) {
                echo "[WARN] User ".$this->_element['email']." has null ${val}\n";
            }

        }

        // If the user Validates continue
        if (!$errs) {
            $this->_element['created'] = date('Y-m-d H:i:s', (int) $this->_element['created']);
            $this->_element['modified'] = $this->date;

            $this->User->create();
            if (!$this->User->save(array('User'=>$this->_element))) {
                echo "[ERROR] User ".$this->_element['email']." was not saved\n";
                echo "[ERROR] Validation failed:\n";
                foreach ($this->User->invalidFields() as $x => $err) {
                    foreach (array_unique($err) as $e) {
                        echo "[ERROR] Field \"${x}\" -> ${e}\n";
                    }
                }
                break;
            } else {
                echo "[INFO] User ".$this->_element['email']." saved successfully\n";
            }
        }
    }

}
