<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2" id="pageTitle"><i class="fa-solid fa-user-plus"></i> เพิ่มผู้ใช้งานใหม่</h1>
    <a href="user" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
    </a>
</div>

<div class="row">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="userForm" class="needs-validation" novalidate>
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="user_id" id="userId">
                    <input type="hidden" name="member_id" id="memberId">

                    <h5 class="text-primary mb-3 border-bottom pb-2">ข้อมูลบัญชีผู้ใช้ (Account)</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">รหัสพนักงาน <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="employee_id" id="employeeId" required>
                            <div class="invalid-feedback">กรุณากรอกรหัสพนักงาน</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" id="username" required>
                            <div class="invalid-feedback">กรุณากรอก Username</div>
                        </div>
                    </div>

                    <h5 class="text-primary mb-3 border-bottom pb-2 mt-4">ข้อมูลส่วนตัว (Profile)</h5>
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

                    <h5 class="text-primary mb-3 border-bottom pb-2 mt-4">การตั้งค่า (Settings)</h5>
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
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center mt-4" role="alert">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        <div>
                            กรณีเพิ่มสมาชิกใหม่ รหัสผ่านเริ่มต้นคือ <strong>123456</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-save me-2"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<p>&nbsp;</p>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // ตรวจสอบว่าเป็นการ แก้ไข หรือ เพิ่มใหม่ จาก URL Parameter
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        if (id) {
            // โหมดแก้ไข
            $('#pageTitle').html('<i class="fa-solid fa-user-pen"></i> แก้ไขข้อมูลผู้ใช้งาน');
            $('#formAction').val('update');
            loadUserData(id);
        }

        // Submit Form
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const form = this;
            if (form.checkValidity()) {
                saveData();
            }
            $(form).addClass('was-validated');
        });
    });

    function loadUserData(id) {
        $.ajax({
            url: 'api/user_api.php',
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#userId').val(d.user_id);
                    $('#memberId').val(d.member_id);

                    // ล็อกฟิลด์ที่ไม่ควรแก้
                    $('#employeeId').val(d.employee_id).prop('readonly', true);
                    $('#employeeId').val(d.employee_id).prop('disabled', true);
                    $('#username').val(d.username).prop('readonly', true);
                    $('#username').val(d.username).prop('disabled', true);

                    $('#firstName').val(d.first_name);
                    $('#lastName').val(d.last_name);
                    $('#email').val(d.email);
                    $('#tel').val(d.tel);
                    $('#role').val(d.role);
                    $('#status').val(d.status);
                } else {
                    Swal.fire('Error', 'ไม่พบข้อมูลผู้ใช้งาน', 'error').then(() => {
                        window.location.href = 'index.php?route=users';
                    });
                }
            }
        });
    }

    function saveData() {
        $.ajax({
            url: 'api/user_api.php',
            method: 'POST',
            data: $('#userForm').serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'user';
                    });
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            }
        });
    }
</script>