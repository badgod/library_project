<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-list"></i> หมวดหมู่หนังสือ</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="resetForm()">
        <i class="fa-solid fa-plus"></i> เพิ่มหมวดหมู่
    </button>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <table id="categoryTable" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="25%">ชื่อหมวดหมู่</th>
                            <th>คำอธิบาย</th>
                            <th width="15%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">เพิ่มหมวดหมู่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="category_id" id="categoryId">

                    <div class="mb-3">
                        <label class="form-label">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="categoryName" required placeholder="เช่น เทคโนโลยี">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">คำอธิบาย</label>
                        <textarea class="form-control" name="description" id="categoryDesc" rows="3" placeholder="รายละเอียดเพิ่มเติม..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" onclick="saveCategory()">บันทึก</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let table;

    $(document).ready(function() {
        table = $('#categoryTable').DataTable({
            ajax: 'api/category_api.php',
            columns: [{
                    data: 'category_id'
                },
                {
                    data: 'name'
                }, // แก้ไขให้ตรงกับฟิลด์ใน DB
                {
                    data: 'description'
                }, // เพิ่มคอลัมน์ description
                {
                    data: null,
                    render: function(data, type, row) {
                        // ส่งข้อมูล description ไปที่ฟังก์ชัน editCategory ด้วย (ต้อง escape string กัน error)
                        // ใช้ replace เพื่อจัดการกับเครื่องหมาย ' หรือ " ในข้อความที่อาจทำให้ JS พัง
                        let safeName = row.name ? row.name.replace(/'/g, "\\'") : "";
                        let safeDesc = row.description ? row.description.replace(/'/g, "\\'").replace(/\n/g, "\\n") : "";

                        return `
                        <button class="btn btn-warning btn-sm me-1" onclick="editCategory(${row.category_id}, '${safeName}', '${safeDesc}')">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteCategory(${row.category_id})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `;
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });
    });

    function resetForm() {
        $('#categoryForm')[0].reset();
        $('#formAction').val('create');
        $('#categoryId').val('');
        $('#modalTitle').text('เพิ่มหมวดหมู่');
    }

    function saveCategory() {
        if (!$('#categoryName').val()) {
            Swal.fire('แจ้งเตือน', 'กรุณากรอกชื่อหมวดหมู่', 'warning');
            return;
        }

        $.ajax({
            url: 'api/category_api.php',
            method: 'POST',
            data: $('#categoryForm').serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire('สำเร็จ', res.message, 'success');
                    $('#categoryModal').modal('hide');
                    table.ajax.reload();
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }
        });
    }

    // รับ parameter description เพิ่ม
    function editCategory(id, name, desc) {
        $('#formAction').val('update');
        $('#categoryId').val(id);
        $('#categoryName').val(name);
        $('#categoryDesc').val(desc); // ใส่ค่า description ลงในฟอร์ม
        $('#modalTitle').text('แก้ไขหมวดหมู่');
        $('#categoryModal').modal('show');
    }

    function deleteCategory(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลที่ลบจะไม่สามารถกู้คืนได้",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/category_api.php',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('ลบสำเร็จ!', res.message, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('ไม่สามารถลบได้', res.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>