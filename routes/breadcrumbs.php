<?php
// Home
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('Dashboard', function ($trail) {
    $trail->push('Dashboard', route('backend.dashboard'));
});

// Home > login
Breadcrumbs::for('login', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Login', route('backend.login'));
});
Breadcrumbs::for('backend.salary.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Quản Lý dự án', '');
});
// Home > profile
Breadcrumbs::for('Profile', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Thông tin tài khoản', );
});

Breadcrumbs::for('backend.users.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Quản lý tài khoản', route('backend.users.index'));
});

Breadcrumbs::for('backend.users.add', function ($trail) {
    $trail->parent('backend.users.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.users.edit', function ($trail) {
    $trail->parent('backend.users.index');
    $trail->push('Cập nhật', '');
});

// Products index
Breadcrumbs::for('backend.products.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Sản phẩm', route('backend.products.index'));
});

Breadcrumbs::for('backend.products.detail', function ($trail) {
    $trail->parent('backend.products.index');
    $trail->push('Chi tiết', '');
});

Breadcrumbs::for('backend.products.add', function ($trail) {
    $trail->parent('backend.products.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.products.edit', function ($trail) {
    $trail->parent('backend.products.index');
    $trail->push('Chỉnh sửa', '');
});

// Notification
Breadcrumbs::for('backend.notification.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Thông báo', route('backend.notification.index'));
});

Breadcrumbs::for('backend.notification.add', function ($trail) {
    $trail->parent('backend.notification.index');
    $trail->push('Push thông báo', '');
});

// Province
Breadcrumbs::for('backend.location.province.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Tỉnh/TP', route('backend.location.province.index'));
});

Breadcrumbs::for('backend.location.province.add', function ($trail) {
    $trail->parent('backend.location.province.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.location.province.edit', function ($trail) {
    $trail->parent('backend.location.province.index');
    $trail->push('Chỉnh sửa', '');
});

// District
Breadcrumbs::for('backend.location.district.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Quận/Huyện', route('backend.location.district.index'));
});

Breadcrumbs::for('backend.location.district.add', function ($trail) {
    $trail->parent('backend.location.district.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.location.district.edit', function ($trail) {
    $trail->parent('backend.location.district.index');
    $trail->push('Chỉnh sửa', '');
});

// Ward
Breadcrumbs::for('backend.location.ward.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Phường/Xã', route('backend.location.ward.index'));
});

Breadcrumbs::for('backend.location.ward.add', function ($trail) {
    $trail->parent('backend.location.ward.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.location.ward.edit', function ($trail) {
    $trail->parent('backend.location.ward.index');
    $trail->push('Chỉnh sửa', '');
});

//staff
Breadcrumbs::for('backend.staff.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Quản lý quản trị viên', route('backend.staff.index'));
});

Breadcrumbs::for('backend.staff.add', function ($trail) {
    $trail->parent('backend.staff.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.staff.edit', function ($trail) {
    $trail->parent('backend.staff.index');
    $trail->push('Cập nhật', '');
});

// posts
Breadcrumbs::for('backend.posts.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Bài viết', route('backend.posts.index'));
});

Breadcrumbs::for('backend.posts.add', function ($trail) {
    $trail->parent('backend.posts.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.posts.edit', function ($trail) {
    $trail->parent('backend.posts.index');
    $trail->push('Chỉnh sửa', '');
});

// posts category
Breadcrumbs::for('backend.posts.category.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Danh mục bài viết', route('backend.posts.category.index'));
});

Breadcrumbs::for('backend.posts.category.add', function ($trail) {
    $trail->parent('backend.posts.category.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.posts.category.edit', function ($trail) {
    $trail->parent('backend.posts.category.index');
    $trail->push('Chỉnh sửa', '');
});
// policy
Breadcrumbs::for('backend.policy.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Bài viết', route('backend.policy.index'));
});

Breadcrumbs::for('backend.policy.add', function ($trail) {
    $trail->parent('backend.policy.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.policy.edit', function ($trail) {
    $trail->parent('backend.policy.index');
    $trail->push('Chỉnh sửa', '');
});

// policy category
Breadcrumbs::for('backend.policy.category.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Danh mục chính sách', route('backend.policy.category.index'));
});

