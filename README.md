# Monica Gateway Monitor - PHP Dashboard

Headless API-based monitoring dashboard for Asterisk servers.

## Files

- `index.php` - Main dashboard
- `api.php` - API proxy to Python monitor
- `config.php` - Server configuration

## Setup

### 1. Configure Servers

Edit `config.php`:

```php
$servers = [
    [
        'id' => 'local',
        'name' => 'Local Server',
        'api_url' => 'http://127.0.0.1:5050'
    ],
    [
        'id' => 'server1',
        'name' => 'Production Server 1',
        'api_url' => 'http://192.168.1.100:5050'
    ]
];
```

### 2. Access Dashboard

Open: `http://your-web-server/monica_gateway_monitor/`

### 3. Usage

1. Select server from dropdown
2. Click "Load Server"
3. View real-time monitoring

## Features

✅ Multi-server support (dropdown selector)
✅ System resources (CPU, RAM, Disk)
✅ Active channels monitoring
✅ Channel variables view
✅ Hangup channels
✅ Join/Spy on calls
✅ Auto-refresh (configurable)
✅ Headless API design

## API Endpoints Used

- `GET /api/system` - System resources
- `GET /api/status` - Channels & endpoints
- `GET /api/channel_vars?channel=<name>` - Channel variables
- `POST /api/action` - Execute actions (hangup/join)

## Integration with Your App

This dashboard is standalone and can be:

1. **Embedded in iframe**
```php
<iframe src="/monica_gateway_monitor/" style="width:100%;height:800px;"></iframe>
```

2. **Integrated into CodeIgniter**
- Copy files to your application
- Modify paths and styling
- Use your authentication

3. **Used as API reference**
- Build your own UI
- Use the same API calls
- Full customization

## Configuration

Edit `config.php` to:
- Add/remove servers
- Change app title
- Adjust refresh interval
- Customize branding

## Requirements

- PHP 7.0+
- curl extension enabled
- Python monitor running on target servers

## Moving to Your Application

```bash
# Copy to your CodeIgniter app
cp -r /var/www/html/monica_gateway_monitor /path/to/your/app/modules/monitor
```

Then integrate with your authentication and layout.

---

**Status**: ✅ Production Ready
**Version**: 1.0
