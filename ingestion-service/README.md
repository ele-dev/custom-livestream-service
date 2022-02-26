# RTMP-HLS-Server Docker

**Docker image for video streaming server that supports RTMP and HLS and streams.**

## Description

This Docker image can be used to create a video streaming server that supports [**RTMP**](https://en.wikipedia.org/wiki/Real-Time_Messaging_Protocol) Ingest and [**HLS**](https://en.wikipedia.org/wiki/HTTP_Live_Streaming) delivery out of the box.
It also allows live recording and transmuxing to mp4 format of video streams.
All modules are built from source on Debian and Alpine Linux base images.

## Features

- The backend is [**Nginx**](http://nginx.org/en/) with [**nginx-rtmp-module**](https://github.com/arut/nginx-rtmp-module).
- [**FFmpeg**](https://www.ffmpeg.org/) for live recording and transmuxing
- Default settings:
  - RTMP is ON
  - HLS is ON
- Statistic page of RTMP streams at `http://<server ip>:<server port>/stats`.

Current Image is built using:

- Nginx 1.21.3 (compiled from source)
- Nginx-rtmp-module 1.2.2 (compiled from source)
- FFmpeg 4.4 (compiled from source)

This image was inspired by similar docker images from [tiangolo](https://hub.docker.com/r/tiangolo/nginx-rtmp/) and [alfg](https://hub.docker.com/r/alfg/nginx-rtmp/). It has small build size, adds support for HTTP-based streams and adaptive streaming using FFmpeg.

## Usage

### To run the server

```shell
docker run -d -p 1935:1935 -p 8082:8082 -e PUID=$UID -e PGID=0 eledev/stream-ingest:latest
```

For more examples, see the [Wiki](https://github.com/JamiePhonic/rtmps-hls-server/wiki/usage)

***

### To stream to the server

- **Stream live RTMP content to:**

 `rtmp://<server ip>:1935/live/<stream_key>`

  where `<stream_key>` is any stream key you specify.

- **Configure [OBS](https://obsproject.com/) to stream content:** <br />
  Go to Settings > Stream, choose the following settings:
  - Service: Custom Streaming Server.
  - Server: `rtmp://<server ip>:1935/live`.
  - StreamKey: anything (but test is the default)

***

### To view the stream

- **Using [VLC](https://www.videolan.org/vlc/index.html):**

  - Go to Media > Open Network Stream.
  - Enter the streaming URL: `rtmp://<server ip>:1935/live/<stream-key>`
    Replace `<server ip>` with the IP of where the server is running, and
    `<stream-key>` with the stream key you used when setting up the stream.
  - For HLS the URLs are of the forms:
    `http://<server ip>:8082/hls/<stream-key>.m3u8`
  - Click Play.

## Copyright

Released under MIT license.

## More info

- **Docker Hub image**: https://hub.docker.com/repository/docker/eledev/stream-ingest
