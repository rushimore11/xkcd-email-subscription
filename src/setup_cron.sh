#!/bin/bash
# src/setup_cron.sh
# This script sets up a CRON job to run cron.php every 24 hours

# Get the current user's crontab
(crontab -l 2>/dev/null; echo "/usr/bin/php/C:/Users/rushi/Music/OneDrive/Desktop/xkcd-email-subscription/src/cron.php") | crontab -
echo "CRON job has been set up to run cron.php every 24 hours."