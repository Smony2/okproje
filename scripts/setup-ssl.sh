#!/bin/bash

# SSL Setup Script for LiveKit and TURN Server
# This script sets up Let's Encrypt SSL certificates

set -e

echo "🔐 Setting up SSL certificates for call.katibim.xyz (direct IP)..."

# Check if domain is accessible
echo "📡 Checking domain accessibility..."
if ! curl -s -o /dev/null -w "%{http_code}" http://call.katibim.xyz | grep -q "200\|301\|302"; then
    echo "❌ Domain call.katibim.xyz is not accessible. Please ensure:"
    echo "   1. DNS is pointing to this server (not Cloudflare proxy)"
    echo "   2. Port 80 is open"
    echo "   3. Firewall allows HTTP traffic"
    exit 1
fi

echo "✅ Domain is accessible"

# Start services without SSL first
echo "🚀 Starting services without SSL..."
docker compose  up -d nginx

# Wait for nginx to be ready
echo "⏳ Waiting for nginx to be ready..."
sleep 10

# Request SSL certificate
echo "📜 Requesting SSL certificate from Let's Encrypt..."
docker compose  run --rm certbot

# Check if certificate was created
if [ ! -d "certbot_certs/live/call.katibim.xyz" ]; then
    echo "❌ SSL certificate creation failed"
    echo "Please check the logs: docker compose  logs certbot"
    exit 1
fi

echo "✅ SSL certificate created successfully"

# Restart services with SSL
echo "🔄 Restarting services with SSL..."
docker compose  down
docker compose  up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 15

# Test SSL for call.katibim.xyz
echo "🧪 Testing SSL configuration..."
if curl -s -o /dev/null -w "%{http_code}" https://call.katibim.xyz | grep -q "200"; then
    echo "✅ SSL is working correctly for call.katibim.xyz"
else
    echo "❌ SSL test failed for call.katibim.xyz"
    echo "Please check nginx logs: docker compose  logs nginx"
    exit 1
fi

# Test LiveKit WebSocket
echo "🧪 Testing LiveKit WebSocket..."
if curl -s -o /dev/null -w "%{http_code}" https://call.katibim.xyz/rtc | grep -q "200\|101"; then
    echo "✅ LiveKit WebSocket proxy is working"
else
    echo "⚠️  LiveKit WebSocket test inconclusive (this is normal)"
fi

echo ""
echo "🎉 SSL setup completed successfully!"
echo ""
echo "📋 Next steps:"
echo "   1. Update your .env file with:"
echo "      LIVEKIT_WS_URL=wss://call.katibim.xyz/rtc"
echo "      TURN_URL=turn:call.katibim.xyz:3478"
echo "      TURN_TLS_URL=turns:call.katibim.xyz:5349"
echo ""
echo "   2. Configure Cloudflare:"
echo "      - katibim.xyz: Cloudflare proxy (orange cloud)"
echo "      - call.katibim.xyz: Direct IP (gray cloud)"
echo ""
echo "   3. Set up automatic certificate renewal:"
echo "      Add this to your crontab:"
echo "      0 12 * * * cd $(pwd) && docker compose  run --rm certbot renew && docker compose  restart nginx"
echo ""
echo "   4. Test your WebRTC calls in production!"
