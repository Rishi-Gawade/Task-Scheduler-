#!/bin/bash

CRON_JOB="0 * * * * php $(pwd)/cron.php >> /dev/null 2>&1"

# Check if the cron job already exists
(crontab -l | grep -v -F "$CRON_JOB" ; echo "$CRON_JOB") | crontab -
