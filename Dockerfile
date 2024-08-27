FROM rintoexsinaga/yii2-minimal

#Change timezone

ENV TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#Change Execution time

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN sed -i -e "s/^ *memory_limit.*/memory_limit = 4G/g" -e "s/^ *max_execution_time.*/max_execution_time = 300/g" /usr/local/etc/php/php.ini

RUN sed -i -e "s/^ *memory_limit.*/memory_limit = 4G/g" -e "s/^ *max_execution_time.*/max_execution_time = 300/g" /usr/local/etc/php/conf.d/base.ini
RUN sed -i -e "s/^ *upload_max_filesize.*/upload_max_filesize = 4096M/g" -e "s/^ *post_max_size.*/post_max_size = 4096M/g" /usr/local/etc/php/conf.d/base.ini

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/web|g' /etc/apache2/sites-available/000-default.conf