FROM php:8.1-fpm

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
			wget \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libxpm-dev \
            libmcrypt-dev \
            libonig-dev; \
    rm -rf /var/lib/apt/lists/*
	
	

RUN set -eux; \
    # Install the PHP pdo_mysql extention
    docker-php-ext-install pdo_mysql; \
    # Install the PHP pdo_pgsql extention
    docker-php-ext-install pdo_pgsql; \
    # Install the PHP gd library
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd; \
	
	# Install the PHP mongodb extention
	docker-php-ext-install mongodb; \
	
	# Install the PHP sodium extention
	docker-php-ext-install sodium; \
	
	# Install the PHP php_grpc extention
	docker-php-ext-install grpc; \
	
	# Install the PHP sqlsrv extention
	docker-php-ext-install sqlsrv; \
	
	# Install the PHP redis extention
	docker-php-ext-install redis; \
	
	
RUN cp /usr/src/php/php.ini-development /usr/local/etc/php/php.ini && \

    # downlod cert chain as without this curl over https returns an error
    wget http://curl.haxx.se/ca/cacert.pem --directory-prefix=/usr/local/etc && \
    wget http://www.symantec.com/content/en/us/enterprise/verisign/roots/Class-3-Public-Primary-Certification-Authority.pem --directory-prefix=/usr/local/etc/ && \
    cat /usr/local/etc/Class-3-Public-Primary-Certification-Authority.pem >> /usr/local/etc/php/cacert.pem && \
    rm /usr/local/etc/Class-3-Public-Primary-Certification-Authority.pem && \

    # global php sesstings - the strart up script sets the env settins
    sed -i 's/^.*curl.cainfo.*$/curl.cainfo =\/usr\/local\/etc\/php\/cacert.pem/' /usr/local/etc/php/php.ini && \
    sed -i 's/^.*short_open_tag =.*$/short_open_tag = On/' /usr/local/etc/php/php.ini && \
    sed -i 's/^.*always_populate_raw_post_data =.*$/always_populate_raw_post_data = -1/' /usr/local/etc/php/php.ini && \
    sed -i 's/^.*sendmail_path =.*$/sendmail_path = sendmail -t -i/' /usr/local/etc/php/php.ini && \

    if ! grep -lq "pm.status_path =" /usr/local/etc/php-fpm.conf  ; then  printf  "\npm.status_path = /status" >> /usr/local/etc/php-fpm.conf ; else sed -i -e "s/;\?pm.status_path =.*/pm.status_path = \/status/" /usr/local/etc/php-fpm.conf ;fi


FROM alpine:3.16

LABEL maintainer="NGINX Docker Maintainers <docker-maint@nginx.com>"

# Define NGINX versions for NGINX Plus and NGINX Plus modules
# Uncomment this block and the versioned nginxPackages in the main RUN
# instruction to install a specific release
# ENV NGINX_VERSION 27
# ENV NJS_VERSION   0.7.4
# ENV PKG_RELEASE   1

# Download certificate and key from the customer portal (https://account.f5.com)
# and copy to the build context
RUN --mount=type=secret,id=nginx-crt,dst=cert.pem \
    --mount=type=secret,id=nginx-key,dst=cert.key \
    set -x \
# Create nginx user/group first, to be consistent throughout Docker variants
    && addgroup -g 101 -S nginx \
    && adduser -S -D -H -u 101 -h /var/cache/nginx -s /sbin/nologin -G nginx -g nginx nginx \
# Install the latest release of NGINX Plus and/or NGINX Plus modules
# Uncomment individual modules if necessary
# Use versioned packages over defaults to specify a release
    && nginxPackages=" \
        nginx-plus \
        # nginx-plus=${NGINX_VERSION}-${PKG_RELEASE} \
        # nginx-plus-module-xslt \
        # nginx-plus-module-xslt=${NGINX_VERSION}-${PKG_RELEASE} \
        # nginx-plus-module-geoip \
        # nginx-plus-module-geoip=${NGINX_VERSION}-${PKG_RELEASE} \
        # nginx-plus-module-image-filter \
        # nginx-plus-module-image-filter=${NGINX_VERSION}-${PKG_RELEASE} \
        # nginx-plus-module-perl \
        # nginx-plus-module-perl=${NGINX_VERSION}-${PKG_RELEASE} \
        # nginx-plus-module-njs \
        # nginx-plus-module-njs=${NGINX_VERSION}.${NJS_VERSION}-${PKG_RELEASE} \
    " \
    KEY_SHA512="e7fa8303923d9b95db37a77ad46c68fd4755ff935d0a534d26eba83de193c76166c68bfe7f65471bf8881004ef4aa6df3e34689c305662750c0172fca5d8552a *stdin" \
    && apk add --no-cache --virtual .cert-deps openssl \
    && wget -O /tmp/nginx_signing.rsa.pub https://nginx.org/keys/nginx_signing.rsa.pub \
    && if [ "$(openssl rsa -pubin -in /tmp/nginx_signing.rsa.pub -text -noout | openssl sha512 -r)" = "$KEY_SHA512" ]; then \
        echo "key verification succeeded!"; \
        mv /tmp/nginx_signing.rsa.pub /etc/apk/keys/; \
    else \
        echo "key verification failed!"; \
        exit 1; \
    fi \
    && apk del .cert-deps \
    && cat cert.pem > /etc/apk/cert.pem \
    && cat cert.key > /etc/apk/cert.key \
    && apk add -X "https://pkgs.nginx.com/plus/alpine/v$(egrep -o '^[0-9]+\.[0-9]+' /etc/alpine-release)/main" --no-cache $nginxPackages \
    && if [ -n "/etc/apk/keys/nginx_signing.rsa.pub" ]; then rm -f /etc/apk/keys/nginx_signing.rsa.pub; fi \
    && if [ -n "/etc/apk/cert.key" && -n "/etc/apk/cert.pem"]; then rm -f /etc/apk/cert.key /etc/apk/cert.pem; fi \
# Bring in gettext so we can get `envsubst`, then throw
# the rest away. To do this, we need to install `gettext`
# then move `envsubst` out of the way so `gettext` can
# be deleted completely, then move `envsubst` back.
    && apk add --no-cache --virtual .gettext gettext \
    && mv /usr/bin/envsubst /tmp/ \
    \
    && runDeps="$( \
        scanelf --needed --nobanner /tmp/envsubst \
            | awk '{ gsub(/,/, "\nso:", $2); print "so:" $2 }' \
            | sort -u \
            | xargs -r apk info --installed \
            | sort -u \
    )" \
    && apk add --no-cache $runDeps \
    && apk del .gettext \
    && mv /tmp/envsubst /usr/local/bin/ \
# Bring in tzdata so users could set the timezones through the environment
# variables
    && apk add --no-cache tzdata \
# Bring in curl and ca-certificates to make registering on DNS SD easier
    && apk add --no-cache curl ca-certificates \
# Forward request and error logs to Docker log collector
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80

STOPSIGNAL SIGQUIT

CMD ["nginx", "-g", "daemon off;"]

# vim:syntax=Dockerfile