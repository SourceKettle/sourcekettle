#!/bin/bash

# NB Vagrant VMs are transitory and only accessible on the local machine (unless you fiddle with the settings)
# so having a fairly noddy root password should be fine. Change if this bothers you. Don't set it to anything you care about people seeing on the web.
MYROOTPASS="shoes";


#Pre-set mysql root password
debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYROOTPASS"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYROOTPASS"

# Install dependencies
apt-get update
apt-get install -y apache2 php5 mysql-client mysql-server git build-essential python-mysqldb vim php-apc php5-mysql php-pear php5-xdebug
a2enmod rewrite

pear config-set auto_discover 1
pear channel-discover pear.phpunit.de
pear install phpunit/PHPUnit-3.7.35

# Move /var/www to our vagrant directory
rm -rf /var/www
ln -fs /vagrant /var/www

# Stop apache, we're about to mess with it...
service apache2 stop

# Run apache as the 'vagrant' user, and stick logfiles in our persistent /vagrant/apache-logs dir
# so we can easily get at them without ssh-ing to the VM
sed -i 's/www-data/vagrant/g' /etc/apache2/envvars
sed -i 's|APACHE_LOG_DIR=.*$|APACHE_LOG_DIR=/vagrant/apache-logs|' /etc/apache2/envvars
chown -R vagrant:vagrant /var/lock/apache2

# Wedge in some apache config with all the right directories set
cat >/etc/apache2/sites-available/default <<EOF
<VirtualHost *:80>
        ServerAdmin webmaster@localhost

        DocumentRoot /var/www/sourcekettle
        <Directory />
                Options FollowSymLinks
                AllowOverride None
        </Directory>
        <Directory /var/www/sourcekettle/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error.log

        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn

        CustomLog \${APACHE_LOG_DIR}/access.log combined


</VirtualHost>
EOF

# Run the sourcekettle setup script to create the database, repo dir etc.
cd /var/www/sourcekettle
./scm-scripts/sourcekettle-setup.py --db-rootpass=$MYROOTPASS --db-name=skettle --create-test-db --test-db-name=skettle_test --repo-dir=/var/skettle/repositories --scm-user=git --scm-group=gitkettle --www-user=vagrant
chown -R vagrant:vagrant /var/skettle
cd /vagrant/sourcekettle/app

# Create some users
./Console/cake user add root@localhost.local "System Administrator" -a -p adminPassword
./Console/cake user add user@localhost.local "Project user"  -p userPassword
./Console/cake user add guest@localhost.local "Project guest"  -p guestPassword

# Create a project or two...
./Console/cake project add private_project -a root@localhost.local -u user@localhost.local -g guest@localhost.local
./Console/cake project add public_project -p -a root@localhost.local -u user@localhost.local -g guest@localhost.local

# Add some project milestones...
./Console/cake milestone add private_project "Do something soon"
./Console/cake milestone add private_project "Do something later"
./Console/cake milestone add private_project "Do something even later"
./Console/cake milestone add public_project "Read the Tea Leaves"
./Console/cake milestone add public_project "Read the Coffee Grounds"
./Console/cake milestone add public_project "Read the Red Bull Cans"

# Populate the private project with tasks attached to milestones
./Console/cake task add private_project "Fix first thing for private project"  -p blocker -o 1 -a 2 -m 1
./Console/cake task add private_project "Fix second thing for private project" -p urgent  -o 2 -a 3 -m 1
./Console/cake task add private_project "Fix third thing for private project"  -p major   -o 3 -a 1 -m 1
./Console/cake task add private_project "Fix fourth thing for private project" -p minor   -o 1 -a 2 -m 1

# Populate the public milestone with unattached tasks
./Console/cake task add public_project "Fix first thing for public project"  -p blocker -o 1 -a 2
./Console/cake task add public_project "Fix second thing for public project" -p urgent  -o 2 -a 3
./Console/cake task add public_project "Fix third thing for public project"  -p major   -o 3 -a 1
./Console/cake task add public_project "Fix fourth thing for public project" -p minor   -o 1 -a 2

# Start the webserver, we're ready to rock!
service apache2 start
