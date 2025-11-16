<?php
session_start();
include 'config/db.php';
include 'php/header.php';

// --- LOGIC PHÂN TRANG (3.11) ---
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 9; // Hiển thị 9 món ăn mỗi trang
$offset = ($page - 1) * $items_per_page;

// --- LOGIC TÌM KIẾM (3.10) ---
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$sql_where = " WHERE trang_thai = 'con_hang' ";
$params = array();
$types = "";

if (!empty($search_term)) {
    $sql_where .= " AND ten_mon_an LIKE ? ";
    $search_like = "%" . $search_term . "%";
    $params[] = &$search_like; // Thêm vào mảng tham số
    $types .= "s"; // string
}

// 1. Đếm tổng số sản phẩm (để tính số trang)
$sql_count = "SELECT COUNT(*) FROM mon_an" . $sql_where;
$stmt_count = $conn->prepare($sql_count);
if (!empty($search_term)) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$stmt_count->bind_result($total_items);
$stmt_count->fetch();
$stmt_count->close();

$total_pages = ceil($total_items / $items_per_page);

// 2. Lấy sản phẩm cho trang hiện tại
$sql = "SELECT ma_mon_an, ten_mon_an, mo_ta, gia, anh 
        FROM mon_an" . $sql_where . "
        LIMIT ? OFFSET ?";

// Thêm 2 tham số (LIMIT, OFFSET) vào
$params[] = &$items_per_page;
$params[] = &$offset;
$types .= "ii"; // integer, integer

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thực Đơn - Lẩu Hương Vị</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <section class="menu-page">
            <div class="container">
                <h2>Toàn Bộ Thực Đơn</h2>
                
                <div class="search-bar">
                    <form action="menu.php" method="GET">
                        <input type="text" id="searchInput" name="search" placeholder="Tìm tên món lẩu bạn muốn..." value="<?php echo htmlspecialchars($search_term); ?>">
                        <button type="submit" class="cta-button">Tìm</button>
                    </form>
                </div>

                <div class="menu-grid" id="menuList">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="menu-item">';
                            echo '    <a href="product_detail.php?id=' . $row["ma_mon_an"] . '">';
                            echo '        <img src=" images/' . htmlspecialchars($row["anh"]) . '" alt="' . htmlspecialchars($row["ten_mon_an"]) . '">';
                            echo '        <h3>' . htmlspecialchars($row["ten_mon_an"]) . '</h3>';
                            echo '    </a>';
                            echo '    <p>' . htmlspecialchars($row["mo_ta"]) . '</p>';
                            echo '    <span class="price">' . number_format($row["gia"], 0, ',', '.') . ' VNĐ</span>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p style='text-align: center; width: 100%;'>Không tìm thấy món ăn nào phù hợp.</p>";
                    }
                    $stmt->close();
                    $conn->close();
                    ?>
                </div>
                
                <div class="pagination" style="text-align: center; margin-top: 40px;">
                    <?php if ($page > 1): ?>
                        <a href="menu.php?page=<?php echo $page - 1; ?>&search=<?php echo $search_term; ?>">&laquo; Trang trước</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="menu.php?page=<?php echo $i; ?>&search=<?php echo $search_term; ?>" 
                           style="<?php echo $i == $page ? 'font-weight: bold; color: var(--color-primary);' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="menu.php?page=<?php echo $page + 1; ?>&search=<?php echo $search_term; ?>">Trang sau &raquo;</a>
                    <?php endif; ?>
                </div>

            </div>
        </section>
    </main>
    <?php include 'php/footer.php'; ?>
</body>
</html>