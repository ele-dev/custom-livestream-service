FROM mariadb

# install tzdata
RUN apt-get update && \
	apt-get install -y tzdata && \
	apt-get clean -y && \
	rm -r /var/cache/apt /var/lib/apt/lists/*

# set the correct timezone env variable
ENV TZ="Europe/Berlin"
