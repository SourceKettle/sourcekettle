#!/usr/bin/env python
#
# SourceKettle setup script
# Prompts the user for several settings:
# * MySQL root password and database name
# * Source control repository directory
# * Source control username for SSH access
# * Webserver username
# * Source control group name
# It then creates the initial database and writes the config file.
#
#
# @copyright     SourceKettle Development Team 2012
# @link          http://github.com/SourceKettle/sourcekettle
# @package       SourceKettle.Console.Command
# @since         SourceKettle v 0.1
# @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
#

import os, stat, sys, pwd, grp, ConfigParser
import re, subprocess, shlex, MySQLdb, string
from getpass  import getpass, getuser
from optparse import OptionParser
from random   import choice
from socket   import gethostname
from os.path  import abspath, dirname

# Check we're running as root to start with, or this will never work...
try:
    current_user = getuser()
except:
    current_user = 'nobody'

if str(current_user) != 'root':
    print "Error - this script must be run as root (try using sudo)"
    sys.exit(1)



# Default values for everything we need to know,
# so the user can basically thump return a lot and
# get a sane setup!
defaults = {
    'repo_dir'    : '/var/sourcekettle/repositories',
    'scm_user'    : 'git',
    'scm_group'   : 'sourcekettle',
    'www_user'    : 'www-data',
    'db_name'     : 'sourcekettle',
    'adduser_cmd' : 'adduser --system --shell /bin/sh --gecos "sourcekettle scm" --group --disabled-password --home /home/__USER__ --quiet __USER__'
}

# We have different defaults on Debian versus RedHat
# Default to using the debian-ish settings
if os.path.isfile('/etc/redhat-release'):
    defaults['www_user'] = 'apache'
    defaults['adduser_cmd'] = 'adduser --system --shell /bin/sh --home /home/__USER__ __USER__'

# Add more distro settings here if needed e.g.
#elif os.path.isfile('/etc/debian_version'):


def bail(msg):
    print msg
    sys.exit(1)

# Fudgery, cos exceptions are inconvenient...
def getpwnam(user):
    try:
        return pwd.getpwnam(user)
    except:
        return None

def getgrnam(group):
    try:
        return grp.getgrnam(group)
    except:
        return None

def chown_r(dir, uid, gid):
    for r, d, f in os.walk(dir):
        os.chown(r, uid, gid)

def chmod_r(dir, mode):
    for r, d, f in os.walk(dir):
        os.chmod(r, mode)

# Parse command line arguments, if we have them...

parser = OptionParser(usage='usage: %prog [options]')

parser.add_option('-d', '--use-defaults', dest='use_defaults',
                  help='Automatically use SourceKettle default values for anything you have not specified (without prompting)')

parser.add_option('', '--db-rootpass', dest='db_rootpass',
                  help='The MySQL root password', metavar='PASS')

parser.add_option('', '--db-host', dest='db_host', default='localhost',
                  help='Hostname of the MySQL server', metavar='HOST')

parser.add_option('', '--db-name', dest='db_name',
                  help='Name of the SourceKettle database (will also be used as the database username for connections)', metavar='NAME')

parser.add_option('', '--repo-dir', dest='repo_dir',
                  help='Directory to store the SCM (git/svn) repositories', metavar='DIR')

parser.add_option('', '--scm-user', dest='scm_user',
                  help='Username for SSH access to repositories', metavar='USERNAME')

parser.add_option('', '--scm-group', dest='scm_group',
                  help='Group name for access to repositories', metavar='GROUP')

parser.add_option('', '--www-user', dest='www_user',
                  help='Username your webserver runs under', metavar='USERNAME')

(options, args) = parser.parse_args()
use_defaults    = options.use_defaults
db_rootpass     = options.db_rootpass
db_host         = options.db_host
db_name         = options.db_name
repo_dir        = options.repo_dir
scm_user        = options.scm_user
scm_group       = options.scm_group
www_user        = options.www_user


