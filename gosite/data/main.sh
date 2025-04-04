#! /bin/bash    

touch kalai

# git clone https://git.selfmade.ninja/kalaiyarasan.offl.2004/photogram001.git /var/www/html
    
# mv /var/photogram/photogramconfig.json /var/www

# to enable short tag
sed -i "s/short_open_tag = .*/short_open_tag = On/" /etc/php/8.3/apache2/php.ini
# sed -i "s/short_open_tag = .*/short_open_tag = On/" /etc/php/8.3/apache2/php.ini



# cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/gosite.conf
# sed -i "s|DocumentRoot .*|DocumentRoot /var/www/html/htdocs|" /etc/apache2/sites-available/gosite.conf
# sed -i "s|#ServerName .*|ServerName gosite.in|" /etc/apache2/sites-available/gosite.conf

a2ensite gosite.conf
a2ensite 000-default.conf
# to increse the upload size
sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 100M/' /etc/php/8.3/apache2/php.ini
sed -i 's/^post_max_size = .*/post_max_size = 100M/' /etc/php/8.3/apache2/php.ini
chown www-data:www-data /etc/apache2/sites-available
chown www-data:www-data /etc/apache2/sites-enabled/
chmod 775 /etc/apache2/sites-available/
dos2unix /var/www/html/htdocs/scripts/enableSite.sh
chown -R www-data:www-data /var/www/html/site
chown -R www-data:www-data /var/lib/apache2/site/enabled_by_admin

echo -e "www-data ALL=(ALL) NOPASSWD: /usr/sbin/a2ensite, /usr/sbin/service apache2 reload\nwww-data ALL=(ALL) NOPASSWD: /usr/sbin/a2ensite, /usr/sbin/a2dissite, /usr/sbin/service apache2 reload" | sudo tee -a /etc/sudoers > /dev/null

#for ssl

# mkdir -p /var/log/letsencrypt
# chown -R www-data:www-data /var/log/letsencrypt
# chmod -R 755 /var/log/letsencrypt

# mkdir -p /etc/letsencrypt
chown -R www-data:www-data /var/www/html/ApacheConfig/letsencrypt
chmod -R 755 /var/www/html/ApacheConfig/letsencrypt

sudo usermod -aG docker www-data
sudo chown root:docker /var/run/docker.sock
sudo chmod 660 /var/run/docker.sock




# service apache2 reload


/usr/sbin/apache2ctl -D FOREGROUND
