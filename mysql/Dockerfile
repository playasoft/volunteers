FROM mysql:5.7.22

ARG USERNAME=homestead
ARG SECRET=secret

# echo GRANT ALL FOO BAR $USERNAME $SECRET and such into >/path/to/init.sql
RUN echo "GRANT ALL ON laravel.* TO \'$USERNAME\'@\'%\' IDENTIFIED BY \'$SECRET\';" > /docker-entrypoint-initdb.d/init.sql
