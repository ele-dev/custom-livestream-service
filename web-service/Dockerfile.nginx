FROM nginx:latest

# Expose the webserver port
EXPOSE 80

# set the correct timezone
ENV TZ="Europe/Berlin"

# create folder for volume mount and grant permissions
RUN mkdir /mnt/uploads
RUN chmod a+wr /mnt/uploads

RUN mkdir /mnt/recordings
RUN chmod a+wr /mnt/recordings
