<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-8">

            <div class="text-center mb-4">
                <h2 class="fw-bold"><i class="fa-solid fa-user-pen me-2"></i>แก้ไขข้อมูลส่วนตัว</h2>
                <p class="text-muted">อัปเดตข้อมูลสมาชิกของคุณ</p>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <form id="profileForm" class="needs-validation" novalidate>
                        <input type="hidden" name="action" value="update_info">

                        <div class="bg-light p-3 rounded-3 mb-4 border">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">รหัสพนักงาน</small>
                                    <span id="disp_employee_id" class="fw-bold text-primary">-</span>
                                </div>
                                <div class="col-6 border-start ps-3">
                                    <small class="text-muted d-block">ชื่อผู้ใช้</small>
                                    <span id="disp_username" class="fw-bold">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="ชื่อจริง" required>
                                    <label for="first_name">ชื่อจริง</label>
                                    <div class="invalid-feedback">กรุณากรอกชื่อจริง</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="นามสกุล" required>
                                    <label for="last_name">นามสกุล</label>
                                    <div class="invalid-feedback">กรุณากรอกนามสกุล</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                                    <label for="email">อีเมล</label>
                                    <div class="invalid-feedback">รูปแบบอีเมลไม่ถูกต้อง</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="tel" name="tel" placeholder="เบอร์โทรศัพท์">
                                    <label for="tel">เบอร์โทรศัพท์</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm rounded-pill">
                                <i class="fas fa-save me-2"></i>บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        const API_URL = 'api/profile_api.php';

        // 1. โหลดข้อมูล
        $.ajax({
            url: API_URL,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success' && res.data) {
                    let d = res.data;
                    // แสดงผลแบบ Text
                    $('#disp_employee_id').text(d.employee_id);
                    $('#disp_username').text(d.username);

                    // ใส่ค่าใน Input
                    $('input[name="first_name"]').val(d.first_name);
                    $('input[name="last_name"]').val(d.last_name);
                    $('input[name="email"]').val(d.email);
                    $('input[name="tel"]').val(d.tel);
                }
            }
        });

        // 2. บันทึกข้อมูล
        $('#profileForm').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = this;

            if (form.checkValidity()) {
                $.ajax({
                    url: API_URL,
                    method: 'POST',
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
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
            $(form).addClass('was-validated');
        });
    });
</script>