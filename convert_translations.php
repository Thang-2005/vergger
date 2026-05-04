<?php
/**
 * Script để chuyển đổi tất cả __('messages.xxx') sang tiếng Việt
 */

// Bản dịch tiếng Việt
$translations = [
    "product_management" => "Quản lý sản phẩm",
    "in_stock" => "Hàng còn",
    "out_of_stock" => "Hết hàng",
    "all_status" => "Tất cả trạng thái",
    "search_and_manage" => "Tìm kiếm và quản lý",
    "product_list" => "Danh sách sản phẩm",
    "add_product" => "Thêm sản phẩm",
    "product_image" => "Ảnh sản phẩm",
    "product_name" => "Tên sản phẩm",
    "category" => "Danh mục",
    "price" => "Giá",
    "inventory" => "Tồn kho",
    "status" => "Trạng thái",
    "action" => "Hành động",
    "filter" => "Lọc",
    "search_product" => "Tìm kiếm sản phẩm",
    "all_categories" => "Tất cả danh mục",
    "no_products" => "Không có sản phẩm",
    "view_details" => "Xem chi tiết",
    "edit" => "Sửa",
    "delete" => "Xóa",
    "add_new_product" => "Thêm sản phẩm mới",
    "product_slug" => "Slug sản phẩm",
    "auto_if_empty" => "Tự động nếu trống",
    "unit" => "Đơn vị",
    "description" => "Mô tả",
    "select_category" => "Chọn danh mục",
    "save_product" => "Lưu sản phẩm",
    "no_image" => "Không có ảnh",
    "edit_product" => "Sửa sản phẩm",
    "save_changes" => "Lưu thay đổi",
    "no_permission_edit_product" => "Bạn không có quyền sửa sản phẩm",
    "close" => "Đóng",
    "dashboard" => "Bảng điều khiển",
    "home" => "Trang chủ",
    "logout" => "Đăng xuất",
    "welcome" => "Chào mừng",
    "about" => "Về chúng tôi",
    "service" => "Dịch vụ",
    "team" => "Team",
    "faq" => "FAQ",
    "shop" => "Cửa hàng",
    "contact" => "Liên hệ",
    "account" => "Tài khoản",
    "wishlist" => "Yêu thích",
    "cart" => "Giỏ hàng",
    "checkout" => "Thanh toán",
    "login" => "Đăng nhập",
    "register" => "Đăng ký",
    "my_orders" => "Đơn hàng của tôi",
    "order_list" => "Danh sách đơn hàng",
    "order_id" => "Mã đơn hàng",
    "customer_name" => "Tên khách hàng",
    "total_amount" => "Tổng tiền",
    "order_status" => "Trạng thái đơn hàng",
    "order_date" => "Ngày đặt hàng",
    "detail" => "Chi tiết",
    "email" => "Email",
    "phone" => "Số điện thoại",
    "message" => "Tin nhắn",
    "send" => "Gửi",
    "full_name" => "Họ và tên",
    "password" => "Mật khẩu",
    "profile" => "Tài khoản",
    "admin" => "Quản trị viên",
    "user_management" => "Quản lý người dùng",
    "role_management" => "Quản lý vai trò",
    "recent_orders" => "Đơn hàng gần đây",
    "latest_10_orders" => "10 đơn hàng mới nhất",
    "view_all_orders" => "Xem tất cả đơn hàng",
    "customer" => "Khách hàng",
    "amount" => "Số tiền",
];

// Lấy tất cả các file blade.php
$dir = __DIR__ . "/resources/views";

function getAllBladeFiles($dir) {
    $files = [];
    if (!is_dir($dir)) return $files;
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($iterator as $file) {
        if ($file->getExtension() === "php" && strpos($file->getFilename(), ".blade.php") !== false) {
            $files[] = $file->getPathname();
        }
    }
    return $files;
}

$files = getAllBladeFiles($dir);

echo "Tìm thấy " . count($files) . " file\n";

$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    // Thay thế tất cả __('messages.xxx')
    $content = preg_replace_callback("/__\(['\"]messages\.([a-z0-9_]+)['\"]\)/", function($matches) use ($translations) {
        $key = $matches[1];
        if (isset($translations[$key])) {
            return "'" . $translations[$key] . "'";
        }
        return $matches[0];
    }, $content);
    
    // Nếu có thay đổi, ghi lại file
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        $count++;
        echo "✓ " . str_replace(__DIR__ . DIRECTORY_SEPARATOR, "", $file) . "\n";
    }
}

echo "\n✅ Đã chuyển đổi $count file!\n";