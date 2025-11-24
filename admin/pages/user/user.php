<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-users-gear"></i> ผู้ใช้งานและสมาชิก</h1>
    <button class="btn btn-primary" onclick="openCreateModal()">
        <i class="fa-solid fa-user-plus"></i> เพิ่มผู้ใช้งานใหม่
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table id="usersTable" class="table table-striped w-100">
            <thead>
                <tr>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>Username</th>
                    <th>สิทธิ์ (Role)</th>
                    <th>สถานะ</th>
                    <th width="20%">จัดการ</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">เพิ่มผู้ใช้งาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" class="needs-validation" novalidate>
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="user_id" id="userId">
                    <input type="hidden" name="member_id" id="memberId">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">รหัสพนักงาน <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="employee_id" id="employeeId" required>
                            <div class="invalid-feedback">กรุณากรอกรหัสพนักงาน</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username (สำหรับ Login) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" id="username" required>
                            <div class="invalid-feedback">กรุณากรอก Username</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อจริง <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="firstName" required>
                            <div class="invalid-feedback">กรุณากรอกชื่อจริง</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="lastName" required>
                            <div class="invalid-feedback">กรุณากรอกนามสกุล</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">อีเมล</label>
                            <input type="email" class="form-control" name="email" id="email">
                            <div class="invalid-feedback">รูปแบบอีเมลไม่ถูกต้อง</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" class="form-control" name="tel" id="tel">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">สิทธิ์การใช้งาน (Role)</label>
                            <select class="form-select" name="role" id="role">
                                <option value="member">Member (สมาชิกทั่วไป)</option>
                                <option value="admin">Admin (ผู้ดูแลระบบ)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">สถานะ (Status)</label>
                            <select class="form-select" name="status" id="status">
                                <option value="active" class="text-success">Active (ใช้งานปกติ)</option>
                                <option value="inactive" class="text-danger">Inactive (ระงับการใช้งาน)</option>
                                <option value="pending" class="text-warning">Pending (รอยืนยัน)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        <div>
                            รหัสผ่านเริ่มต้นสำหรับสมาชิกใหม่คือ <strong>123456</strong>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-2"></i>ยกเลิก</button>
                <button type="submit" form="userForm" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>บันทึกข้อมูล</button>
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
    // Initial DataTable
    table = $('#usersTable').DataTable({
        ajax: 'api/user_api.php',
        columns: [
            { data: 'employee_id' },
            { 
                data: null,
                render: function(data) {
                    return `${data.first_name} ${data.last_name}`;
                }
            },
            { data: 'username' },
            { 
                data: 'role',
                render: function(data) {
                    return data === 'admin' 
                        ? '<span class="badge bg-danger">Admin</span>' 
                        : '<span class="badge bg-primary">Member</span>';
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    if(data === 'active') return '<span class="badge bg-success">Active</span>';
                    if(data === 'inactive') return '<span class="badge bg-secondary">Inactive</span>';
                    return '<span class="badge bg-warning text-dark">Pending</span>';
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    let jsonRow = JSON.stringify(row).replace(/"/g, '&quot;');
                    return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-warning" onclick="editUser(${jsonRow})" title="แก้ไข">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-info text-white" onclick="resetPassword(${row.user_id})" title="รีเซ็ตรหัสผ่าน">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser(${row.user_id}, ${row.member_id})" title="ลบ">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json" }
    });

    // 4. Form Submit Listener with Validation
    $('#userForm').on('submit', function(event) {
        // หยุดการ submit ปกติ
        event.preventDefault();
        event.stopPropagation();

        const form = this;

        // ตรวจสอบว่าฟอร์มถูกต้องหรือไม่
        if (form.checkValidity()) {
            saveUserAjax(); // ถ้าผ่าน ให้ส่ง Ajax
        }

        // เพิ่ม class เพื่อแสดงผล validation (สีเขียว/แดง)
        $(form).addClass('was-validated');
    });
});

function openCreateModal() {
    resetForm(); // ล้างค่าและ Validation เก่า
    
    // เปิดให้กรอกได้
    $('#employeeId').prop('readonly', false);
    $('#username').prop('readonly', false);
    
    $('#modalTitle').text('เพิ่มผู้ใช้งานใหม่');
    $('#userModal').modal('show');
}

function editUser(data) {
    resetForm(); // ล้างค่าและ Validation เก่า

    $('#formAction').val('update');
    $('#userId').val(data.user_id);
    $('#memberId').val(data.member_id);
    
    $('#employeeId').val(data.employee_id).prop('readonly', true);
    $('#username').val(data.username).prop('readonly', true);
    $('#firstName').val(data.first_name);
    $('#lastName').val(data.last_name);
    $('#email').val(data.email);
    $('#tel').val(data.tel);
    $('#role').val(data.role);
    $('#status').val(data.status);
    
    $('#modalTitle').text('แก้ไขข้อมูลผู้ใช้');
    $('#userModal').modal('show');
}

function resetForm() {
    $('#userForm')[0].reset();
    $('#formAction').val('create');
    $('#userId').val('');
    $('#memberId').val('');
    // ล้าง class validation ออก เพื่อไม่ให้ขึ้นสีแดงค้าง
    $('#userForm').removeClass('was-validated');
}

// แยกฟังก์ชัน Ajax ออกมาเพื่อเรียกใช้เมื่อ Validate ผ่าน
function saveUserAjax() {
    $.ajax({
        url: 'api/user_api.php',
        method: 'POST',
        data: $('#userForm').serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                Swal.fire('สำเร็จ', res.message, 'success');
                $('#userModal').modal('hide');
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

function resetPassword(id) {
    Swal.fire({
        title: 'รีเซ็ตรหัสผ่าน?',
        text: "รหัสผ่านจะถูกเปลี่ยนเป็น '123456'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f0ad4e',
        confirmButtonText: 'ยืนยันรีเซ็ต',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api/user_api.php',
                method: 'POST',
                data: { action: 'reset_password', id: id },
                dataType: 'json',
                success: function(res) {
                    if(res.status === 'success') {
                        Swal.fire('เรียบร้อย', res.message, 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        }
    });
}

function deleteUser(uid, mid) {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: "ข้อมูลผู้ใช้และสมาชิกจะถูกลบออกจากระบบ",
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'ลบข้อมูล',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api/user_api.php',
                method: 'POST',
                data: { action: 'delete', user_id: uid, member_id: mid },
                dataType: 'json',
                success: function(res) {
                    if(res.status === 'success') {
                        Swal.fire('ลบสำเร็จ', res.message, 'success');
                        table.ajax.reload();
                    } else {
                        Swal.fire('ลบไม่ได้', res.message, 'error');
                    }
                }
            });
        }
    });
}
</script>