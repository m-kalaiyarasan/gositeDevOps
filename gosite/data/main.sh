#! /bin/bash    

touch kalai

# git clone https://git.selfmade.ninja/kalaiyarasan.offl.2004/photogram001.git /var/www/html
    
# mv /var/photogram/photogramconfig.json /var/www

# to enable short tag
sed -i "s/short_open_tag = .*/short_open_tag = On/" /etc/php/8.3/apache2/php.ini
# sed -i "s/short_open_tag = .*/short_open_tag = On/" /etc/php/8.3/apache2/php.ini

#
sed -i "s|DocumentRoot .*|DocumentRoot /var/www/html/htdocs|" /etc/apache2/sites-available/000-default.conf

# to increse the upload size
sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 100M/' /etc/php/8.3/apache2/php.ini
sed -i 's/^post_max_size = .*/post_max_size = 100M/' /etc/php/8.3/apache2/php.ini



/usr/sbin/apache2ctl -D FOREGROUND