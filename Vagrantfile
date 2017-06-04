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
    yum groupinstall 'Development Tools'
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
    ln -sf /usr/bin/php71 /bin/php
    echo "Install MariaDB and phpMyAdmin"
    yum install -y mariadb-server mariadb
    yum install -y phpmyadmin
    ln -s /usr/share/phpMyAdmin /usr/share/nginx/html
    echo "Start services"
    systemctl enable php71-php-fpm
    systemctl enable nginx
    systemctl enable mariadb
    systemctl start php71-php-fpm
    systemctl start nginx
    systemctl start mariadb
    if ! type -fp mysql; then
        mysqladmin -u root password vagrant
    fi
    echo "Install Composer"
    if ! /usr/local/bin/composer &>/dev/null; then
        curl -sS https://getcomposer.org/installer | php
        mv composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
    fi
    echo "PROVISION DONE."
  SHELL
end
