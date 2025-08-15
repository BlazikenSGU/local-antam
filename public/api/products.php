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
$fnc   = isset($_GET['func'])  ? $_GET['func'] : '';

switch ($fnc) {
    case 'GETPRODUCTLIST':
        // Lấy danh sách sản phẩm theo số điện thoại
        $sql = "SELECT p.* 
                FROM products AS p 
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
        $d = 0;
        $str = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $d++;
                $str .= "<tr>
                            <th scope='row'>{$d}</th>
                            <td class='product-code'>{$row['product_code']}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['product_weight']}</td>
                            <td>
                                <span class='btn-dx' onclick='DeleteProduct({$row['product_id']})'>
                                    <i class='fa fa-trash'></i>
                                </span>
                            </td>
                         </tr>";
            }
            echo $str;
        } else {
            echo "(chưa có sản phẩm)";
        }
        $stmt->close();
        break;

    case 'ADDPRODUCT':
        $product_name   = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
        $product_code   = isset($_POST['product_code']) ? trim($_POST['product_code']) : '';
        $product_weight = isset($_POST['product_weight']) ? floatval($_POST['product_weight']) : 0;
        $shop_id        = isset($_POST['shop_id']) ? intval($_POST['shop_id']) : 0;
        
        if (empty($product_name) || empty($product_code) || $product_weight <= 0 || $shop_id <= 0) {
            echo "Invalid input.";
            break;
        }
        
        $sql = "INSERT INTO products(product_code, product_name, product_weight, shop_id) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }
        $stmt->bind_param("ssdi", $product_code, $product_name, $product_weight, $shop_id);
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        break;

    case 'DELETEPRODUCT':
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $shop_id    = isset($_POST['shop_id']) ? intval($_POST['shop_id']) : 0;
        if ($product_id <= 0 || $shop_id <= 0) {
            echo "Invalid input.";
            break;
        }
        $sql = "DELETE FROM products WHERE shop_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }
        $stmt->bind_param("ii", $shop_id, $product_id);
        if ($stmt->execute()) {
            echo "Delete record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        break;

    case 'GETPRODUCT':
        $product_code = isset($_GET['product_code']) ? trim($_GET['product_code']) : '';
        if (empty($product_code)) {
            echo "-1";
            break;
        }
        $sql = "SELECT product_weight FROM products WHERE product_code = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }
        $stmt->bind_param("s", $product_code);
        $stmt->execute();
        $stmt->bind_result($product_weight);
        if ($stmt->fetch()) {
            echo $product_weight;
        } else {
            echo "-1";
        }
        $stmt->close();
        break;

    default:
        echo "Invalid function.";
        break;
}

$conn->close();
?>
