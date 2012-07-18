<?php

/**
 * Description of GitShell
 *
 * @author Chris
 */
class GitShell extends AppShell {

    public $uses = array('SshKey', 'Project', 'Collaborator', 'Setting');

    public function main() {
        $this->out("You need to specify a command. Try 'sync_keys' or 'serve'.");
    }

    /**
     * Syncs all of the SSH keys to the git user's authorized_keys file to allow for ssh access
     */
    public function sync_keys() {

        $sync_required = $this->Setting->find('first', array('conditions' => array('name' => 'sync_required')));

        //if ($sync_required['Setting']['value'] == 1) {
            $keys = $this->SshKey->find('all');
            $prepared_keys = array();

            $template = 'command="%s %s",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty %s';

            $app_path = App::path('Controller');
            $app_path = $app_path[0];
            $app_path = str_replace('/app/Controller', '', $app_path);

            $cmd = $app_path . 'scm-scripts/git-serve.py';

            $out = '';
            foreach ($keys as $key) {
                $sshkey = $key['SshKey']['key'];
                $userid = $key['User']['id'];

                if (strlen($sshkey) > 40) { //sanity check on key
                    $content = trim(str_replace(array("\n", "\r"), '', $sshkey));
                    $out .= sprintf($template, $cmd, $userid, $content) . "\n";
                }
            }
            file_put_contents('/home/git/.ssh/authorized_keys', $out, LOCK_EX);
            $sync_required['Setting']['value'] = 0;
            $this->Setting->save($sync_required);
        //}
    }

    public function serve() {
        //Firstly, get the SSH_ORIGINAL_COMMAND and other useful variables from environment
        $vars = array_merge($_SERVER, $_ENV);

        if (!isset($vars['SSH_ORIGINAL_COMMAND']) or !isset($vars['argv'])){
            $this->err("Error: Required environment variables are not defined");
            exit(1);
        }

        $ssh_original_command = $vars['SSH_ORIGINAL_COMMAND']; 
        $argv = $vars['argv'];
        $userid = $argv[sizeof($argv)-1];

        //Secondly, validate the arguments and get the command into a generic format

        //check if SSH_ORIGINAL_COMMAND contains new lines
        if (strpos($ssh_original_command, "\n") !== false){ //!=== as it may also return non-boolean values that evaluate to false
            $this->err("Error: SSH_ORIGINAL_COMMAND contains new lines");
            exit(1);
        }


        $command_parts = explode(" ", $ssh_original_command, 2);

        //Check if the command has 2 parts
        if (sizeof($command_parts) != 2){
            $this->err("Error: Command is not a valid git command");
            exit(1);
        }

        $command = array(); //initialise an empty array

        //Get the command into a generic format
        if ($command_parts[0] == 'git'){
            $command_args = explode(" ", $command_parts[1], 2); //split into the git command name and the arguments
            if (sizeof($command_args) != 2){
                $this->err("Error: Wrong number of arguments to a git command");
                exit(1);
            } else {
                $command['command'] = $command_parts[0] . " " . $command_args[0];
                $command['args'] = $command_args[1];
            }
        } else {
            $command['command'] = $command_parts[0];
            $command['args'] = $command_parts[1];
        }

        // Now check if the parts are a valid git command
        $read_commands = array('git-upload-pack', 'git upload-pack');
        $write_commands = array('git-receive-pack', 'git receive-pack');

        if (!in_array($command['command'], $read_commands) and !in_array($command['command'], $write_commands)){
            $this->err("Error: Unknown command");
            exit(1);
        } 

        //Get the project. Since the project name must be a valid unix name, we can just use the argument
        preg_match("#^\'(/?(?P<last>[a-zA-Z0-9][a-zA-Z0-9@._-]*))*\'$#", $command['args'], $matches);
        preg_match("#^(?P<repo>[a-zA-Z0-9][a-zA-Z0-9@._-]*).git$#", $matches['last'], $matches);
        $_proj_name = $matches['repo'];

        $project = $this->Project->getProject($_proj_name);
        if (empty ($project)){
            $this->err("Error: You do not have the necessary permissions");
            exit(1);
        }
        $this->Project->id = $project['Project']['id'];

        $devtrack_config = Configure::read('devtrack');
        $repo_path = $devtrack_config['repo']['base'];
        //Now check if the user has the correct permissions for the operation they are trying to perform


        if (in_array($command['command'], $read_commands) and ($this->Project->hasRead($userid))){
            // read requested and they have permission, serve the request
            print $command['command'] . ' \'' . $repo_path . '/' . $_proj_name . '\'';
            exit(0);
        } else if (in_array($command['command'], $write_commands) and ($this->Project->hasWrite($userid))) {
             // write requested and they have permission, serve the request
            print $command['command'] . ' \'' . $repo_path . '/' . $_proj_name . '\'';
            exit(0);
        } else {
            // they do not have permission
            $this->err("Error: You do not have the necessary permissions");
            exit(1);
        }
    }

    /**
    * Override the default welcome. We do not want to print the welcome message as this breaks git, so do nothing
    */
    protected function _welcome(){

    }

}

?>
