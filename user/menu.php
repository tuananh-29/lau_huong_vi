<?php
session_start();
include '../config/db.php';
include 'php/header.php';
$category_id = isset($_GET['category']) && is_numeric($_GET['category']) 
    ? (int)$_GET['category'] 
    : 0;
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 9;
$offset = ($page - 1) * $items_per_page;
$sql_where = " WHERE trang_thai = 'con_hang' ";
$params = [];
$types = "";
if ($category_id > 0) {
    $sql_where .= " AND ma_danh_muc = ? ";
    $params[] = &$category_id;
    $types .= "i";
}
if (!empty($search_term)) {
    $sql_where .= " AND ten_mon_an LIKE ? ";
    $search_like = "%" . $search_term . "%";
    $params[] = &$search_like;
    $types .= "s";
}
$sql_count = "SELECT COUNT(*) FROM mon_an" . $sql_where;
$stmt_count = $conn->prepare($sql_count);
if (!empty($types)) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$stmt_count->bind_result($total_items);
$stmt_count->fetch();
$stmt_count->close();

$total_pages = ceil($total_items / $items_per_page);
$sql = "SELECT ma_mon_an, ten_mon_an, mo_ta, gia, anh 
        FROM mon_an" . $sql_where . "
        LIMIT ? OFFSET ?";
$params_select = $params;
$params_select[] = &$items_per_page;
$params_select[] = &$offset;
$types_select = $types . "ii";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types_select, ...$params_select);
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
    <style>
        .category-choice-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            padding: 50px 0;
        }
        .category-choice {
            width: 320px;
            height: 320px;
            overflow: hidden;
            border-radius: 10px;
            position: relative;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transition: 0.3s;
        }
        .category-choice:hover { transform: translateY(-5px); }
        .category-choice img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .category-choice span {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            font-size: 24px;
            color: white;
            text-align: center;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        }
    </style>
</head>
<body>
<main>
<section class="menu-page">
<div class="container">
<?php if ($category_id == 0): ?>
    <h2 style="text-align:center;margin-bottom:40px;">Vui Lòng Chọn Loại Món Ăn</h2>
    <div class="category-choice-container">
        <a href="menu.php?category=1" class="category-choice">
            <img src="images/placeholder_lau.jpg"
                 onerror="this.src='https://placehold.co/320x320/c06a4f/fff?text=L%E1%BA%A9u';">
            <span>Lẩu</span>
        </a>
        <a href="menu.php?category=2" class="category-choice">
            <img src="images/placeholder_monle.jpg"
                 onerror="this.src='https://placehold.co/320x320/333/fff?text=M%C3%B3n+L%E1%BA%BB';">
            <span>Món lẻ</span>
        </a>
    </div>
<?php else: ?>
    <h2>
        <?php echo ($category_id == 1) ? "Lẩu" : "Món lẻ"; ?>
    </h2>
    <div class="search-bar">
        <form action="menu.php" method="GET">
            <input type="hidden" name="category" value="<?php echo $category_id; ?>">
            <input type="text" name="search" 
                placeholder="Tìm trong danh mục này..."
                value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="cta-button">Tìm</button>
        </form>
    </div>
    <div class="menu-grid">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $image = "../images/" . $row["anh"];
                if (!file_exists($image)) {
                    $image = "https://placehold.co/400x300?text=No+Image";
                }
                echo '<div class="menu-item">';
                echo '  <a href="product_detail.php?id=' . $row["ma_mon_an"] . '">';
                echo '      <img src="' . $image . '" alt="' . $row["ten_mon_an"] . '">';
                echo '      <h3>' . $row["ten_mon_an"] . '</h3>';
                echo '  </a>';

                echo '  <p>' . $row["mo_ta"] . '</p>';
                echo '<div class="menu-item-bottom">';
                echo '    <span class="price">' . number_format($row["gia"], 0, ',', '.') . ' VNĐ</span>';
                echo '    <form action="php/cart_action.php" method="POST">';
                echo '         <input type="hidden" name="product_id" value="' . $row["ma_mon_an"] . '">';
                echo '         <input type="hidden" name="quantity" value="1">';
                echo '         <button class="cta-button-small" type="submit" name="add_to_cart">+</button>';
                echo '    </form>';
                echo '</div>';

                echo '</div>';
            }
        } else {
            echo "<p style='text-align:center;width:100%;'>Không có món nào.</p>";
        }
        ?>
    </div>
    <div class="pagination" style="text-align:center;margin-top:40px;">

        <?php if ($page > 1): ?>
            <a href="menu.php?page=<?php echo $page - 1; ?>&category=<?php echo $category_id; ?>&search=<?php echo $search_term; ?>">&laquo; Trang trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="menu.php?page=<?php echo $i; ?>&category=<?php echo $category_id; ?>&search=<?php echo $search_term; ?>"
               style="<?php echo $i == $page ? 'font-weight:bold;color:var(--color-primary);' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
            <a href="menu.php?page=<?php echo $page + 1; ?>&category=<?php echo $category_id; ?>&search=<?php echo $search_term; ?>">Trang sau &raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
</div>
</section>
</main>
<?php include 'php/footer.php'; ?>
</body>
</html>