# Now fill in any blanks by prompting or using defaults
while db_rootpass == None:

    # Look for the MySQL root password in ~/.my.cnf
    # CAVEAT: Python's ConfigParser is a bit indent-sensitive
    # whereas MySQL isn't, so this may fail... oh well.
    if os.path.isfile(os.path.expanduser('~')+'/.my.cnf'):
        try:
            mycnf = ConfigParser.RawConfigParser()
            mycnf.read(os.path.expanduser('~')+'/.my.cnf')
            db_rootpass = mycnf.get('client', 'password')
            print "I have read your MySQL root password from ~/.my.cnf"
        except ConfigParser.ParsingError:
            pass

    # If we can't find it, prompt for it...
    if db_rootpass == None:
        db_rootpass = getpass('Enter MySQL root password:')


# Connect to the MySQL database server as root
try:
    dbc = MySQLdb.connect (
      host = db_host,
      user = "root",
      passwd = db_rootpass,
    )
except:
    bail("Failed to connect to MySQL on '"+db_host+"' with the password given! Aborting setup.")



while scm_user == None:

    if use_defaults:
        scm_user = defaults['scm_user']
        break

    print "I need to create a user account for git/svn access."
    print "Please enter a username"
    scm_user = raw_input('['+str(defaults['scm_user'])+']: ')

    if scm_user == None or len(scm_user.strip()) < 1:
        scm_user = defaults['scm_user']


while www_user == None:

    if use_defaults:
        www_user = defaults['www_user']
        break

    print "What user does your webserver run as?"
    www_user = raw_input('['+str(defaults['www_user'])+']: ')

    if www_user == None or len(www_user.strip()) < 1:
        www_user = defaults['www_user']


while scm_group == None:

    if use_defaults:
        scm_group = defaults['scm_group']
        break

    print "The webserver user and git/svn user both need repository access."
    print "I need to create a group to put them both in."
    print "Please enter a group name"
    scm_group = raw_input('['+str(defaults['scm_group'])+']: ')

    if scm_group == None or len(scm_group.strip()) < 1:
        scm_group = defaults['scm_group']


while repo_dir == None:

    if use_defaults:
        repo_dir = defaults['repo_dir']
        break

    print "I need somewhere to store git/svn repositories."
    print "Please enter a repo dir"
    repo_dir = raw_input('['+str(defaults['repo_dir'])+']: ')

    if repo_dir == None or len(repo_dir.strip()) < 1:
        repo_dir = defaults['repo_dir']


while db_name == None:

    if use_defaults:
        db_name = defaults['db_name']
        break

    print "I need to create a MySQL database for SourceKettle."
    print "Please enter a database name."
    db_name = raw_input('['+str(defaults['db_name'])+']: ')

    if db_name == None or len(db_name.strip()) < 1:
        db_name = defaults['db_name']

# Sanity checks
www_user  = www_user.strip()
scm_user  = scm_user.strip()
scm_group = scm_group.strip()
repo_dir  = repo_dir.strip()
db_name   = db_name.strip()


problem = False
if not re.match(r'^[A-Za-z0-9_]+$', db_name):
    print "ERROR: database name may only contain upper and lowercase ASCII letters, numbers, or underscore."
    print "Yes, MySQL supports other characters, but please keep it simple."
    problem = True

if not re.match(r'^[0-9a-zA-Z_-]+$', www_user):
    print "ERROR: WWW user '"+str(www_user)+"' is not a valid UNIX username."
    print "Letters, numbers, underscores and dashes only please."
    problem = True
else:
    www_user_data  = getpwnam(www_user)

    if www_user_data == None:
        print "ERROR: webserver user '"+str(www_user)+"' does not exist!"
        problem = True

if not re.match(r'^[0-9a-zA-Z_-]+$', scm_user):
    print "ERROR: SCM user '"+str(scm_user)+"' is not a valid UNIX username."
    print "Letters, numbers, underscores and dashes only please."
    problem = True
else:

    scm_user_data  = getpwnam(scm_user)

    if scm_user_data != None:

        print "WARNING: user '"+str(scm_user)+"' already exists!"
        print "Are you absolutely sure you want to use that user account?"

        ok = raw_input('[y/n]:')
        if ok.strip().lower() != 'y':
            problem = True

