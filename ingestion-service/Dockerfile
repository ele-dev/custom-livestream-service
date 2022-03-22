ARG DEBIAN_VERSION=stretch-slim 

##### Building stage #####
FROM debian:${DEBIAN_VERSION} as builder
LABEL version = "1.5"
LABEL maintainer = "elias@ele-dev.de"

# Versions of nginx, rtmp-module and ffmpeg 
ARG  NGINX_VERSION=1.21.3
ARG  NGINX_RTMP_MODULE_VERSION=1.2.2
ARG  FFMPEG_VERSION=4.4

# Install dependencies
RUN apt-get update && \
	apt-get install -y \
		wget build-essential ca-certificates \
		openssl libssl-dev yasm \
		libpcre3-dev librtmp-dev libtheora-dev \
		libvorbis-dev libvpx-dev libfreetype6-dev \
		libmp3lame-dev libx264-dev libx265-dev && \
    rm -rf /var/lib/apt/lists/*
		
# Download nginx source
RUN mkdir -p /tmp/build && \
	cd /tmp/build && \
	wget https://nginx.org/download/nginx-${NGINX_VERSION}.tar.gz && \
	tar -zxf nginx-${NGINX_VERSION}.tar.gz && \
	rm nginx-${NGINX_VERSION}.tar.gz

# Download rtmp-module source
RUN cd /tmp/build && \
    wget https://github.com/arut/nginx-rtmp-module/archive/v${NGINX_RTMP_MODULE_VERSION}.tar.gz && \
    tar -zxf v${NGINX_RTMP_MODULE_VERSION}.tar.gz && \
	rm v${NGINX_RTMP_MODULE_VERSION}.tar.gz

# Build nginx with nginx-rtmp module
RUN cd /tmp/build/nginx-${NGINX_VERSION} && \
    ./configure \
        --sbin-path=/usr/local/sbin/nginx \
        --conf-path=/etc/nginx/nginx.conf \
        --error-log-path=/var/log/nginx/error.log \
        --http-log-path=/var/log/nginx/access.log \		
        --pid-path=/var/run/nginx/nginx.pid \
        --lock-path=/var/lock/nginx.lock \
        --http-client-body-temp-path=/tmp/nginx-client-body \
        --with-http_ssl_module \
		--with-stream \
		--with-stream_ssl_module \
		--with-http_v2_module \
        --with-threads \
        --add-module=/tmp/build/nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION} && \
    make -j $(getconf _NPROCESSORS_ONLN) && \
    make install

# Download ffmpeg source
RUN cd /tmp/build && \
  wget http://ffmpeg.org/releases/ffmpeg-${FFMPEG_VERSION}.tar.gz && \
  tar -zxf ffmpeg-${FFMPEG_VERSION}.tar.gz && \
  rm ffmpeg-${FFMPEG_VERSION}.tar.gz
  
# Build ffmpeg
RUN cd /tmp/build/ffmpeg-${FFMPEG_VERSION} && \
  ./configure \
	  --enable-version3 \
	  --enable-gpl \
	  --enable-small \
	  --enable-libx264 \
	  --enable-libx265 \
	  --enable-libvpx \
	  --enable-libtheora \
	  --enable-libvorbis \
	  --enable-librtmp \
	  --enable-postproc \
	  --enable-swresample \ 
	  --enable-libfreetype \
	  --enable-libmp3lame \
	  --disable-debug \
	  --disable-doc \
	  --disable-ffplay \
	  --extra-libs="-lpthread -lm" && \
	make -j $(getconf _NPROCESSORS_ONLN) && \
	make install
	
# Copy stats.xsl file to nginx html directory and cleaning build files
RUN cp /tmp/build/nginx-rtmp-module-${NGINX_RTMP_MODULE_VERSION}/stat.xsl /usr/local/nginx/html/stat.xsl && \
	rm -rf /tmp/build && \
	mkdir /var/run/stunnel4

##### Building the final image #####
FROM debian:${DEBIAN_VERSION}

# Install dependencies and create the assets folders for default players and configs to be copied to
RUN apt-get update && \
	apt-get install -y \
		ca-certificates openssl libpcre3-dev \
		librtmp1 libtheora0 libvorbis-dev libmp3lame0 \
		libvpx4 libx264-dev libx265-dev stunnel4 htop tzdata && \
    rm -rf /var/lib/apt/lists/* && \
	mkdir /assets-default && mkdir /assets-default/ssl && \
	mkdir /assets

# Copy files from build stage to final stage	
COPY --from=builder /usr/local /usr/local
COPY --from=builder /etc/nginx /etc/nginx
COPY --from=builder /var/log/nginx /var/log/nginx
COPY --from=builder /var/lock /var/lock
COPY --from=builder /var/run/nginx /var/run/nginx

# Forward logs to Docker
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

# Copy  nginx config file to container
COPY conf /assets-default/configs

# Copy run script to container
COPY run.sh /run.sh

# Set correct timezone
ENV TZ="Europe/Berlin"

ENV IMAGE=Debian
ENV PUID=0
ENV PGID=0
ENV SSL_DOMAIN=rtmp-server.loc
EXPOSE 1935
EXPOSE 8082
VOLUME /assets

RUN chmod a+wr /mnt

CMD ["bash", "/run.sh"]
