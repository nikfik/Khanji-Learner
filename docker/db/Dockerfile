FROM postgres

ENV POSTGRES_PASSWORD docker
ENV POSTGRES_DB db
ENV POSTGRES_USER docker

# Kopiuj skrypt inicjalizacyjny - PostgreSQL automatycznie wykona pliki *.sql z tego katalogu
COPY docker/db/init.sql /docker-entrypoint-initdb.d/