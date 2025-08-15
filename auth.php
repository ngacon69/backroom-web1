<?php
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$accountsFile = 'accounts.txt';

function loadAccounts() {
    global $accountsFile;
    if (!file_exists($accountsFile)) {
        file_put_contents($accountsFile, json_encode([]));
        return [];
    }
    $content = file_get_contents($accountsFile);
    return $content ? json_decode($content, true) : [];
}

function saveAccounts($accounts) {
    global $accountsFile;
    file_put_contents($accountsFile, json_encode($accounts, JSON_PRETTY_PRINT));
}

// Validate input
function validateInput($username, $password) {
    if (empty($username) || empty($password)) {
        return 'Vui lòng nhập đầy đủ thông tin';
    }
    if (strlen($username) < 3 || strlen($username) > 20) {
        return 'Tên đăng nhập phải từ 3-20 ký tự';
    }
    if (strlen($password) < 6) {
        return 'Mật khẩu phải có ít nhất 6 ký tự';
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
    }
    return null;
}

if ($action === 'register') {
    $error = validateInput($username, $password);
    if ($error) {
        echo json_encode(['success' => false, 'message' => $error]);
        exit;
    }
    
    $accounts = loadAccounts();
    
    if (isset($accounts[$username])) {
        echo json_encode(['success' => false, 'message' => 'Tên đăng nhập đã tồn tại']);
        exit;
    }
    
    $accounts[$username] = [
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s')
    ];
    saveAccounts($accounts);
    
    echo json_encode(['success' => true]);
} elseif ($action === 'login') {
    $error = validateInput($username, $password);
    if ($error) {
        echo json_encode(['success' => false, 'message' => $error]);
        exit;
    }
    
    $accounts = loadAccounts();
    
    if (!isset($accounts[$username])) {
        echo json_encode(['success' => false, 'message' => 'Tên đăng nhập không tồn tại']);
        exit;
    }
    
    if (!password_verify($password, $accounts[$username]['password'])) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng']);
        exit;
    }
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
}
?>