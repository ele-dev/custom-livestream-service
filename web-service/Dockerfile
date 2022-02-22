FROM debian:stable-slim
RUN apt-get update && \
	apt-get install -y apache2 php libapache2-mod-php php-mysql tzdata && \
	apt-get clean -y && \
	rm -r /var/cache/apt /var/lib/apt/lists/*
ENV TZ="Europe/Berlin"
EXPOSE 80
COPY webroot/ /var/www/html
RUN rm /var/www/html/index.html
CMD ["usr/sbin/apache2ctl", "-D", "FOREGROUND"]
