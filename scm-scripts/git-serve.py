#!/usr/bin/env python
# 
# 
# Git serve authorisation controller for the DevTrack system
# Ensures only allowed members can access certain git requests
# 
# Licensed under The MIT License
# Redistributions of files must retain the above copyright notice.
# 
# @copyright     DevTrack Development Team 2012
# @link          http://github.com/chrisbulmer/devtrack
# @package       DevTrack.Console.Command
# @since         DevTrack v 0.1
# @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
# 
import os
import os.path
import sys
import subprocess
import shlex

# XXX: There are probably better ways of pulling the parent directory
# but I really didn't want to deal with os.path.split
CAKE_ROOT = os.path.abspath(os.path.dirname(__file__) + '/../')
GITSERVEPHP = '{0}/app/Console/cake -app {0}/app/ git serve {1}'.format(CAKE_ROOT, sys.argv[1])
try:
    output = subprocess.check_output(shlex.split(GITSERVEPHP))
    subprocess.check_output(['git-shell', '-c', output.strip()])
except subprocess.CalledProcessError:
    sys.exit(1)
