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

# Check if user is running as root
if [ "$(whoami)" != "root" ]; then
    echo "$(tput setaf 1)You need to be root to run this script. Exiting.$(tput sgr0)"
    exit
fi


printf "Adding git user to system..."

# Check if user exists
if [ -z $(getent passwd git) ]
then
    #Add user
    sudo adduser \
        --system \
        --shell /bin/sh \
        --gecos 'git user' \
        --group \
        --disabled-password \
        --home /home/git \
        --quiet \
        git

    echo "$(tput setaf 2) Done!$(tput sgr0)"
else   
    echo "$(tput setaf 1) Fail!$(tput sgr0)"
    echo "$(tput setaf 1)User git already exists. Exiting.$(tput sgr0)"
    exit
fi

# Create files for storing SSH keys and set permissions

printf "Creating SSH key files..."
sudo -H -u git mkdir /home/git/.ssh
sudo -H -u git touch /home/git/.ssh/authorized_keys
echo "$(tput setaf 2) Done!$(tput sgr0)"

printf "Updating file permissions..."
sudo -H -u git chmod 0700 /home/git/.ssh
sudo -H -u git chmod 0600 /home/git/.ssh/authorized_keys

echo "$(tput setaf 2) Done!$(tput sgr0)"

# Now add webserver user to git group
PASS='false'
USER=''
while [ $PASS = 'false' ]
do
    printf "What user does your webserver run as? (Usually www-data) \r\n"
    read -p " > "
    USER=$REPLY
    if [ -z $(getent passwd $USER) ] 
    then
            echo "$(tput setaf 1) Error. The given group does not exist. Please try again.$(tput sgr0)"
    else
            break
    fi
done
    
printf "Adding webserver user to git group..."
sudo usermod -aG git $USER 
echo "$(tput setaf 2) Done!$(tput sgr0)"