<!DOCTYPE html>
<html>
<head>
    <title>🚀 KiplayRat QR Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 p-5 mx-auto" style="max-width: 500px;">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-white">🎮 KiplayRat</h1>
            <p class="lead text-white-50">Professional QR Generator</p>
        </div>
        
        <div class="card p-5">
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-bold text-white">Payload URL</label>
                    <input type="url" name="payload" class="form-control" required 
                           placeholder="https://your-domain.com/install.php" style="font-size: 16px;">
                </div>
                <button type="submit" name="generate_qr" class="btn btn-success w-100 fs-5 py-3">
                    ✨ Generate QR Code
                </button>
            </form>
            
            <?php if (isset($result)): ?>
            <div class="mt-4 text-center">
                <img src="data:image/png;base64,<?= base64_encode($result->getString()) ?>" class="img-fluid rounded shadow">
                <a href="download.php?qr=<?= $qrData ?>" class="btn btn-primary mt-3 w-100">💾 Download QR</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
