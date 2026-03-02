<?php
session_start();
require_once '../databases/db.php';

// Basic security - you should implement proper authentication
$allowed_ips = array('127.0.0.1', '::1'); // localhost IPs
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    die('Access Denied');
}

// Get all messages
try {
    $stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATA - Admin Messages</title>
    <link rel="stylesheet" href="../all.css">
    <style>
        .messages-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .message-card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .message-content {
            white-space: pre-wrap;
        }
        .message-meta {
            color: #666;
            font-size: 0.9em;
        }
        .no-messages {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="../nembo.png" alt="TATA Logo">
                <span>TATA Admin</span>
            </div>
        </nav>
    </header>

    <main class="messages-container">
        <h1>Contact Messages</h1>
        
        <?php if (empty($messages)): ?>
            <div class="no-messages">
                <p>No messages yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-card">
                    <div class="message-header">
                        <div>
                            <strong>From:</strong> <?= htmlspecialchars($message['name']) ?> 
                            (<a href="mailto:<?= htmlspecialchars($message['email']) ?>"><?= htmlspecialchars($message['email']) ?></a>)
                        </div>
                        <div class="message-meta">
                            <?= date('F j, Y g:i A', strtotime($message['created_at'])) ?>
                        </div>
                    </div>
                    <div>
                        <strong>Subject:</strong> <?= htmlspecialchars($message['subject']) ?>
                    </div>
                    <div class="message-content">
                        <?= nl2br(htmlspecialchars($message['message'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 THINK AND ACT FOR TANZANIA (TATA). All rights reserved.</p>
        </div>
    </footer>
</body>
</html>