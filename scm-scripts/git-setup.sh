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
# @link          http://github.com/chrisbulmer/devtrack
# @package       DevTrack.scm-scripts
# @since         DevTrack v 0.1
# @license       MIT License (http://www.opensource.org/licenses/mit-license.php)

# Firstly we create the git user.
# Setup as a system user, with no password login and various other settings

set -e # Exit on error

DEFAULT_REPODIR='/home/git/repositories'
GIT_USER='git'

# Check if user is running as root
if [ "$(whoami)" != "root" ]; then
    echo "$(tput setaf 1)You need to be root to run this script. Exiting.$(tput sgr0)"
    exit
fi


printf "Adding git user '$GIT_USER' to system..."

# Check if user exists
if [ -z "$(getent passwd $GIT_USER)" ]
then
    #Add user
    sudo adduser \
        --system \
        --shell /bin/sh \
        --gecos 'git user' \
        --group \
        --disabled-password \
        --home /home/$GIT_USER \
        --quiet \
        $GIT_USER

    echo "$(tput setaf 2) Done!$(tput sgr0)"
else   
    #echo "$(tput setaf 1) Fail!$(tput sgr0)"
    #echo "$(tput setaf 1)User git already exists. Exiting.$(tput sgr0)"
	echo
	echo "User $GIT_USER already exists, skipping user creation"
fi

# Create files for storing SSH keys and set permissions

printf "Creating SSH key files..."
sudo -H -u $GIT_USER mkdir -p /home/$GIT_USER/.ssh
sudo -H -u $GIT_USER touch /home/$GIT_USER/.ssh/authorized_keys
echo "$(tput setaf 2) Done!$(tput sgr0)"

printf "Updating file permissions..."
sudo -H -u git chmod 0700 /home/$GIT_USER/.ssh
sudo -H -u git chmod 0600 /home/$GIT_USER/.ssh/authorized_keys

echo "$(tput setaf 2) Done!$(tput sgr0)"

# Now add webserver user to git group
PASS='false'
USER=''
while [ $PASS = 'false' ]
do
    printf "What user does your webserver run as? (Usually www-data) \r\n"
    read -p " > "
    USER=$REPLY
    if [ -z "$(getent passwd $USER)" ] 
    then
            echo "$(tput setaf 1) Error. The given user does not exist. Please try again.$(tput sgr0)"
    else
            break
    fi
done


printf "Adding webserver user to devtrack group..."
if [ -z "$(getent group devtrack)" ]
then
	sudo groupadd --system devtrack
else
	echo
	echo "The devtrack group already exists, skipping..."
fi
sudo usermod -aG devtrack $USER
printf "Adding git user to devtrack group..."
sudo usermod -aG devtrack $GIT_USER 
echo "$(tput setaf 2) Done!$(tput sgr0)"


REPODIR=''
printf "Where should project source repositories be stored? \r\n"
read -p "[$DEFAULT_REPODIR] > "
REPODIR=$REPLY

if [ -z $REPODIR ]
then
    REPODIR=$DEFAULT_REPODIR
fi

if [ -x $REPODIR ]
then
    echo "$REPODIR already exists - are you sure about this?"
	echo "I'll mess with the permissions so don't come crying to me if it all goes horribly wrong..."
    read -p "Really use this directory? [y/n] > "
    YARLY=$REPLY
    if [ $YARLY != 'y' ]; then
        printf "OK then..."
        exit
    fi
fi


printf "Creating repository directory [$REPODIR] and setting permissions..."
sudo mkdir -p $REPODIR
sudo chown $GIT_USER:devtrack $REPODIR
sudo chmod -R g+rwxs $REPODIR

echo "$(tput setaf 2) Done!$(tput sgr0)"
