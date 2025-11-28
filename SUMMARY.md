# Monica Gateway Monitor - Complete Setup

## ✅ What's Been Created

### 1. Python API Monitor (Asterisk Server)
**Location:** `/tmp/asterisk_monitor/`

**Files:**
- `monitor_production.py` - Main API server
- `config_production.json` - Configuration
- `display_config.json` - Display settings
- `API_DOCUMENTATION.md` - Complete API docs
- `DEPLOYMENT_GUIDE.md` - Production deployment

**Running on:** `http://192.175.23.137:5050`

**Features:**
- ✅ Real-time AMI monitoring
- ✅ System resources (CPU, RAM, Disk)
- ✅ REST API for all operations
- ✅ Channel variables
- ✅ Hangup/Join actions

---

### 2. PHP Dashboard (Web Server)
**Location:** `/var/www/html/monica_gateway_monitor/`

**Files:**
- `index.php` - Main dashboard
- `api.php` - API proxy
- `config.php` - Server configuration
- `README.md` - Documentation

**Access:** `http://192.175.23.137/monica_gateway_monitor/`

**Features:**
- ✅ Multi-server dropdown selector
- ✅ System resources display
- ✅ Active channels table
- ✅ Channel variables modal
- ✅ Hangup/Join buttons
- ✅ Auto-refresh
- ✅ Headless API design

---

## How It Works

```
┌─────────────────┐
│  PHP Dashboard  │  (Web Server)
│  Port 80/443    │
└────────┬────────┘
         │ HTTP API Calls
         ▼
┌─────────────────┐
│ Python Monitor  │  (Asterisk Server or App Server)
│   Port 5050     │
└────────┬────────┘
         │ AMI Connection
         ▼
┌─────────────────┐
│ Asterisk Server │
│   Port 8038     │
└─────────────────┘
```

---

## Quick Start

### On Asterisk Server (or App Server)

```bash
cd /tmp/asterisk_monitor
./venv/bin/python3 monitor_production.py
```

### On Web Browser

Open: `http://192.175.23.137/monica_gateway_monitor/`

1. Select server from dropdown
2. Click "Load Server"
3. View real-time monitoring

---

## Configuration

### Add More Servers

Edit `/var/www/html/monica_gateway_monitor/config.php`:

```php
$servers = [
    [
        'id' => 'server1',
        'name' => 'Production Server 1',
        'api_url' => 'http://server1-ip:5050'
    ],
    [
        'id' => 'server2',
        'name' => 'Production Server 2',
        'api_url' => 'http://server2-ip:5050'
    ]
];
```

### Customize Display

Edit `/tmp/asterisk_monitor/config_production.json`:

```json
{
  "app": {
    "title": "Your Title",
    "branding": "Monica AI",
    "refresh_interval": 1000
  },
  "channels": {
    "filter_contexts": ["SAAS_Dial_Out_Call_To"]
  },
  "variables": {
    "show_only": [
      "BRANCH_ID",
      "CAMPAIGN_ID",
      "TTS_MESSAGE"
    ]
  }
}
```

---

## API Endpoints

All available at: `http://server:5050/api/`

1. **GET /api/system** - CPU, RAM, Disk
2. **GET /api/status** - Channels & Endpoints
3. **GET /api/channel_vars?channel=<name>** - Variables
4. **POST /api/action** - Hangup/Join

See `API_DOCUMENTATION.md` for complete details and PHP examples.

---

## Production Deployment

### 1. Move Python Monitor to Permanent Location

```bash
sudo cp -r /tmp/asterisk_monitor /opt/asterisk_monitor
```

### 2. Create Systemd Service

```bash
sudo nano /etc/systemd/system/asterisk-monitor.service
```

```ini
[Unit]
Description=Asterisk Monitor API
After=network.target

[Service]
Type=simple
User=root
WorkingDirectory=/opt/asterisk_monitor
ExecStart=/opt/asterisk_monitor/venv/bin/python3 monitor_production.py
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable asterisk-monitor
sudo systemctl start asterisk-monitor
```

### 3. Secure the Setup

- Use HTTPS for web dashboard
- Restrict API port 5050 to your web server IP only
- Use VPN or private network
- Add authentication to API

---

## Integration with Your CodeIgniter App

### Option 1: Copy Files
```bash
cp -r /var/www/html/monica_gateway_monitor /path/to/your/app/modules/
```

### Option 2: Use APIs Directly

See `API_DOCUMENTATION.md` for PHP code examples to integrate into your existing application.

---

## Testing

### Test Python API
```bash
curl http://192.175.23.137:5050/api/system
curl http://192.175.23.137:5050/api/status
```

### Test PHP Dashboard
```bash
curl http://192.175.23.137/monica_gateway_monitor/
```

---

## Support

- Python Monitor: `/tmp/asterisk_monitor/`
- PHP Dashboard: `/var/www/html/monica_gateway_monitor/`
- API Docs: `API_DOCUMENTATION.md`
- Deployment: `DEPLOYMENT_GUIDE.md`

---

**Status**: ✅ Production Ready
**Created**: November 28, 2025
**Powered by**: Monica AI
