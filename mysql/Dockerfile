FROM mysql:5.7.22

ARG USERNAME
ARG SECRET

RUN echo "GRANT ALL ON $DATABASE.* TO \'$USERNAME\'@\'%\' IDENTIFIED BY \'$SECRET\';" > /docker-entrypoint-initdb.d/init.sql
RUN echo 'FLUSH PRIVILEGES;' >> /docker-entrypoint-initdb.d/init.sql