if not re.match(r'^[0-9a-zA-Z_-]+$', scm_group):
    print "ERROR: SCM group '"+str(scm_group)+"' is not a valid UNIX group name."
    print "Letters, numbers, underscores and dashes only please."
    problem = True
else:

    scm_group_data = getgrnam(scm_group)

    if scm_group_data != None:
        print "WARNING: SCM group '"+str(scm_group)+"' already exists!"
        print "Are you absolutely sure you want to use that group?"

        ok = raw_input('[y/n]:')
        if ok.strip().lower() != 'y':
            problem = True


repo_dir = os.path.realpath(repo_dir)
print "Canonicalised and resolved symlinks:"
print "Real repo directory is '"+repo_dir+"'"

if re.search(r'\s', repo_dir):
    print "Path '"+str(repo_dir)+"' contains spaces - technically this would work,"
    print "but let's not do that.  It's bound to cause trouble."
    problem = True

elif repo_dir == '/':
    print "OK, for some reason you're trying to use / as your repo directory."
    print "I'm afraid I can't do that, Dave."
    problem = True

elif os.path.exists(repo_dir):
    print "WARNING: Repo directory '"+str(repo_dir)+"' already exists!"
    print "Are you absolutely sure you want to use it?"

    ok = raw_input('[y/n]:')
    if ok.strip().lower() != 'y':
        problem = True

# NB db_name is sanitised above
c=dbc.cursor()
c.execute("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = %s", (db_name))
data = c.fetchone()
create_db = True

if(data):
    print "WARNING: MySQL database '"+str(db_name)+"' already exists!"
    print "I can use this database if you like, but I will NOT attempt to create the schema."
    print "Are you absolutely sure you want to use it?"

    ok = raw_input('[y/n]:')
    if ok.strip().lower() == 'y':
        print "OK, I won't touch that then..."
        create_db = False
    else:
        problem = True


if problem:
    bail("Aborting...")

## Now we have all the info we need, start the setup process

# Generate a random password containing only a-z, A-Z, 0-9
db_pass = ''
chars = string.letters + string.digits
for i in range(15):
    db_pass += choice(chars)

print "I have auto-generated a database password for you (%s)" % (db_pass)

c.execute("GRANT ALL ON `%s`.* TO `%s`@`%s` IDENTIFIED BY '%s'" % (db_name, db_name, gethostname(), db_pass))

# If the database server is running locally, also grant to localhost
if db_host.lower() == 'localhost':
    c.execute("GRANT ALL ON `%s`.* TO `%s`@`localhost` IDENTIFIED BY '%s'" % (db_name, db_name, db_pass))

if create_db:
    print "Creating SourceKettle database..."
    c=dbc.cursor()
    c.execute('CREATE DATABASE `%s`' % db_name)


    print "Creating database schema..."

    sql_file = abspath(dirname(__file__)+'/../db.sql')
    print "Importing from "+str(sql_file)

    sql_obj = open(sql_file, 'r')
    if not sql_obj:
        print "Error importing database schema from %s!" % sql_file

    # So, yeeeah, this is much easier than 'cat db.sql | mysql' ...
    import_cmd = 'mysql %s -h %s -u %s --password=%s' % (db_name, db_host, db_name, db_pass)

    try:
        p = subprocess.Popen(shlex.split(import_cmd), stdin=subprocess.PIPE)
    except subprocess.CalledProcessError:
        bail("Failed to load database schema")

    line = sql_obj.readline()
    while line:
        p.stdin.write(line)
        line = sql_obj.readline()



print "Creating user and group..."

if scm_user_data  == None:
    adduser_cmd = re.sub(r'__USER__', "'"+scm_user+"'", defaults['adduser_cmd'])
    try:
        output = subprocess.Popen(shlex.split(adduser_cmd), stdout=subprocess.PIPE).communicate()[0]
    except subprocess.CalledProcessError:
        bail("Failed to create user '"+scm_user+"'!")

    scm_user_data = getpwnam(scm_user)
    if scm_user_data  == None:
        bail("Failed to create user '"+scm_user+"'!")


