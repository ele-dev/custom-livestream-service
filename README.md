# custom-livestream-service
A docker-compose software stack for a self hosted live streaming service that allows RTMP ingestion, HLS live delivery, live video recording, clip uploads and more

# deployment 
This software stack requires docker and docker-compose to be installed on the target system.
It should be mentioned that it is designed to be deployed behind a Reverse Proxy that can
handle SSL Termination. So no certificate management or encryption at all is implemented here.

Build the application with:
```shell
docker-compose build 
```

Then run it in background with:
```shell
docker-compose up -d 
```

Stop it with:
```shell
docker-compose down
```

By default the ports are configured as follows
  - 1935: RTMP ingestion
  - 8080: web application 
  - 8081: direct playback of HLS video feed (e.g. with VLC media player)
  

