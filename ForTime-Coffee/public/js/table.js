/**
 * TABLE MANAGEMENT JAVASCRIPT
 * File: public/js/table.js
 * -------------------------------------------------------------------------
 * - Chức năng: Quản lý sơ đồ bàn (Thêm, Sửa, Xóa, Khôi phục).
 * - Xử lý thông báo (Flash Message) tách biệt khỏi View.
 * -------------------------------------------------------------------------
 */

document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    initSearch();
    initTableInteractions();
    initDeleteAction();
    initRestoreAction(); // [MỚI] Khởi tạo chức năng khôi phục
    initFlashMessage();  // [MỚI] Tự động hiển thị thông báo nếu có
});

// ============================================================
// 1. INITIALIZATION MODULES
// ============================================================

/**
 * Module: Toggle Sidebar
 */
function initSidebar() {
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    if(sidebarCollapse && sidebar) {
        sidebarCollapse.addEventListener('click', () => sidebar.classList.toggle('active'));
    }
}

/**
 * Module: Tìm kiếm nhanh
 */
function initSearch() {
    const searchInput = document.getElementById('searchTable');
    const tableBody = document.getElementById('tableTableBody');
    if(searchInput && tableBody) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = tableBody.getElementsByTagName('tr');
            Array.from(rows).forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
}

/**
 * Module: Xử lý click dòng (Chọn bàn để sửa)
 */
function initTableInteractions() {
    const tableBody = document.getElementById('tableTableBody');
    if(tableBody) {
        tableBody.addEventListener('click', function(e) {
            const row = e.target.closest('.clickable-row');
            
            // [LOGIC QUAN TRỌNG]
            // Không chọn dòng nếu: 
            // 1. Click trúng nút khôi phục (.btn-restore)
            // 2. Dòng này là dòng đã xóa (.table-secondary)
            if (!row || e.target.closest('.btn-restore') || row.classList.contains('table-secondary')) {
                return; 
            }
            
            if (row) {
                const allRows = tableBody.querySelectorAll('.clickable-row');
                allRows.forEach(r => r.classList.remove('table-active'));
                row.classList.add('table-active');

                try {
                    const jsonData = row.getAttribute('data-json');
                    if (jsonData) {
                        const tableData = JSON.parse(jsonData);
                        editTable(tableData);
                    }
                } catch (error) {
                    console.error('Lỗi dữ liệu bàn:', error);
                }
            }
        });
    }
}

/**
 * Module: Xử lý nút Xóa
 */
function initDeleteAction() {
    const btnDelete = document.getElementById('btnDelete');
    if(btnDelete) {
        btnDelete.addEventListener('click', function() {
            const id = document.getElementById('table_id').value;
            const name = document.getElementById('table_name').value;
            
            if(!id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Chưa chọn bàn!',
                    text: 'Vui lòng chọn một bàn để xóa.',
                    confirmButtonColor: '#f6c23e',
                    confirmButtonText: 'Đã hiểu'
                });
                return;
            }

            Swal.fire({
                title: 'XÁC NHẬN XÓA?',
                html: `<div class="text-start fs-6">
                        <p>Bàn <b>${name}</b> sẽ bị ẩn khỏi sơ đồ.</p>
                        <p class="mb-0 text-muted fst-italic small">Lưu ý: Lịch sử đơn hàng cũ vẫn được giữ nguyên.</p>
                       </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Xóa ngay',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${URLROOT}/table/delete/${id}`;
                }
            });
        });
    }
}

/**
 * [MỚI] Module: Xử lý nút Khôi phục
 */
function initRestoreAction() {
    const tableBody = document.getElementById('tableTableBody');
    if(!tableBody) return;

    tableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-restore');
        if (btn) {
            e.preventDefault();
            const url = btn.getAttribute('href');

            Swal.fire({
                title: 'Khôi phục bàn này?',
                text: "Bàn sẽ được kích hoạt trở lại (Trạng thái: Trống).",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    });
}

/**
 * [MỚI] Module: Đọc và hiển thị Flash Message từ thẻ ẩn
 */
function initFlashMessage() {
    const flashDiv = document.getElementById('flash-message');
    if (flashDiv) {
        const msgType = flashDiv.getAttribute('data-type'); // success / error
        const msgText = flashDiv.getAttribute('data-text');

        if (msgType && msgText) {
            Swal.fire({
                icon: msgType,
                title: 'Thông báo',
                text: msgText,
                confirmButtonColor: '#1cc88a',
                confirmButtonText: 'Đã hiểu',
                timer: 3000
            });
        }
    }
}

// ============================================================
// 2. GLOBAL FUNCTIONS
// ============================================================

function editTable(table) {
    document.getElementById('table_id').value = table.table_id;
    document.getElementById('table_name').value = table.table_name;
    
    const form = document.getElementById('tableForm');
    form.action = `${URLROOT}/table/edit/${table.table_id}`;
    
    const btnSave = document.getElementById('btnSave');
    btnSave.innerHTML = '<i class="fas fa-sync-alt"></i> Cập nhật';
    btnSave.className = 'btn btn-warning text-white';
    
    document.getElementById('btnDelete').classList.remove('d-none');
}

function resetTableForm() {
    document.getElementById('tableForm').reset();
    document.getElementById('table_id').value = '';
    document.getElementById('tableForm').action = `${URLROOT}/table/add`;
    
    const activeRows = document.querySelectorAll('.table-active');
    activeRows.forEach(r => r.classList.remove('table-active'));
    
    const btnSave = document.getElementById('btnSave');
    btnSave.innerHTML = '<i class="fas fa-save"></i> Lưu lại';
    btnSave.className = 'btn btn-primary';
    
    document.getElementById('btnDelete').classList.add('d-none');
}