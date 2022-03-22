FROM debian:stable-slim

# install basic webserver with PHP and mysql module
RUN apt-get update && \
	apt-get install -y apache2 php libapache2-mod-php php-mysql tzdata && \
	apt-get clean -y && \
	rm -r /var/cache/apt /var/lib/apt/lists/*

# set the correct timezone
ENV TZ="Europe/Berlin"

# Expose the webserver port
EXPOSE 80

# Copy the webroot into the image and delete default examle page
COPY webroot/ /var/www/html
RUN rm /var/www/html/index.html

# create folder for volume mount and grant permissions
RUN mkdir /mnt/uploads
RUN chmod a+wr /mnt/uploads

RUN mkdir /mnt/recordings
RUN chmod a+wr /mnt/recordings

CMD ["usr/sbin/apache2ctl", "-D", "FOREGROUND"]
