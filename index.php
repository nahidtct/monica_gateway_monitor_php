<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $app_config['title']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #1a1a2e; color: #eee; padding: 20px; }
        .container { max-width: 1800px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px 30px; border-radius: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 28px; margin-bottom: 15px; }
        .server-selector { display: flex; gap: 10px; align-items: center; margin-bottom: 15px; }
        .server-selector select { padding: 10px 15px; border-radius: 5px; border: none; font-size: 14px; background: white; color: #333; }
        .server-selector button { padding: 10px 25px; border-radius: 5px; border: none; background: #10b981; color: white; font-weight: 600; cursor: pointer; }
        .server-selector button:hover { background: #059669; }
        .resources { display: flex; gap: 15px; flex-wrap: wrap; }
        .resource-badge { background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 15px; font-size: 13px; font-weight: 600; }
        .stats-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: #16213e; border-radius: 10px; padding: 25px; text-align: center; border: 2px solid #0f3460; }
        .stat-number { font-size: 48px; font-weight: bold; color: #10b981; margin: 10px 0; }
        .stat-label { font-size: 14px; color: #aaa; text-transform: uppercase; }
        .card { background: #16213e; border-radius: 10px; padding: 20px; margin-bottom: 20px; border: 2px solid #0f3460; }
        .card h2 { font-size: 22px; margin-bottom: 15px; color: #fff; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0f3460; padding: 14px; text-align: left; font-weight: 600; color: #fff; }
        td { padding: 14px; border-bottom: 1px solid #0f3460; color: #ccc; }
        tr:hover { background: #0f3460; }
        .badge { display: inline-block; padding: 5px 14px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge.success { background: #10b981; color: #fff; }
        .badge.error { background: #ef4444; color: #fff; }
        .badge.info { background: #3b82f6; color: #fff; }
        .btn { padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; margin: 0 3px; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-info { background: #3b82f6; color: #fff; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn:hover { opacity: 0.8; }
        .no-data { text-align: center; padding: 40px; color: #666; }
        .loading { text-align: center; padding: 40px; color: #667eea; font-size: 18px; }
        .error { background: #ef4444; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .footer { text-align: center; color: #667eea; font-size: 16px; margin-top: 20px; font-weight: 600; }
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .tab { padding: 12px 30px; background: #16213e; border: 2px solid #0f3460; border-radius: 8px; cursor: pointer; font-weight: 600; color: #aaa; transition: all 0.3s; }
        .tab.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-color: #667eea; }
        .tab:hover { border-color: #667eea; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); }
        .modal-content { background-color: #16213e; margin: 5% auto; padding: 20px; border: 2px solid #667eea; border-radius: 10px; width: 80%; max-width: 800px; max-height: 80vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        .close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: #fff; }
        .var-table { width: 100%; margin-top: 10px; }
        .var-table td { padding: 8px; border-bottom: 1px solid #0f3460; }
        .var-table td:first-child { font-weight: bold; color: #10b981; width: 40%; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🚀 <?php echo $app_config['title']; ?></h1>
        
        <div class="server-selector">
            <label style="font-weight:600;">Select Server:</label>
            <select id="server-select">
                <?php foreach ($servers as $server): ?>
                <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <button onclick="loadServer()">Load Server</button>
        </div>
        
        <div class="resources" id="resources">
            <span class="resource-badge">--% CPU</span>
            <span class="resource-badge">--% RAM</span>
            <span class="resource-badge">--% Disk</span>
            <span class="resource-badge">Connected: 0</span>
        </div>
    </div>

    <div id="content">
        <div class="loading">Select a server and click "Load Server" to begin monitoring</div>
    </div>

    <div class="footer">Powered by <?php echo $app_config['branding']; ?></div>
</div>

<!-- Variables Modal -->
<div id="varModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Channel Variables</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div id="varContent"></div>
    </div>
</div>

<script>
let currentServer = null;
let refreshInterval = null;
let currentTab = 'channels';

function loadServer() {
    const serverId = document.getElementById('server-select').value;
    currentServer = serverId;
    
    // Clear existing interval
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
    
    // Load data immediately
    updateData();
    
    // Set up auto-refresh
    refreshInterval = setInterval(updateData, <?php echo $app_config['refresh_interval']; ?>);
}

function updateData() {
    if (!currentServer) return;
    
    // Fetch system resources
    fetch(`api.php?action=system&server_id=${currentServer}`)
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                showError(data.error);
                return;
            }
            
            const resources = document.getElementById('resources');
            resources.innerHTML = `
                <span class="resource-badge" style="background:#3b82f6;">${data.cpu.display}</span>
                <span class="resource-badge" style="background:#8b5cf6;">${data.memory.display}</span>
                <span class="resource-badge" style="background:#ec4899;">${data.disk.display}</span>
                <span class="resource-badge" style="background:#10b981;" id="connected-count">Connected: 0</span>
            `;
        })
        .catch(err => showError('Failed to fetch system resources'));
    
    // Fetch channels status
    fetch(`api.php?action=status&server_id=${currentServer}`)
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                showError(data.error);
                return;
            }
            
            // Count connected calls
            let connectedCount = 0;
            data.channels.forEach(ch => {
                if (ch.ChannelState === '6') connectedCount++;
            });
            
            const connectedEl = document.getElementById('connected-count');
            if (connectedEl) {
                connectedEl.textContent = `Connected: ${connectedCount}`;
            }
            
            // Display channels and store endpoints
            displayChannels(data.channels, data.endpoints || []);
        })
        .catch(err => showError('Failed to fetch channel status'));
}

function displayChannels(channels, endpoints) {
    // Calculate endpoint breakdown
    let epBreakdown = {available: 0, unavailable: 0, in_use: 0, other: 0};
    if (endpoints && endpoints.length > 0) {
        endpoints.forEach(ep => {
            let state = (ep.DeviceState || '').toLowerCase();
            if (state.includes('not in use') || state.includes('idle')) {
                epBreakdown.available++;
            } else if (state.includes('unavailable')) {
                epBreakdown.unavailable++;
            } else if (state.includes('in use') || state.includes('busy')) {
                epBreakdown.in_use++;
            } else {
                epBreakdown.other++;
            }
        });
    }
    
    let html = '<div class="stats-bar">';
    html += `<div class="stat-card"><div class="stat-label">Total Channels</div><div class="stat-number">${channels.length}</div></div>`;
    
    const connected = channels.filter(ch => ch.ChannelState === '6').length;
    html += `<div class="stat-card"><div class="stat-label">Connected</div><div class="stat-number">${connected}</div></div>`;
    html += `<div class="stat-card"><div class="stat-label">Available</div><div class="stat-number">${epBreakdown.available}</div></div>`;
    html += `<div class="stat-card"><div class="stat-label">Unavailable</div><div class="stat-number">${epBreakdown.unavailable}</div></div>`;
    html += `<div class="stat-card"><div class="stat-label">In Use</div><div class="stat-number">${epBreakdown.in_use}</div></div>`;
    html += '</div>';
    
    html += '<div class="tabs">';
    html += `<div class="tab ${currentTab === 'channels' ? 'active' : ''}" onclick="switchTab('channels')">📞 Channels</div>`;
    html += `<div class="tab ${currentTab === 'endpoints' ? 'active' : ''}" onclick="switchTab('endpoints')">🔌 Endpoints</div>`;
    html += '</div>';
    
    // Channels Tab
    html += `<div id="channels-tab" class="tab-content ${currentTab === 'channels' ? 'active' : ''}">`;
    html += '<div class="card"><h2>📞 Active Channels</h2>';
    
    if (channels.length > 0) {
        html += '<table><thead><tr>';
        html += '<th>Channel</th><th>Caller ID</th><th>Connected Line</th><th>State</th><th>Duration</th><th>Actions</th>';
        html += '</tr></thead><tbody>';
        
        channels.forEach(ch => {
            let duration = '0s';
            if (ch.ChannelState === '6' && ch.Duration) {
                const parts = ch.Duration.split(':');
                let seconds = 0;
                if (parts.length === 3) {
                    seconds = parseInt(parts[0]) * 3600 + parseInt(parts[1]) * 60 + parseInt(parts[2]);
                } else if (parts.length === 2) {
                    seconds = parseInt(parts[0]) * 60 + parseInt(parts[1]);
                }
                if (seconds > 0) duration = seconds + 's';
            }
            
            html += '<tr>';
            html += `<td><strong>${ch.Channel || 'N/A'}</strong></td>`;
            html += `<td>${ch.CallerIDNum || 'N/A'}</td>`;
            html += `<td>${ch.ConnectedLineNum || ch.Exten || 'N/A'}</td>`;
            html += `<td><span class="badge info">${ch.ChannelStateDesc || 'Active'}</span></td>`;
            html += `<td>${duration}</td>`;
            html += `<td>
                <button class="btn btn-warning" onclick="showVars('${ch.Channel}')">View</button>
                <button class="btn btn-danger" onclick="hangup('${ch.Channel}')">Hangup</button>
                <button class="btn btn-info" onclick="join('${ch.Channel}')">Join</button>
            </td>`;
            html += '</tr>';
        });
        
        html += '</tbody></table>';
    } else {
        html += '<div class="no-data">No active channels</div>';
    }
    
    html += '</div></div>';
    
    // Endpoints Tab
    html += `<div id="endpoints-tab" class="tab-content ${currentTab === 'endpoints' ? 'active' : ''}">`;
    html += '<div class="card"><h2>🔌 Endpoints</h2>';
    
    if (endpoints && endpoints.length > 0) {
        html += '<table><thead><tr>';
        html += '<th>Object Type</th><th>Object Name</th><th>Transport</th><th>Identify</th><th>Match</th><th>Channels</th><th>State</th>';
        html += '</tr></thead><tbody>';
        
        endpoints.forEach(ep => {
            html += '<tr>';
            html += `<td>${ep.ObjectType || 'N/A'}</td>`;
            html += `<td><strong>${ep.ObjectName || 'N/A'}</strong></td>`;
            html += `<td>${ep.Transport || 'N/A'}</td>`;
            html += `<td>${ep.Identify || 'N/A'}</td>`;
            html += `<td>${ep.Match || 'N/A'}</td>`;
            html += `<td>${ep.Channels || '0'}</td>`;
            html += `<td><span class="badge ${ep.DeviceState === 'Not in use' ? 'success' : 'info'}">${ep.DeviceState || 'Unknown'}</span></td>`;
            html += '</tr>';
        });
        
        html += '</tbody></table>';
    } else {
        html += '<div class="no-data">No endpoints found</div>';
    }
    
    html += '</div></div>';
    
    document.getElementById('content').innerHTML = html;
}

function showVars(channel) {
    fetch(`api.php?action=channel_vars&server_id=${currentServer}&channel=${encodeURIComponent(channel)}`)
        .then(r => r.json())
        .then(data => {
            let html = '';
            if (data.variables && Object.keys(data.variables).length > 0) {
                html = '<table class="var-table">';
                for (let key in data.variables) {
                    html += `<tr><td>${key}</td><td>${data.variables[key]}</td></tr>`;
                }
                html += '</table>';
            } else {
                html = '<p style="color:#666;">No variables found</p>';
            }
            document.getElementById('varContent').innerHTML = html;
            document.getElementById('varModal').style.display = 'block';
        });
}

function hangup(channel) {
    if (!confirm('Hangup channel: ' + channel + '?')) return;
    
    const formData = new FormData();
    formData.append('channel', channel);
    
    fetch(`api.php?action=hangup&server_id=${currentServer}`, {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        alert(data.success ? 'Hangup initiated' : 'Failed to hangup');
        updateData();
    });
}

function join(channel) {
    const number = prompt('Enter your extension/number:');
    if (!number) return;
    
    const formData = new FormData();
    formData.append('channel', channel);
    formData.append('spy_number', number);
    
    fetch(`api.php?action=join&server_id=${currentServer}`, {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        alert(data.success ? `Join initiated. You will receive a call on ${number}` : 'Failed to join');
    });
}

function closeModal() {
    document.getElementById('varModal').style.display = 'none';
}

function switchTab(tabName) {
    currentTab = tabName;
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    
    event.target.classList.add('active');
    document.getElementById(tabName + '-tab').classList.add('active');
}

function showError(message) {
    document.getElementById('content').innerHTML = `<div class="error">❌ Error: ${message}</div>`;
}

window.onclick = function(event) {
    if (event.target == document.getElementById('varModal')) {
        closeModal();
    }
}
</script>
</body>
</html>
