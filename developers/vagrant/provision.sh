#!/bin/bash

# NB Vagrant VMs are transitory and only accessible on the local machine (unless you fiddle with the settings)
# so having a fairly noddy root password should be fine. Change if this bothers you. Don't set it to anything you care about people seeing on the web.
MYROOTPASS="shoes";

# Database name and repo dir to use (probably don't use the default, it may help to dig up bugs...)
MYDBNAME="skettle";
DATADIR="/var/skettle";

# Install useful packages
yum -y remove php53*
yum -y install vim nano git npm php56u php56u-mysql php56u-mbstring php56u-ldap php56u-pecl-apc php56u-pecl-xdebug MySQL-python
npm install -g less
npm install -g less-plugin-clean-css
ln -s /usr/lib/node_modules/less-plugin-clean-css/node_modules/clean-css/bin/cleancss /usr/bin/cleancss

# Stop apache, we're about to mess with it...
service httpd stop

# Configure PHP with settings needed for SourceKettle to work
# The APC garbage collection TTL is to ensure the tests run properly, not needed in production
cat >/etc/php.d/sourcekettle.ini <<EOF
[PHP]
short_open_tag = On
apc.cli_enabled = On
apc.enable_cli = On
apc.gc_ttl=10000
date.timezone=UTC
EOF

# Wedge in some apache config with all the right directories set
sed -i 's/\/var\/www\/public/\/var\/www\/sourcekettle/g' /etc/httpd/conf/httpd.conf
cat >/etc/httpd/conf.d/sourcekettle.conf <<EOF
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

        ErrorLog /var/www/apache-logs/error.log

        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn

        CustomLog /var/www/apache-logs/access.log combined


</VirtualHost>
EOF

# Testing installer page
service httpd start; #exit 0;

# Run the sourcekettle setup script to create the database, repo dir etc.
cd /var/www/sourcekettle
./scm-scripts/sourcekettle-setup.py --db-rootpass='' --db-name=${MYDBNAME} --create-test-db --test-db-name=${MYDBNAME}_test --repo-dir=${DATADIR}/repositories --scm-user=git --scm-group=gitkettle --www-user=vagrant
chown -R vagrant:vagrant ${DATADIR}
cd /var/www/sourcekettle/app

# Configure repo dir
./Console/cake setting set SourceRepository.base ${DATADIR}/repositories

# Create some users
./Console/cake user add admin@localhost.local "System Administrator" -a -p adminPassword
./Console/cake user add user@localhost.local "Project user"  -p userPassword
./Console/cake user add guest@localhost.local "Project guest"  -p guestPassword

# Create a project or two...
./Console/cake project add private_project -a admin@localhost.local -u user@localhost.local -g guest@localhost.local
./Console/cake project add public_project -p -a admin@localhost.local -u user@localhost.local -g guest@localhost.local

# Add some project milestones...
./Console/cake milestone add private_project "Do something soon"
./Console/cake milestone add private_project "Do something later"
./Console/cake milestone add private_project "Do something even later"
./Console/cake milestone add public_project "ODN scheduled maintenance"
./Console/cake milestone add public_project "Warp core scheduled maintenance"
./Console/cake milestone add public_project "Shakedown cruise"

# Populate the private project with tasks attached to milestones
./Console/cake task add private_project "Fix first thing for private project"  -p blocker -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix second thing for private project" -p urgent  -o user@localhost.local -a guest@localhost.local -m 1
./Console/cake task add private_project "Fix third thing for private project"  -p major   -o guest@localhost.local -a admin@localhost.local -m 1
./Console/cake task add private_project "Fix fourth thing for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1
./Console/cake task add private_project "Fix many things for private project" -p minor   -o admin@localhost.local -a user@localhost.local -m 1

# Populate the public milestone with unattached tasks
./Console/cake task add public_project "Fix first thing for public project"  -p blocker -o admin@localhost.local -a user@localhost.local
./Console/cake task add public_project "Fix second thing for public project" -p urgent  -o user@localhost.local -a guest@localhost.local
./Console/cake task add public_project "Fix third thing for public project"  -p major   -o guest@localhost.local -a admin@localhost.local
./Console/cake task add public_project "Fix fourth thing for public project" -p minor   -o admin@localhost.local -a user@localhost.local

# Start the webserver, we're ready to rock!
service httpd start
