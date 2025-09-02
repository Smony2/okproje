# Production Deployment Guide - OK Proje

Bu rehber, OK Proje uygulamasını production ortamında Docker ile deploy etmek için hazırlanmıştır.

## 🚀 Production Özellikleri

### Güvenlik
- ✅ SSL/TLS şifreleme (HTTPS)
- ✅ Güvenlik başlıkları (HSTS, CSP, XSS Protection)
- ✅ Rate limiting (API ve login koruması)
- ✅ Dosya erişim kısıtlamaları
- ✅ PHP güvenlik ayarları
- ✅ Redis şifreleme
- ✅ MySQL güvenli konfigürasyon

### Performans
- ✅ PHP OPcache optimizasyonu
- ✅ Nginx gzip/brotli sıkıştırma
- ✅ Static asset caching (1 yıl)
- ✅ Redis cache katmanı
- ✅ MySQL query cache
- ✅ Connection pooling

### Monitoring & Backup
- ✅ Otomatik veritabanı yedekleme
- ✅ Log yönetimi
- ✅ Health check endpoints
- ✅ Resource limits
- ✅ Prometheus/Grafana monitoring (opsiyonel)

## 📁 Dizin Yapısı

Production ortamında veriler bir üst dizinde saklanır:

```
/path/to/parent/
├── okproje/                    # Proje dizini
│   ├── docker-compose.prod.yml
│   ├── .env
│   ├── ssl/                    # SSL sertifikaları
│   └── ...
├── mysql_data/                 # MySQL veritabanı dosyaları
├── redis_data/                 # Redis veri dosyaları
├── nginx_cache/                # Nginx cache dosyaları
├── nginx_logs/                 # Nginx log dosyaları
├── prometheus_data/            # Prometheus verileri
├── grafana_data/               # Grafana verileri
└── backups/                    # Backup dosyaları
```

Bu yapı sayesinde:
- ✅ Proje güncellemelerinde veri kaybı olmaz
- ✅ Backup işlemleri kolaylaşır
- ✅ Veri yönetimi daha organize olur
- ✅ Disk kullanımı daha iyi kontrol edilir

## 📋 Ön Gereksinimler

### Sunucu Gereksinimleri
- **CPU**: Minimum 2 core, önerilen 4+ core
- **RAM**: Minimum 4GB, önerilen 8GB+
- **Disk**: Minimum 50GB SSD
- **OS**: Ubuntu 20.04+ / CentOS 8+ / Debian 11+