if scm_group_data == None:
    addgroup_cmd = 'groupadd --system %s' % ("'"+scm_group+"'")
    try:
        output = subprocess.Popen(shlex.split(addgroup_cmd), stdout=subprocess.PIPE).communicate()[0]
    except subprocess.CalledProcessError:
        bail("Failed to create group '"+scm_group+"'!")

    scm_group_data = getgrnam(scm_group)

    if scm_group_data == None:
        bail("Failed to create group '"+scm_group+"'!")


print "Adding SCM and WWW users to the "+str(scm_group)+" group..."

add2group_cmd = 'usermod -aG '+str(scm_group)+' '+str(scm_user)
try:
    output = subprocess.Popen(shlex.split(add2group_cmd), stdout=subprocess.PIPE).communicate()[0]
except subprocess.CalledProcessError:
    bail("Failed to add user '"+str(scm_user)+"' to group '"+scm_group+"'!")


add2group_cmd = 'usermod -aG '+str(scm_group)+' '+str(www_user)
try:
    output = subprocess.Popen(shlex.split(add2group_cmd), stdout=subprocess.PIPE).communicate()[0]
except subprocess.CalledProcessError:
    bail("Failed to add user '"+str(www_user)+"' to group '"+scm_group+"'!")


# For permission setting, we need the UID and GID
scm_uid = scm_user_data.pw_uid
scm_gid = scm_group_data.gr_gid


print "Creating empty home directory for "+str(scm_user)
homedir = '/home/'+str(scm_user)
os.makedirs(homedir+'/.ssh')
open(homedir+'/.ssh/authorized_keys', 'w').close()


print "Updating file permissions..."
chown_r('/home/'+str(scm_user), scm_uid, scm_gid)

os.chmod('/home/'+str(scm_user)+'/.ssh', 0700)
os.chmod('/home/'+str(scm_user)+'/.ssh/authorized_keys', 0600)



### Create repository storage if needed ###
print "Creating repository storage directory..."

if not os.path.exists(repo_dir):
    os.makedirs(repo_dir)

chown_r(repo_dir, scm_uid, scm_gid)
chmod_r(repo_dir, stat.S_IRWXU | stat.S_ISGID)

print "Building SourceKettle config files..."

config_template = abspath(dirname(__file__)+'/../app/Config/devtrack.php.template')
config_file     = abspath(dirname(__file__)+'/../app/Config/devtrack.php')

config_tpl = open(config_template, 'r')
if not config_tpl:
    print "Error reading config template from '%s'!" % config_template

config = open(config_file, 'w')
if not config:
    print "Error writing to config file '%s'!" % config_file

line = config_tpl.readline()
while line:
    line = line.replace('__SCM_USER__', scm_user)
    line = line.replace('__REPO_DIR__', repo_dir)
    config.write(line)
    line = config_tpl.readline()

config_tpl.close()
config.close()

db_template = abspath(dirname(__file__)+'/../app/Config/database.php.template')
db_file     = abspath(dirname(__file__)+'/../app/Config/database.php')

db_tpl = open(db_template, 'r')
if not db_tpl:
    print "Error reading db template from '%s'!" % db_template

db = open(db_file, 'w')
if not db:
    print "Error writing to db file '%s'!" % db_file

line = db_tpl.readline()
while line:
    line = line.replace('__DB_HOST__', db_host)
    line = line.replace('__DB_NAME__', db_name)
    line = line.replace('__DB_PASS__', db_pass)
    db.write(line)
    line = db_tpl.readline()

db_tpl.close()
db.close()

core_template = abspath(dirname(__file__)+'/../app/Config/global.php.template')
core_file     = abspath(dirname(__file__)+'/../app/Config/global.php')

core_tpl = open(core_template, 'r')
if not core_tpl:
    print "Error reading global template from '%s'!" % core_template

core = open(core_file, 'w')
if not core:
    print "Error writing to gloabl file '%s'!" % core_file

line = core_tpl.readline()
while line:
    core.write(line)
    line = core_tpl.readline()

core_tpl.close()
core.close()

print "Setup complete!"
print "Your auto-generated MySQL password is: '%s'" % db_pass
print "This password has been added to the SourceKettle config file, but you may wish to make a note of it."
