# custom-livestream-service
A docker-compose software stack for a self hosted live streaming service that allows RTMP ingestion, HLS live delivery, live video recording, clip uploads and more

# configuration
The login credentials for the remote SFTP file access must be set in the Dockerfile inside the file-access-service directory before first deploy.
All other global configuration variables are stored in the database and should be configured in the admin web panel after the first deploy
config variables:
  - HLS URL (correct access url to the hls segments for live playback)
  - admin login password (default is 123456)

# deployment 
This software stack requires docker and docker-compose to be installed on the target system.
It should be mentioned that it is designed to be deployed behind a Reverse Proxy that can
handle SSL Termination. So no certificate management or encryption at all is implemented here.
Also make sure that the service ports (see below) aren't used by other applications or being blocked by a firewall

Create docker network (relevant for deployment with reverse proxy)
```shell
docker network create dmz_net
```

Build the application with:
```shell
docker-compose build 
```

Then run it in background with (run reverse proxy after this application if used):
```shell
docker-compose up -d 
```

Stop it with:
```shell
docker-compose down
```

By default the service ports are configured as follows
  - 1935: RTMP video ingestion (H264 video + ACC audio)
  - 8080: Front End Web application 
  - 8082 (8443 for https behind reverse proxy): HLS video segments and Video on Demand delivery
  - 2221: SFTP File access to upload recorded MP4 videos
  
