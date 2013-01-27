#!/bin/bash


# git setup script for the DevTrack system
# Sets up the git user to work with DevTrack
# Creates the user with appropriate settings, creates the SSH key files and 
# adds the webserver user to the git group so it can view repositories
#
# Licensed under The MIT License
# Redistributions of files must retain the above copyright notice.
#
# @copyright     DevTrack Development Team 2012
# @link          http://github.com/SourceKettle/devtrack
# @package       DevTrack.scm-scripts
# @since         DevTrack v 0.1
# @license       MIT License (http://www.opensource.org/licenses/mit-license.php)

# Firstly we create the git user.
# Setup as a system user, with no password login and various other settings

set -e # Exit on error

# Check if user is running as root
if [ "$(whoami)" != "root" ]; then
	echo "$(tput setaf 1)You need to be root to run this script. Exiting.$(tput sgr0)"
	exit
fi

# Some defaults for where to store repositories, and the user for git
DEFAULT_REPO_DIR='/home/git/repositories'
DEFAULT_GIT_USER='git'


# What flavour of Teh Lunix is this? A bit crude, just checks for RHEL or Debian-ish things
# Tested on Ubuntu and RHEL6.
DISTRO='Debian';
if [ -f /etc/redhat-release ]; then
	DISTRO='RedHat';
elif [ -f /etc/debian_version ]; then
	DISTRO='Debian';
fi;



# Set defaults based on distro
if [ "$DISTRO" == "Debian" ]; then
	DEFAULT_WWW_USER="www-data"
elif [ "$DISTRO" == "RedHat" ]; then
	DEFAULT_WWW_USER="apache"
else
	DEFAULT_WWW_USER="apache"
fi

# Prompt for a git username
echo "I need to create a user account for git/svn access."
echo "Please enter a username:"
echo -n "[default: $DEFAULT_GIT_USER] >"
read -e GIT_USER
if [ -z $GIT_USER ]; then
	GIT_USER=$DEFAULT_GIT_USER
fi

# Prompt for a repo directory
echo "I need somewhere to store git/svn repositories."
echo "Please enter a repo dir:"
echo -n "[default: $DEFAULT_REPO_DIR] >"
read -e REPO_DIR
if [ -z $REPO_DIR ]; then
	REPO_DIR=$DEFAULT_REPO_DIR
fi

# Find out the apache/www-data/whatever username
WWW_USER=''
while [ 1 ]
do
    echo "What user does your webserver run as?"
    read -p "[$DEFAULT_WWW_USER] > "
    WWW_USER=$REPLY
	if [ -z $WWW_USER ]; then
		WWW_USER=$DEFAULT_WWW_USER
	fi
    if [ -z "$(getent passwd $WWW_USER)" ] 
    then
            echo "$(tput setaf 1) Error. The given user does not exist. Please try again.$(tput sgr0)"
    else
            break
    fi
done


### Create git user for SSH access ###

# User already exists, so don't bother creating it
if [ ! -z "$(getent passwd $GIT_USER)" ]; then
	echo "$(tput setaf 1)[WARN]$(tput sgr0) $GIT_USER already exists, so I'll skip creating it..."

# Create user - Debian-style
elif [ "$DISTRO" == "Debian" ]; then

	echo "Add $GIT_USER to the system (Debian)"
    adduser \
        --system \
        --shell /bin/sh \
        --gecos 'git user' \
        --group \
        --disabled-password \
        --home /home/$GIT_USER \
        --quiet \
        $GIT_USER

	echo "$(tput setaf 2) Done!$(tput sgr0)"

# Create user - RedHat-style
elif [ "$DISTRO" == "RedHat" ]; then

	echo "Add $GIT_USER to the system (RedHat)"
    adduser \
        --system \
        --shell /bin/sh \
        --home /home/$GIT_USER \
        $GIT_USER

	echo "$(tput setaf 2) Done!$(tput sgr0)"

# Shouldn't hit this one, but meh
else
	echo "$(tput setaf 1)[ERROR]$(tput sgr0) For some reason, I have no idea how to create users on your distro ($DISTRO) :-("
fi


# Create files for storing SSH keys and set permissions
mkdir -p /home/$GIT_USER
chown $GIT_USER /home/$GIT_USER

echo "Creating SSH key files..."
sudo -H -u $GIT_USER mkdir -p /home/$GIT_USER/.ssh
sudo -H -u $GIT_USER touch /home/$GIT_USER/.ssh/authorized_keys
echo "$(tput setaf 2) Done!$(tput sgr0)"

echo "Updating file permissions..."
sudo -H -u $GIT_USER chmod 0700 /home/$GIT_USER/.ssh
sudo -H -u $GIT_USER chmod 0600 /home/$GIT_USER/.ssh/authorized_keys

echo "$(tput setaf 2) Done!$(tput sgr0)"


### Create devtrack group and add git user + webserver user ###


echo "Creating devtrack group..."
if [ -z "$(getent group devtrack)" ]
then
	groupadd --system devtrack
else
	echo
	echo "The devtrack group already exists, skipping..."
fi


echo "Adding webserver user $WWW_USER to devtrack group..."
usermod -aG devtrack $WWW_USER

echo "Adding git user to devtrack group..."
usermod -aG devtrack $GIT_USER 
echo "$(tput setaf 2) Done!$(tput sgr0)"



### Create repository storage if needed ###

echo "Creating repository storage directory..."
if [ -x $REPO_DIR ]
then
    echo "$REPO_DIR already exists - are you sure about this?"
	echo "I'll mess with the permissions so don't come crying to me if it all goes horribly wrong..."
    read -p "Really use this directory? [y/n] > "
    YARLY=$REPLY
    if [ $YARLY != 'y' ]; then
        echo "OK then..."
        exit
    fi

	# Dereference any symlinks so we can set permissions properly...
	REPO_DIR=$(readlink -f $REPO_DIR);
	echo "Symlinks dereferenced - repo dir is at $REPO_DIR"
fi


echo "Creating repository directory [$REPO_DIR] and setting permissions..."
mkdir -p $REPO_DIR
chown -R $GIT_USER:devtrack $REPO_DIR
chmod -R g+rwxs $REPO_DIR

echo "$(tput setaf 2) Done!$(tput sgr0)"

