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
            return false;
        }

        $ssh_original_command = $vars['SSH_ORIGINAL_COMMAND']; 
        $argv = $vars['argv'];
        $userid = $argv[length($argv)-1];

        //Secondly, validate the arguments

        //check if SSH_ORIGINAL_COMMAND contains new lines
        if (strpos($ssh_original_command, "\n") !== false){ //!=== as it may also return non-boolean values that evaluate to false
            throw new Exception("SSH_ORIGINAL_COMMAND contains new lines.");
            return false;
        }

        var_dump($ssh_original_command);

    }

}

?>
