#!/bin/bash
echo "=== STARTING DEBUG ==="
echo "PORT from Render is: $PORT"
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
echo "=== APACHE CONFIG ==="
cat /etc/apache2/ports.conf
echo "=== STARTING APACHE ==="
apache2-foreground
