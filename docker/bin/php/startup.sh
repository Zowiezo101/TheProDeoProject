#!/bin/sh
# exec "$@"

# Get the encrypted version of a password
BLOG_PASSHASH=$(php -r 'echo password_hash($_ENV["BLOG_PASSWORD"], PASSWORD_DEFAULT);') 

# Insert the blog user into the database
mysql --host="$MYSQL_HOST" --port=3306 --user="$MYSQL_USER" --password="$MYSQL_PASSWORD" --database=bible --execute="DELETE FROM users WHERE name='$BLOG_USER'; INSERT INTO users (name, hash) VALUES ('$BLOG_USER', '$BLOG_PASSHASH');"

# Put the apache server in the foreground
apache2-foreground