# custom-livestream-service
A docker-compose software stack for a self hosted live streaming service that allows RTMP ingestion, HLS live delivery, live video recording, clip uploads and more

# configuration
In order to get the software working properly you need to check the config.php file in the web-service/webroot/php directory.
There you must set the correct IP address or Domain of your machine before your run the application. <br />
For a simple test instance you might use the internal (e.g. 192.168.1.xxx) or even localhost (127.0.0.1) IP address. <br />
For a production system, a domain with an A Record to your public IP address should be entered here.

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
  

