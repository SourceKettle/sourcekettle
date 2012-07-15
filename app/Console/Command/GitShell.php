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

            $verbosity = 'quiet';

            //$template = 'command="%s %s --%s",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty %s';
            $template = 'command="%s %s",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty %s';

            $console_path= App::path('Console');
            $app_path = App::path('Controller');
            $app_path = $app_path[0];
            $app_path = str_replace('/Controller', '', $app_path);

            $cmd = $console_path[0] . 'cake -app ' . $app_path . ' git serve';

            $out = '';
            foreach ($keys as $key) {
                $sshkey = $key['SshKey']['key'];
                $userid = $key['User']['id'];

                if (strlen($sshkey) > 40) { //sanity check on key
                    $content = trim(str_replace(array("\n", "\r"), '', $sshkey));
                    //$out .= sprintf($template, $cmd, $userid, $verbosity, $content) . "\n";
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
            throw new Exception("Required environment variables are not defined");
            exit(1);
        }

        $ssh_original_command = $vars['SSH_ORIGINAL_COMMAND']; 
        $argv = $vars['argv'];
        $userid = $argv[sizeof($argv)-1];

        //Secondly, validate the arguments and get the command into a generic format

        //check if SSH_ORIGINAL_COMMAND contains new lines
        if (strpos($ssh_original_command, "\n") !== false){ //!=== as it may also return non-boolean values that evaluate to false
            throw new Exception("SSH_ORIGINAL_COMMAND contains new lines");
            exit(2);
        }


        $command_parts = explode(" ", $ssh_original_command, 2);

        //Check if the command has 2 parts
        if (sizeof($command_parts) != 2){
            throw new Exception("Command is not a valid git command");
            exit(3);
        }

        $command = array(); //initialise an empty array

        //Get the command into a generic format
        if ($command_parts[0] == 'git'){
            $command_args = explode(" ", $command_parts[1], 2); //split into the git command name and the arguments
            if (sizeof($command_args) != 2){
                throw new Exception("Wrong number of arguments to a git command");
                exit(4);
            } else {
                $command['command'] = $command_parts[0] . $command_args[0];
                $command['args'] = $command_args[1];
            }
        } else {
            $command['command'] = $command_parts[0];
            $command['args'] = $command_parts[1];
        }

        // Now check if the parts are a valid git command
        $read_commands = array('git-upload-pack', 'git upload-pack');
        $write_commands = array('git-receive-pack', 'git receive-pack');
        $args_regex = '#^\'/*(?P<path>[a-zA-Z0-9][a-zA-Z0-9@._-]*(/[a-zA-Z0-9][a-zA-Z0-9@._-]*)*)\'$#';

        if (!in_array($command['command'], $read_commands) and !in_array($command['command'], $write_commands)){
            throw new Exception("Unknown command");
            exit(5);
        } else if(!preg_match($args_regex, $command['args'], $matches)){
            throw new Exception("Arguments to command do not look safe");
            exit(6);
        }

        $repo_path = $matches['path'];
        //Now check if the user has the correct permissions
        var_dump($repo_path);

    }

}

?>
