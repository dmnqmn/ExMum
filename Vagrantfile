# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "bento/centos-7.2"
  config.vm.hostname = "exmum.dev"
  config.vm.network "private_network", ip: "192.168.33.20"
  config.vm.synced_folder ".", "/vagrant", create: true, nfs: true
  config.ssh.forward_agent = true

  config.vm.provider "virtualbox" do |vb|
    vb.name = "ExMum"
    vb.memory = "1024"
    vb.cpus = "1"
    vb.customize ['modifyvm', :id, '--natdnshostresolver1', 'on']
    vb.customize ['modifyvm', :id, '--natdnsproxy1', 'on']
  end
  
  config.vm.provision "shell", inline: <<-SHELL
    echo "PROVISION........."
    echo "Install devtools"
    yum install -y gcc gcc-c++ glibc glibc-devel glibc-headers kernel-devel
    echo "Install Node"
    curl --silent --location https://rpm.nodesource.com/setup_7.x | bash -
    yum -y install nodejs
    yum groupinstall -y 'Development Tools'
    npm install -g yarn --registry=https://registry.npm.taobao.org
    echo "Install Nginx"
    yum install -y epel-release
    yum install -y nginx
    echo "Install PHP 7"
    yum install -y https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
    yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm
    yum install -y --enablerepo="remi,remi-php71" php71 php71-php-cli php71-php-devel \
        php71-php-mysqlnd php71-php-gd php71-php-mcrypt php71-php-pdo php71-php-pear \
        php71-php-pecl-memcache php71-php-pecl-memcached \
        php71-php-mbstring php71-php-pecl-msgpack php71-php-bcmath php71-php-fpm php71-php-pecl-zip
    if [ -e /bin/php ]; then rm /bin/php; fi
    ln -sf /usr/bin/php71 /bin/php
    echo "Install MariaDB and phpMyAdmin"
    if [ ! -e /bin/mysql ]; then
        yum install -y mariadb-server mariadb
        systemctl start mariadb
        mysqladmin -uroot password vagrant
    fi
    yum install -y phpmyadmin
    if [ -e /usr/share/nginx/html/phpMyAdmin ]; then rm /usr/share/nginx/html/phpMyAdmin; fi
    ln -s /usr/share/phpMyAdmin /usr/share/nginx/html
    if [ ! -e /var/lib/php/session ]; then
        chmod 777 -R /usr/share/nginx/html/phpMyAdmin
        mkdir -p /var/lib/php/session
        chmod 777 -R /var/lib/php/session
    fi
    echo "Install Composer"
    if ! /usr/local/bin/composer &>/dev/null; then
        curl -sS https://getcomposer.org/installer | php
        mv composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
    fi
    echo "Nginx & PHP Setup"
    if [ -e /etc/nginx/nginx.conf ]; then rm /etc/nginx/nginx.conf; fi
    ln -sf /vagrant/dev/nginx.conf /etc/nginx/nginx.conf
    if [ -e /etc/php.ini ]; then cp /etc/php.ini /etc/php.ini.bak && rm /etc/php.ini; fi
    ln -sf /vagrant/dev/php.ini /etc/php.ini
    if [ ! -e /var/log/php-fpm ]; then mkdir /var/log/php-fpm; fi
    if [ -e /etc/opt/remi/php71/php-fpm.d/www.conf ]; then rm -f /etc/opt/remi/php71/php-fpm.d/www.conf; fi
    ln -sf /vagrant/dev/php-fpm.conf /etc/opt/remi/php71/php-fpm.d/www.conf
    echo "Start services"
    systemctl enable php71-php-fpm
    systemctl enable nginx
    systemctl enable mariadb
    systemctl start php71-php-fpm
    systemctl start nginx
    systemctl start mariadb
    echo "Install Composer Packages and Perform Migrations"
    cd /vagrant && /usr/local/bin/composer install && php artisan migrate
    echo "PROVISION DONE."
  SHELL
end