### Yazılım Gereksinimleri
- Docker 20.10+
- Docker Compose 2.0+
- Git
- SSL sertifikası (Let's Encrypt önerilen)

## 🔧 Kurulum Adımları

### 1. Sunucu Hazırlığı

```bash
# Sistem güncellemesi
sudo apt update && sudo apt upgrade -y

# Docker kurulumu
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Docker Compose kurulumu
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Git kurulumu
sudo apt install git -y
```

### 2. Proje Kurulumu

```bash
# Projeyi klonla
git clone <your-repository-url>
cd okproje

# Environment dosyasını kontrol et
# .env dosyası zaten mevcut, gerekirse düzenleyin

# Veri dizinlerini oluştur (bir üst dizinde)
mkdir -p ../mysql_data
mkdir -p ../redis_data
mkdir -p ../nginx_cache
mkdir -p ../nginx_logs
mkdir -p ../prometheus_data
mkdir -p ../grafana_data
mkdir -p ../backups

# SSL sertifikalarını hazırla
mkdir -p ssl
# Let's Encrypt ile sertifika al
sudo certbot certonly --standalone -d your-domain.com
sudo cp /etc/letsencrypt/live/your-domain.com/fullchain.pem ssl/cert.pem
sudo cp /etc/letsencrypt/live/your-domain.com/privkey.pem ssl/key.pem
sudo chown $USER:$USER ssl/*

# Dizin izinlerini ayarla
sudo chown -R $USER:$USER ../mysql_data
sudo chown -R $USER:$USER ../redis_data
sudo chown -R $USER:$USER ../nginx_cache
sudo chown -R $USER:$USER ../nginx_logs
sudo chown -R $USER:$USER ../prometheus_data
sudo chown -R $USER:$USER ../grafana_data
sudo chown -R $USER:$USER ../backups
```

### 3. Environment Konfigürasyonu

`.env` dosyasını düzenleyin:

```bash
# Domain ayarları
APP_URL=https://your-domain.com
SESSION_DOMAIN=your-domain.com
SANCTUM_STATEFUL_DOMAINS=your-domain.com,www.your-domain.com

# Güvenli şifreler oluştur
DB_PASSWORD=$(openssl rand -base64 32)
DB_ROOT_PASSWORD=$(openssl rand -base64 32)
REDIS_PASSWORD=$(openssl rand -base64 32)

# LiveKit ayarları
LIVEKIT_API_KEY=$(openssl rand -hex 16)
LIVEKIT_API_SECRET=$(openssl rand -hex 32)

# TURN server ayarları
TURN_USERNAME=$(openssl rand -hex 8)
TURN_PASSWORD=$(openssl rand -base64 32)
```

### 4. Production Deploy

```bash
# Production container'ları başlat
docker-compose -f docker-compose.prod.yml up -d --build

# Laravel kurulumu
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate --force
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force
docker-compose -f docker-compose.prod.yml exec app php artisan storage:link
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# Frontend asset'leri build et
docker-compose -f docker-compose.prod.yml exec node npm ci --production
docker-compose -f docker-compose.prod.yml exec node npm run build
```

## 🔒 SSL Sertifika Yönetimi

### Let's Encrypt ile Otomatik Yenileme

```bash
# Crontab'a ekle
echo "0 12 * * * /usr/bin/certbot renew --quiet && docker-compose -f /path/to/okproje/docker-compose.prod.yml restart nginx" | sudo crontab -
```

### Manuel Sertifika Güncelleme

```bash
# Sertifikayı yenile
sudo certbot renew

# Container'ı yeniden başlat
docker-compose -f docker-compose.prod.yml restart nginx
```

## 📊 Monitoring Kurulumu (Opsiyonel)

```bash
# Monitoring servislerini başlat
docker-compose -f docker-compose.prod.yml --profile monitoring up -d

# Grafana'ya erişim
# http://your-domain.com:3000
# Username: admin
# Password: .env dosyasındaki GRAFANA_PASSWORD
```

## 🔄 Backup Stratejisi

### Otomatik Backup

```bash
# Crontab'a backup job'ı ekle
echo "0 2 * * * docker-compose -f /path/to/okproje/docker-compose.prod.yml run --rm backup" | crontab -
```

### Manuel Backup

```bash
# Veritabanı backup'ı
docker-compose -f docker-compose.prod.yml run --rm backup

# Dosya backup'ı
tar -czf ../backup_$(date +%Y%m%d).tar.gz storage/ public/uploads/

# Tüm veri dizinlerini backup'la
tar -czf ../full_backup_$(date +%Y%m%d).tar.gz ../mysql_data ../redis_data ../nginx_logs
```

## 🚨 Güvenlik Kontrol Listesi

### Sunucu Güvenliği
- [ ] Firewall aktif (ufw/iptables)
- [ ] SSH key authentication
- [ ] Fail2ban kurulu
- [ ] Sistem güncellemeleri otomatik
- [ ] Root login devre dışı

### Uygulama Güvenliği
- [ ] SSL sertifikası aktif
- [ ] Güvenlik başlıkları kontrol edildi
- [ ] Rate limiting test edildi
- [ ] Dosya upload kısıtlamaları aktif
- [ ] Debug mode kapalı
- [ ] Error reporting kapalı

### Veritabanı Güvenliği
- [ ] Güçlü şifreler kullanıldı
- [ ] Remote access kısıtlandı
- [ ] Backup şifreleme aktif
- [ ] Log monitoring aktif

## 🔧 Maintenance Komutları

### Log Görüntüleme
```bash
# Tüm servislerin logları
docker-compose -f docker-compose.prod.yml logs -f

# Belirli servisin logları
docker-compose -f docker-compose.prod.yml logs -f app
docker-compose -f docker-compose.prod.yml logs -f nginx
```

### Cache Temizleme
```bash
# Laravel cache temizleme
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec app php artisan route:clear
docker-compose -f docker-compose.prod.yml exec app php artisan view:clear

# Redis cache temizleme
docker-compose -f docker-compose.prod.yml exec redis redis-cli FLUSHALL
```

### Container Yönetimi
```bash
# Container'ları yeniden başlat
docker-compose -f docker-compose.prod.yml restart

# Belirli servisi yeniden başlat
docker-compose -f docker-compose.prod.yml restart app

# Container'ları güncelle
docker-compose -f docker-compose.prod.yml pull
docker-compose -f docker-compose.prod.yml up -d --force-recreate
```

## 📈 Performans Optimizasyonu

### Nginx Optimizasyonu
- Gzip compression aktif
- Static asset caching (1 yıl)
- Connection keep-alive
- Worker process sayısı CPU core sayısına eşit

### PHP Optimizasyonu
- OPcache aktif ve optimize edilmiş
- Memory limit 512MB
- Max execution time 300s
- Realpath cache aktif

### MySQL Optimizasyonu
- InnoDB buffer pool 256MB
- Query cache aktif
- Connection pooling
- Slow query log aktif

### Redis Optimizasyonu
- Memory limit 256MB
- LRU eviction policy
- Persistence aktif (RDB + AOF)

## 🆘 Sorun Giderme

### Yaygın Sorunlar

**SSL Sertifika Hatası:**
```bash
# Sertifika dosyalarını kontrol et
ls -la ssl/
# Nginx konfigürasyonunu kontrol et
docker-compose -f docker-compose.prod.yml exec nginx nginx -t
```

**Veritabanı Bağlantı Hatası:**
```bash
# MySQL container'ını kontrol et
docker-compose -f docker-compose.prod.yml logs db
# Bağlantıyı test et
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
```

**Memory Hatası:**
```bash
# Memory kullanımını kontrol et
docker stats
# Container resource limitlerini artır
```

### Log Analizi
```bash
# Error logları
docker-compose -f docker-compose.prod.yml exec app tail -f /var/log/php_errors.log
docker-compose -f docker-compose.prod.yml exec nginx tail -f /var/log/nginx/error.log

# Access logları
docker-compose -f docker-compose.prod.yml exec nginx tail -f /var/log/nginx/access.log
```

## 📞 Destek

Production deployment ile ilgili sorunlar için:
1. Log dosyalarını kontrol edin
2. Container durumlarını kontrol edin: `docker-compose -f docker-compose.prod.yml ps`
3. Resource kullanımını kontrol edin: `docker stats`
4. Network bağlantılarını test edin

Bu rehber production ortamında güvenli ve performanslı bir deployment sağlar. Herhangi bir sorun yaşarsanız, log dosyalarını inceleyerek sorunu tespit edebilirsiniz.
