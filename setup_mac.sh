# Copied from: http://jason.pureconcepts.net/2014/11/install-apache-php-mysql-mac-os-x-yosemite/
sudo apachectl start
sudo sed -i -e 's/#LoadModule php5/LoadModule php5/' /etc/apache2/httpd.conf
sudo apachectl restart

sudo ln -s $PWD /Library/WebServer/Documents/marchmadnessest

echo "Go download and install MySQL from https://dev.mysql.com/downloads/mysql/"

read -p "Press the [Enter] once it's installed..."

sudo mkdir /var/mysql
sudo ln -s /tmp/mysql.sock /var/mysql/mysql.sock

sudo /usr/local/mysql/support-files/mysql.server restart

echo "export PATH=/usr/local/mysql/bin:\$PATH" >> ~/.bashrc
source ~/.bashrc
