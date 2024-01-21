# Backuppo
## Simple platform for backup a FortiGate appliances through RestAPI


Backuppo is a web-based application created with the simple idea ( for my needs ) of saving a FortiGate configuration using a secure method.

Few things to keep in mind:
- I'm not developer and i have ZERO programming skills, i'm very able to do copy / paste. ( Maybe i can make it appear an "Hello World" message on a linux terminal )
- I used my friend ChatGPT to do that.
- I know, i don't think is a quality code.
- Apparently seems to be working!

## Features

- Save a FortiGate configurations in secure mode. RestAPI with HTTPS secure communication
- Schedule a cron to plan when do the backup. ( Daily, Weekly or Monthly )
- Download the last backupped configuration
- View the job's log
- All the features are able to do through web interface

I appreciate if someone want to introduce new features, fixing or something else...

Anyway, if you have the same needs to me, just download and use it!

![Home Page](/screenshot/backuppo-1.jpg?raw=true "Main Page")
![Device Manage](/screenshot/backuppo-2.jpg?raw=true "Device Page")


## Tech

- [PHP]
- [Python]
- [SQL-Lite]
- [Bootstrap]

## Installation

Installation tested on Linux Ubuntu 22.04

Make sure have update the system:
```sh
apt update
apt upgrade -y
```

Install the dependencies and components:
```sh
apt install apache2 php libapache2-mod-php php-mysql
apt install sqlite3 php-sqlite3 dbus python3-pip libapache2-mod-python
pip3 install requests
```

Enable apache module for run correctly a python script
```sh
a2enmod cgi
```

Clone repository to home folder
```sh
apt install git
git clone https://github.com/danieledfr/backuppo-fortigate.git
```


## Configuration

Move the contents into www apache folder
```sh
cd backuppo-fortigate/
mv * /var/www
```

If exist html folder in /var/www then remove
```sh
cd /var/www
rm -R html/ ( only if html folder are exist )
```

Modify Apache index folder
```sh
nano /etc/apache2/sites-enabled/000-default.conf
modify DocumentRoot /var/www/html in /var/www
```

Make the database
```sh
python3 /var/www/cgi-bin/createdb.py
```

Assign a privileges for the application to write in a various folders
```sh
chown -R www-data:www-data /var/www
```

## Development

Want to contribute? Great!

## Docker

Maybe one day...

## License

MIT

**Free Software, Hell Yeah!**
