FROM php:8-fpm

# install and enable the mysqli module
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

# set the correct timezone
ENV TZ="Europe/Berlin"

# also configure php time
RUN printf '[PHP]\ndate.timezone = "Europe/Berlin"\n' > /usr/local/etc/php/conf.d/tzone.ini

# create folder for volume mount and grant permissions
RUN mkdir /mnt/uploads
RUN chmod a+wr /mnt/uploads

RUN mkdir /mnt/recordings
RUN chmod a+wr /mnt/recordings
