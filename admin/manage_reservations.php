<?php
include 'admin_auth.php';
include '../config/db.php';

$message = '';

if (isset($_POST['approve_booking'])) {
    $ma_dat_ban = (int)$_POST['ma_dat_ban'];
    $ma_ban = (int)$_POST['ma_ban'];

    if ($ma_dat_ban > 0 && $ma_ban > 0) {
        $conn->begin_transaction();
        try {
            $sql_booking = "UPDATE dat_ban SET trang_thai = 'da_xac_nhan', ma_ban = ? WHERE ma_dat_ban = ?";
            $stmt = $conn->prepare($sql_booking);
            $stmt->bind_param("ii", $ma_ban, $ma_dat_ban);
            $stmt->execute();
            $stmt->close();

            $sql_table = "UPDATE ban_an SET trang_thai = 'da_dat' WHERE ma_ban = ?";
            $stmt_table = $conn->prepare($sql_table);
            $stmt_table->bind_param("i", $ma_ban);
            $stmt_table->execute();
            $stmt_table->close();

            $conn->commit();
            $message = '<div class="message success">Đã duyệt và xếp bàn thành công!</div>';
        } catch (Exception $e) {
            $conn->rollback();
            $message = '<div class="message error">Lỗi: ' . $e->getMessage() . '</div>';
        }
    } else {
        $message = '<div class="message error">Vui lòng chọn bàn trước khi duyệt!</div>';
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE dat_ban SET trang_thai = 'da_huy' WHERE ma_dat_ban = $id");
    $message = '<div class="message success">Đã hủy lịch đặt!</div>';
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM dat_ban WHERE ma_dat_ban = $id");
    $message = '<div class="message success">Đã xóa lịch đặt!</div>';
}

$sql_get_bookings = "SELECT db.*, ba.ten_ban 
                     FROM dat_ban db 
                     LEFT JOIN ban_an ba ON db.ma_ban = ba.ma_ban 
                     ORDER BY db.ngay_den DESC, FIELD(db.trang_thai, 'cho_xac_nhan') DESC";
$result_bookings = $conn->query($sql_get_bookings);

$sql_get_tables = "SELECT * FROM ban_an WHERE trang_thai = 'trong'";
$result_tables = $conn->query($sql_get_tables);
$available_tables = [];
while($t = $result_tables->fetch_assoc()) {
    $available_tables[] = $t;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Đặt Bàn</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <main class="main-content">
            <h1>Quản Lý Đặt Bàn & Xếp Bàn</h1>
            <?php echo $message; ?>
            
            <div class="content-box">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách Hàng</th>
                            <th>Ngày/Giờ</th>
                            <th>Số Khách</th>
                            <th>Bàn</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_bookings->num_rows > 0): ?>
                            <?php while($row = $result_bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ma_dat_ban']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($row['ten_nguoi_dat']); ?><br>
                                        <small><?php echo htmlspecialchars($row['sdt_nguoi_dat']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($row['ngay_den'])); ?><br>
                                        <?php echo date('H:i', strtotime($row['gio_den'])); ?>
                                    </td>
                                    <td><?php echo $row['so_luong_khach']; ?></td>
                                    
                                    <td>
                                        <?php if($row['ma_ban']): ?>
                                            <strong style="color:blue;"><?php echo $row['ten_ban']; ?></strong>
                                        <?php else: ?>
                                            <span style="color:#999;">Chưa xếp</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php 
                                            if($row['trang_thai'] == 'cho_xac_nhan') echo '<span style="color:orange; font-weight:bold;">Chờ xếp bàn</span>';
                                            elseif($row['trang_thai'] == 'da_xac_nhan') echo '<span style="color:green; font-weight:bold;">Đã chốt</span>';
                                            else echo '<span style="color:red;">' . $row['trang_thai'] . '</span>';
                                        ?>
                                    </td>
                                    
                                    <td class="action-links">
                                        <?php if ($row['trang_thai'] == 'cho_xac_nhan'): ?>
                                            <form method="POST" action="manage_reservations.php" style="display:flex; gap:5px;">
                                                <input type="hidden" name="ma_dat_ban" value="<?php echo $row['ma_dat_ban']; ?>">
                                                
                                                <select name="ma_ban">
                                                    <option value="">--Chọn bàn--</option>
                                                    <?php foreach($available_tables as $tb): ?>
                                                        <option value="<?php echo $tb['ma_ban']; ?>">
                                                            <?php echo $tb['ten_ban']; ?> (<?php echo $tb['so_ghe']; ?> ghế)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                                <button type="submit" name="approve_booking" class="form-button" style="padding:5px 10px; font-size:12px; background:green;">Duyệt</button>
                                            </form>
                                            
                                            <a href="manage_reservations.php?action=cancel&id=<?php echo $row['ma_dat_ban']; ?>" class="delete-link" style="color:orange; margin-top:5px; display:inline-block;">Hủy</a>
                                        
                                        <?php else: ?>
                                            <a href="manage_reservations.php?delete=<?php echo $row['ma_dat_ban']; ?>" class="delete-link" onclick="return confirm('Xóa lịch sử này?');">Xóa</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center;">Chưa có dữ liệu.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
