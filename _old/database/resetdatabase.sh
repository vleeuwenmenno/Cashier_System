#!/bin/bash

mysql -S/tmp/mysql.sock2 -u louis -pjohan1 < createcomtoday.sql
mysql -S/tmp/mysql.sock2 -u louis -pjohan1 < comtodaybackup2505.sql
