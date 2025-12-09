<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Mã giảm giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/sidebar.css">
</head>
<body>

<div class="wrapper">
    <?php require_once APPROOT . '/views/Layouts/sidebar.php'; ?>

    <div id="content">
        <nav class="navbar navbar-light bg-white shadow-sm px-3 mb-4">
            <button type="button" id="sidebarCollapse" class="btn btn-primary me-3"><i class="fas fa-bars"></i></button>
            <h4 class="text-primary mb-0 fw-bold">🎟️ QUẢN LÝ MÃ GIẢM GIÁ</h4>
        </nav>

        <div class="container-fluid px-4">
            <div class="row g-4">
                
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold py-3 text-primary">
                            <i class="fas fa-plus-circle me-1"></i> Thông tin mã
                        </div>
                        <div class="card-body">
                             <form id="discountForm" action="<?php echo URLROOT; ?>/discount/add" method="post">
                                    <input type="hidden" name="discount_id" id="discount_id">

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Mã Code (Ví dụ: SALE10)</label>
                                        <input type="text" name="code" id="code" class="form-control text-uppercase" required placeholder="Nhập mã...">
                                        
                                        <?php if(isset($_SESSION['error_discount_code'])): ?>
                                            <div class="text-danger small mt-1 fw-bold">
                                                <i class="fas fa-exclamation-triangle me-1"></i> 
                                                <?php echo $_SESSION['error_discount_code']; unset($_SESSION['error_discount_code']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Loại giảm giá</label>
                                        <select name="type" id="type" class="form-select">
                                            <option value="fixed">Giảm theo tiền mặt (VNĐ)</option>
                                            <option value="percentage">Giảm theo phần trăm (%)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Giá trị giảm</label>
                                        <div class="input-group">
                                            <input type="number" name="value" id="value" class="form-control" required min="1">
                                            <span class="input-group-text" id="value-unit">VNĐ</span>
                                        </div>
                                        <?php if(isset($_SESSION['error_discount_value'])): ?>
                                            <div class="text-danger small mt-1 fw-bold">
                                                <i class="fas fa-exclamation-triangle me-1"></i> 
                                                <?php echo $_SESSION['error_discount_value']; unset($_SESSION['error_discount_value']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3 p-3 bg-light border rounded">
                                        <label class="form-label fw-bold mb-2">Điều kiện áp dụng:</label>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="condition_type" id="cond_none" value="none" checked>
                                            <label class="form-check-label" for="cond_none">
                                                Không có điều kiện (Áp dụng mọi đơn)
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="condition_type" id="cond_min" value="min">
                                            <label class="form-check-label" for="cond_min">
                                                Có điều kiện: Áp dụng cho đơn hàng từ...
                                            </label>
                                        </div>

                                        <div class="mt-2 ps-4" id="box-min-value" style="display: none;">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Tổng tiền ></span>
                                                <input type="number" name="min_order_value" id="min_order_value" class="form-control" placeholder="Nhập số tiền...">
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                            <?php if(isset($_SESSION['error_discount_min'])): ?>
                                                <div class="text-danger small mt-1 fw-bold">
                                                    <?php echo $_SESSION['error_discount_min']; unset($_SESSION['error_discount_min']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mt-3 ps-0" id="box-max-discount" style="display: none;">
                                            <label class="form-label fw-bold text-success small">Giới hạn giảm tối đa (Cho loại %):</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Tối đa</span>
                                                <input type="number" name="max_discount_amount" id="max_discount_amount" class="form-control" placeholder="VD: 50000 (Để trống = Không giới hạn)">
                                                <span class="input-group-text">VNĐ</span>
                                            </div>
                                            <div class="form-text small text-muted fst-italic">Ví dụ: Giảm 10% nhưng tối đa chỉ giảm 50k.</div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-4">
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Thời gian hiệu lực (Tùy chọn):</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white">Từ</span>
                                                <input type="datetime-local" name="start_date" id="start_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-white">Đến</span>
                                                <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-text small text-muted ps-1">Để trống nếu muốn áp dụng vô thời hạn.</div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" onclick="resetDiscountForm()" class="btn btn-light me-md-2">Làm mới</button>
                                        <button type="submit" id="btnSave" class="btn btn-primary fw-bold flex-grow-1">Lưu mã giảm giá</button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="fas fa-list me-1"></i> Danh sách mã hiện có
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Code</th>
                                            <th>Loại</th>
                                            <th>Giá trị</th>
                                            <th class="text-end pe-4">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($data['discounts'])): ?>
                                            <?php foreach($data['discounts'] as $d): ?>
                                            
                                            <tr class="<?php echo ($d->is_deleted == 1) ? 'table-secondary text-muted' : ''; ?>">
                                                <td class="ps-4 fw-bold text-primary">
                                                    <span class="badge bg-light text-primary border border-primary border-dashed px-3 py-2">
                                                        <?php echo htmlspecialchars($d->code); ?>
                                                    </span>
                                                    
                                                    <?php if($d->is_deleted == 1): ?>
                                                        <br><span class="badge bg-danger mt-1">Đã xóa</span>
                                                    <?php else: ?>
                                                        <?php 
                                                            $now = date('Y-m-d H:i:s');
                                                            $isExpired = ($d->end_date && $d->end_date < $now);
                                                            $isUpcoming = ($d->start_date && $d->start_date > $now);
                                                        ?>
                                                        <?php if($isExpired): ?>
                                                            <br><span class="badge bg-secondary mt-1">Đã hết hạn</span>
                                                        <?php elseif($isUpcoming): ?>
                                                            <br><span class="badge bg-warning text-dark mt-1">Chưa diễn ra</span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($d->end_date): ?>
                                                        <div class="text-muted small fw-normal mt-1" style="font-size: 0.75rem;">
                                                            Hạn: <?php echo date('d/m/Y H:i', strtotime($d->end_date)); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <td><?php echo ($d->type == 'fixed') ? 'Tiền mặt' : 'Phần trăm'; ?></td>
                                                
                                                <td class="fw-bold text-success">
                                                    -<?php echo ($d->type == 'fixed') ? number_format($d->value).'đ' : $d->value.'%'; ?>
                                                    
                                                    <?php if($d->min_order_value > 0): ?>
                                                        <br><small class="text-muted fw-normal">Đơn từ <?php echo number_format($d->min_order_value); ?></small>
                                                    <?php endif; ?>

                                                    <?php if($d->type == 'percentage' && $d->max_discount_amount > 0): ?>
                                                        <br><small class="text-danger fw-normal">Tối đa: -<?php echo number_format($d->max_discount_amount); ?>đ</small>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <td class="text-end pe-4">
                                                    <?php if($d->is_deleted == 1): ?>
                                                        <a href="<?php echo URLROOT; ?>/discount/restore/<?php echo $d->discount_id; ?>" 
                                                           class="btn btn-sm btn-success fw-bold btn-restore"> <i class="fas fa-trash-restore"></i> Khôi phục
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-warning border-0 me-1" 
                                                                onclick='editDiscount(<?php echo htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8'); ?>)'>
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <a href="<?php echo URLROOT; ?>/discount/delete/<?php echo $d->discount_id; ?>" 
                                                           class="btn btn-sm btn-outline-danger btn-delete-discount">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['alert_type']) && isset($_SESSION['alert_msg'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?php echo $_SESSION['alert_type']; ?>',
                title: 'Thông báo',
                text: '<?php echo $_SESSION['alert_msg']; ?>',
                confirmButtonColor: '#4e73df',
                confirmButtonText: 'Đã hiểu'
            });
        });
    </script>
    <?php unset($_SESSION['alert_type'], $_SESSION['alert_msg']); ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>const URLROOT = '<?php echo URLROOT; ?>';</script>
<script src="<?php echo URLROOT; ?>/js/discount.js"></script>

</body>
</html>