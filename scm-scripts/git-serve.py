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
import sys
import commands
import traceback

CAKE = traceback.extract_stack(limit=1)[0][0][0:-len("/scm-scripts/git-serve.py")]
GITSERVEPHP = '%s/app/Console/cake -app %s/app/ git serve' % (CAKE, CAKE)
status, output = commands.getstatusoutput('%s %s' % (GITSERVEPHP, sys.argv[1]))
if status == 0:
    os.execvp('git', ['git', 'shell', '-c', output.strip()])
else:
    sys.stderr.write("%s\n" % output)
sys.exit(1)
