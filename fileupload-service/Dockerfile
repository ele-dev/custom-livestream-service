FROM debian:stable-slim

ARG USER=admin

RUN apt-get update && \
	apt-get install -y openssh-server tzdata && \
	apt-get clean -y && \
	rm -r /var/cache/apt /var/lib/apt/lists/*

ENV TZ="Europe/Berlin"

RUN useradd -rm -d /home/${USER} -s /bin/bash -u 1000 ${USER}

RUN echo 'admin:123456' | chpasswd

RUN service ssh start

RUN chmod a+wr /home/${USER}

EXPOSE 22
CMD ["/usr/sbin/sshd", "-D"]
