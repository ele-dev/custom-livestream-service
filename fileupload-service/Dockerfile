FROM debian:stable-slim

RUN apt-get update && \
	apt-get install -y openssh-server tzdata && \
	apt-get clean -y && \
	rm -r /var/cache/apt /var/lib/apt/lists/*

ENV TZ="Europe/Berlin"

RUN useradd -rm -d /home/clip-manager -s /bin/bash -u 1000 clip-manager

RUN echo 'clip-manager:123456' | chpasswd

RUN service ssh start

RUN chmod a+wr /home/clip-manager

EXPOSE 22
CMD ["/usr/sbin/sshd", "-D"]
