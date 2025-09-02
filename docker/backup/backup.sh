#!/bin/bash

# Database backup script for production
# This script creates daily backups of the MySQL database

# Configuration
DB_HOST="db"
DB_USER="root"
DB_PASS="${DB_ROOT_PASSWORD}"
DB_NAME="${DB_DATABASE}"
BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=${BACKUP_RETENTION_DAYS:-30}

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Create database backup
echo "Starting database backup at $(date)"
mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    --hex-blob \
    --quick \
    --lock-tables=false \
    $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Compress the backup
gzip $BACKUP_DIR/db_backup_$DATE.sql

# Upload to S3 if configured
if [ ! -z "$BACKUP_S3_BUCKET" ]; then
    echo "Uploading backup to S3..."
    aws s3 cp $BACKUP_DIR/db_backup_$DATE.sql.gz \
        s3://$BACKUP_S3_BUCKET/database-backups/ \
        --region $BACKUP_S3_REGION
fi

# Clean up old backups
echo "Cleaning up backups older than $RETENTION_DAYS days"
find $BACKUP_DIR -name "db_backup_*.sql.gz" -type f -mtime +$RETENTION_DAYS -delete

# Clean up old S3 backups
if [ ! -z "$BACKUP_S3_BUCKET" ]; then
    aws s3 ls s3://$BACKUP_S3_BUCKET/database-backups/ \
        --region $BACKUP_S3_REGION | \
    while read -r line; do
        createDate=$(echo $line | awk '{print $1" "$2}')
        createDate=$(date -d"$createDate" +%s)
        olderThan=$(date -d"$RETENTION_DAYS days ago" +%s)
        if [[ $createDate -lt $olderThan ]]; then
            fileName=$(echo $line | awk '{print $4}')
            if [[ $fileName != "" ]]; then
                aws s3 rm s3://$BACKUP_S3_BUCKET/database-backups/$fileName \
                    --region $BACKUP_S3_REGION
            fi
        fi
    done
fi

echo "Backup completed at $(date)"
