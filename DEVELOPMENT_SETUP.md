# Development Setup - OK Proje

Bu rehber, OK Proje uygulamasını development ortamında Docker ile çalıştırmak için hazırlanmıştır.

## 🚀 Hızlı Başlangıç

### 1. Projeyi Klonlayın
```bash
git clone <your-repository-url>
cd okproje
```

### 2. Environment Dosyasını Oluşturun
```bash
cp .env.example .env
```

### 3. Docker Container'ları Başlatın
```bash
docker-compose up -d --build
```

### 4. Laravel Kurulumu
```bash
# Composer bağımlılıklarını yükle
docker-compose exec app composer install

# Application key oluştur
docker-compose exec app php artisan key:generate

# Veritabanı migration'larını çalıştır
docker-compose exec app php artisan migrate

# Veritabanı seed'lerini çalıştır
docker-compose exec app php artisan db:seed

# Storage link oluştur
docker-compose exec app php artisan storage:link

# Frontend asset'leri build et
docker-compose exec node npm install
docker-compose exec node npm run build
```

## 🌐 Erişim Adresleri

- **Ana Uygulama**: http://localhost
- **Mailhog (Email Test)**: http://localhost:8025
- **LiveKit**: ws://localhost:7880
- **TURN Server**: turn:localhost:3478

## 🔧 Development Komutları

### Laravel Komutları
```bash
# Artisan komutları
docker-compose exec app php artisan <command>

# Composer komutları
docker-compose exec app composer <command>

# Cache temizleme
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Veritabanı İşlemleri
```bash
# Migration çalıştırma
docker-compose exec app php artisan migrate

# Migration geri alma
docker-compose exec app php artisan migrate:rollback

# Fresh migration + seed
docker-compose exec app php artisan migrate:fresh --seed

# MySQL'e bağlan
docker-compose exec db mysql -u okproje -p okproje
```

### Frontend Development
```bash
# NPM komutları
docker-compose exec node npm <command>

# Development server başlat
docker-compose exec node npm run dev

# Production build
docker-compose exec node npm run build
```

### Log Görüntüleme
```bash
# Tüm servislerin logları
docker-compose logs -f

# Belirli servisin logları
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f db
```

## 🛠️ Development Araçları

### Mailhog
Email testleri için Mailhog kullanılır:
- **Web UI**: http://localhost:8025
- **SMTP**: localhost:1025

### LiveKit
Video/audio özellikleri için:
- **WebSocket**: ws://localhost:7880
- **API Key**: devkey
- **API Secret**: devsecret

### TURN Server
WebRTC için TURN server:
- **UDP/TCP**: localhost:3478
- **Username**: turnuser
- **Password**: turnpass

## 🔄 Container Yönetimi

### Container'ları Başlatma/Durdurma
```bash
# Tüm servisleri başlat
docker-compose up -d

# Tüm servisleri durdur
docker-compose down

# Tüm servisleri durdur ve volume'ları sil
docker-compose down -v
```

### Container'a Bağlanma
```bash
# PHP container'ına bağlan
docker-compose exec app bash

# MySQL container'ına bağlan
docker-compose exec db mysql -u okproje -p okproje

# Redis container'ına bağlan
docker-compose exec redis redis-cli

# Node container'ına bağlan
docker-compose exec node sh
```

## 🐛 Sorun Giderme

### Port Çakışması
Eğer portlar kullanımda ise, `docker-compose.yml` dosyasındaki port numaralarını değiştirin.

### Permission Hatası
```bash
# Storage klasörü izinlerini düzelt
docker-compose exec app chown -R www:www /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

### Container Yeniden Build
```bash
# Tüm container'ları yeniden build et
docker-compose down
docker-compose up -d --build --force-recreate
```

### Cache Temizleme
```bash
# Tüm cache'leri temizle
docker-compose exec app php artisan optimize:clear
```

## 📁 Proje Yapısı

```
okproje/
├── app/                    # Laravel uygulama dosyaları
├── config/                 # Konfigürasyon dosyaları
├── database/               # Migration ve seed dosyaları
├── docker/                 # Docker konfigürasyonları
│   ├── nginx/             # Nginx konfigürasyonu
│   ├── php/               # PHP konfigürasyonu
│   └── mysql/             # MySQL konfigürasyonu
├── public/                 # Web erişilebilir dosyalar
├── resources/              # View, CSS, JS dosyaları
├── routes/                 # Route tanımları
├── storage/                # Log ve cache dosyaları
├── .env                    # Environment değişkenleri
├── .env.example            # Environment template
├── docker-compose.yml      # Development Docker Compose
├── docker-compose.prod.yml # Production Docker Compose
└── composer.json           # PHP bağımlılıkları
```

## 🔧 Environment Değişkenleri

`.env` dosyasında aşağıdaki değişkenleri ayarlayabilirsiniz:

### Veritabanı
- `DB_DATABASE`: Veritabanı adı (varsayılan: okproje)
- `DB_USERNAME`: Veritabanı kullanıcı adı (varsayılan: okproje)
- `DB_PASSWORD`: Veritabanı şifresi (varsayılan: password)

### LiveKit
- `LIVEKIT_API_KEY`: LiveKit API anahtarı (varsayılan: devkey)
- `LIVEKIT_API_SECRET`: LiveKit API secret'ı (varsayılan: devsecret)

### TURN Server
- `TURN_USERNAME`: TURN server kullanıcı adı (varsayılan: turnuser)
- `TURN_PASSWORD`: TURN server şifresi (varsayılan: turnpass)

## 🚀 Production'a Geçiş

Development'tan production'a geçmek için:

1. `.env` dosyasını production ayarlarıyla güncelleyin
2. `docker-compose.prod.yml` kullanın
3. SSL sertifikalarını hazırlayın
4. Production deployment rehberini takip edin

```bash
# Production'ı başlat
docker-compose -f docker-compose.prod.yml up -d --build
```

## 📞 Destek

Development ile ilgili sorunlar için:
1. Log dosyalarını kontrol edin: `docker-compose logs`
2. Container durumlarını kontrol edin: `docker-compose ps`
3. Environment değişkenlerini kontrol edin: `.env` dosyası
4. Port çakışmalarını kontrol edin: `netstat -tulpn | grep :80`

Bu rehber development ortamında hızlı ve kolay bir kurulum sağlar. Herhangi bir sorun yaşarsanız, log dosyalarını inceleyerek sorunu tespit edebilirsiniz.
