<?php
header('Content-Type: text/html; charset=utf-8');

// Cấu hình DB
$servername = "localhost";
$username   = "root";
$password   = "cCW4PnxxArMAdrjk";
$database   = "sql_antamecommer";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$phone = isset($_GET['phone']) ? $_GET['phone'] : '';

$sql = "SELECT p.* FROM products AS p 
        LEFT JOIN lck_core_users AS cu ON cu.id = p.shop_id 
        WHERE cu.phone = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $str = '<option>Nhập tên sản phẩm</option>';
    while ($row = $result->fetch_assoc()) {
        $product_code = htmlspecialchars($row["product_code"], ENT_QUOTES, 'UTF-8');
        $product_name = htmlspecialchars($row["product_name"], ENT_QUOTES, 'UTF-8');
        $str .= "<option value='{$product_code}'>{$product_name}</option>";
    }
    echo $str;
} else {
    echo "<option value='0'>Nhập tên sản phẩm</option>";
}
$stmt->close();
$conn->close();
