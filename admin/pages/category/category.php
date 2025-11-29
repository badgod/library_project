<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-tags"></i> หมวดหมู่หนังสือ</h1>
    <button class="btn btn-primary" onclick="openCreateModal()">
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
                <form id="categoryForm" class="needs-validation" novalidate>
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="category_id" id="categoryId">

                    <div class="mb-3">
                        <label class="form-label">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="categoryName" required placeholder="เช่น เทคโนโลยี">
                        <div class="invalid-feedback">
                            กรุณากรอกชื่อหมวดหมู่
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">คำอธิบาย</label>
                        <textarea class="form-control" name="description" id="categoryDesc" rows="3" placeholder="รายละเอียดเพิ่มเติม..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <i class="fa-solid fa-xmark me-2"></i>ปิด</button>
                <button type="submit" form="categoryForm" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>บันทึก</button>
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
        // โหลดตาราง
        table = $('#categoryTable').DataTable({
            ajax: 'api/category_api.php',
            columns: [{
                    data: 'category_id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'description'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let safeName = row.name ? row.name.replace(/'/g, "\\'") : "";
                        let safeDesc = row.description ? row.description.replace(/'/g, "\\'").replace(/\n/g, "\\n") : "";

                        return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-warning btn-sm" onclick="editCategory(${row.category_id}, '${safeName}', '${safeDesc}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteCategory(${row.category_id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });

        // 5. Logic การตรวจสอบฟอร์มของ Bootstrap 5
        // ดักจับ Event Submit ของ Form
        $('#categoryForm').on('submit', function(event) {
            // หยุดการทำงานปกติ (ไม่ให้ Refresh หน้า)
            event.preventDefault();
            event.stopPropagation();

            const form = this;

            // ตรวจสอบความถูกต้อง (Validity)
            if (form.checkValidity()) {
                // ถ้าข้อมูลถูกต้อง -> ส่ง Ajax
                saveCategoryAjax();
            }

            // เพิ่ม class 'was-validated' เพื่อให้ Bootstrap แสดงสีเขียว/แดง
            $(form).addClass('was-validated');
        });
    });

    function openCreateModal() {
        resetForm();
        $('#modalTitle').text('เพิ่มหมวดหมู่');
        $('#categoryModal').modal('show');
    }

    function resetForm() {
        $('#categoryForm')[0].reset();
        $('#formAction').val('create');
        $('#categoryId').val('');

        // ล้างสถานะ Validation (สีเขียว/แดง) ออก
        $('#categoryForm').removeClass('was-validated');
    }

    // แยกฟังก์ชัน Ajax ออกมา
    function saveCategoryAjax() {
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
            },
            error: function(err) {
                console.error(err);
                Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            }
        });
    }

    function editCategory(id, name, desc) {
        resetForm(); // ล้างค่าเก่าและ validation class ก่อน

        $('#formAction').val('update');
        $('#categoryId').val(id);
        $('#categoryName').val(name);
        $('#categoryDesc').val(desc);

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