Breadcrumbs::for('backend.policy.category.add', function ($trail) {
    $trail->parent('backend.policy.category.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.policy.category.edit', function ($trail) {
    $trail->parent('backend.policy.category.index');
    $trail->push('Chỉnh sửa', '');
});

// products category
Breadcrumbs::for('backend.products.type.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Danh mục sản phẩm', route('backend.products.type.index'));
});

Breadcrumbs::for('backend.products.type.add', function ($trail) {
    $trail->parent('backend.products.type.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.products.type.edit', function ($trail) {
    $trail->parent('backend.products.type.index');
    $trail->push('Chỉnh sửa', '');
});

// products attributes
Breadcrumbs::for('backend.products.attributes.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Thuộc tính sản phẩm', route('backend.products.attributes.index'));
});

Breadcrumbs::for('backend.products.attributes.add', function ($trail) {
    $trail->parent('backend.products.attributes.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.products.attributes.edit', function ($trail) {
    $trail->parent('backend.products.attributes.index');
    $trail->push('Chỉnh sửa', '');
});

// products attributes value
Breadcrumbs::for('backend.products.attributes.values.index', function ($trail) {
    $trail->parent('backend.products.attributes.index');
    $trail->push('Giá trị', route('backend.products.attributes.values.index', ''));
});

Breadcrumbs::for('backend.products.attributes.values.add', function ($trail) {
    $trail->parent('backend.products.attributes.values.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.products.attributes.values.edit', function ($trail) {
    $trail->parent('backend.products.attributes.values.index');
    $trail->push('Chỉnh sửa', '');
});

// projects
Breadcrumbs::for('backend.orders.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Đơn hàng', route('backend.orders.index'));
});

Breadcrumbs::for('backend.orders.detail', function ($trail) {
    $trail->parent('backend.orders.index');
    $trail->push('Chi tiết', '#');
});

// setting
Breadcrumbs::for('backend.setting.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Cài đặt chung', '');
});

// menu
Breadcrumbs::for('backend.menu.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Menu', route('backend.menu.index'));
});

Breadcrumbs::for('backend.menu.add', function ($trail) {
    $trail->parent('backend.menu.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.menu.edit', function ($trail) {
    $trail->parent('backend.menu.index');
    $trail->push('Chỉnh sửa', '');
});


// banner
Breadcrumbs::for('backend.banner.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Banner', route('backend.banner.index'));
});

Breadcrumbs::for('backend.banner.add', function ($trail) {
    $trail->parent('backend.banner.index');
    $trail->push('Thêm mới', '');
});

Breadcrumbs::for('backend.banner.edit', function ($trail) {
    $trail->parent('backend.banner.index');
    $trail->push('Chỉnh sửa', '');
});

Breadcrumbs::for('backend.products.inventory.index', function ($trail) {
    $trail->parent('backend.products.index');
    $trail->push('Quản lý kho', '');
});

//Discount
Breadcrumbs::for('backend.discount.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Mã giảm giá', route('backend.discount.index'));
});
Breadcrumbs::for('backend.discount.add', function ($trail) {
    $trail->parent('backend.discount.index');
    $trail->push('Thêm mới');
});
Breadcrumbs::for('backend.discount.edit', function ($trail) {
    $trail->parent('backend.discount.index');
    $trail->push('Chỉnh sửa');
});

Breadcrumbs::for('backend.brands.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Chi nhánh', route('backend.brands.index'));
});

Breadcrumbs::for('backend.warehouses.index', function ($trail) {
    $trail->parent('Dashboard');
    $trail->push('Bàn', route('backend.warehouses.index'));
});

Breadcrumbs::for('backend.warehouse.detail', function ($trail) {
    $trail->parent('backend.warehouses.index');
    $trail->push('Chỉnh sửa', '');
});

Breadcrumbs::for('backend.orders.detailTemp', function ($trail) {
    $trail->parent('backend.orders.index');
    $trail->push('Chi tiết', '#');
});
