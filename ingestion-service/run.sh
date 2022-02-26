#!/bin/sh

function generate_certificate () {
    SUBJ="/CN=$SSL_DOMAIN"
    echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: The generated certificate will be valid for: $SSL_DOMAIN"
    openssl genrsa -out /assets/ssl/.self_signed/rtmp.key 2048
    openssl req -new -key /assets/ssl/.self_signed/rtmp.key -subj $SUBJ -out /tmp/rtmp.csr
    openssl x509 -req -in /tmp/rtmp.csr -CA /assets/ssl/.self_signed/RTMP-CA.crt -CAkey /assets/ssl/.self_signed/RTMP-CA.key \
    -CAcreateserial -days 365 -sha256 -out /assets/ssl/.self_signed/rtmp.crt
    echo -e ""
}

# Copy assets from /assets-default to /assets
# If /assets has been mounted from the host, this will automatically populate the host directory with the files
# Copy default players and configs if '/assets/.initialized' does not exist

if [ ! -f "/assets/.initialized" ]; then
	echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Copying default Assets to /assets/"
    # Copy default assets from /assets-default/ to /assets/
    # Hard link the hls player as index.html (making it the default player)
    if [ "$IMAGE" = "Alpine" ]; then
        cp -Rfv /assets-default/* /assets/ 2>/dev/null
    else
        cp -Rfv /assets-default/* /assets/ 2>/dev/null
    fi
    # Create an empty file called '.initialized' so we dont re-copy the assets again
	echo "Delete me and restart the container to restore default configs and players." >/assets/.initialized
fi

echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Creating symlinks to Configs and Players from /assets/ \\n"
# Link Nginx config from assets directory
ln -sf /assets/configs/nginx.conf /etc/nginx/nginx.conf

# Verify the SSL directory exists. if not, create it
if [ ! -d /assets/ssl/.self_signed ]; then
    mkdir -p /assets/ssl/.self_signed
fi

# Create a cert/key pair a Certificate Authroity cert if it doesn't already exist, 
# otherwise we won't be able to generate a self signed cert and Nginx won't start properly.
if [ ! -f "/assets/ssl/.self_signed/RTMP-CA.crt" ]; then
    echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Generating a Self Signing Certificate Authority..."
    openssl genrsa -out /assets/ssl/.self_signed/RTMP-CA.key 2048
    openssl req -x509 -new -nodes -key /assets/ssl/.self_signed/RTMP-CA.key -sha256 -days 1825 -subj '/CN=RTMP-Server-CA' -out /assets/ssl/.self_signed/RTMP-CA.crt
    cp -fv /assets/ssl/.self_signed/RTMP-CA.crt /assets/ssl/ &>/dev/null
	echo -e ""
fi

# Generate a cert/key pair if they don't already exist, otherwise Nginx won't start properly.
if [ ! -f "/assets/ssl/.self_signed/rtmp.crt" ]; then
    echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Couldn't find an existing SSL certificate in '/assets/ssl/.self_signed/'"
    echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Generating one for you so that Nginx can start properly..."
    generate_certificate
    echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Please update the Nginx confguration file to use vaild signed certificate for your domain"
    echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: or install 'ssl/RTMP-CA.crt' as a certificate authority on your machine to use the generated Self Signed certificate. \\n"
else # If a certificate DOES already exist, check if it's valid for the domain specified in SSL_DOMAIN. if not, re-generate it.
    SSL_DOMAIN_CURRENT=$(openssl x509 -noout -subject -in /assets/ssl/.self_signed/rtmp.crt | sed 's|subject=CN\ =\ ||' )
    if [ "$SSL_DOMAIN_CURRENT" != "$SSL_DOMAIN" ]; then
        echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Current certificate is not valid for: $SSL_DOMAIN"
        echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Re-Generating a new one that is..."
        generate_certificate
    fi
fi

# Recursivley CHOWN the /assets directory to $PUID:$PGID
echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Setting Owner:Group on /assets/ to: $PUID:$PGID \\n"
chown -R $PUID:$PGID /assets

sleep 2
# Start Nginx
echo -e "`date +"%Y-%m-%d %H:%M:%S"` INFO: Starting Nginx!"
exec nginx -g "daemon off;"
