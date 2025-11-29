<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-8">

            <div class="text-center mb-4">
                <h2 class="fw-bold"><i class="fa-solid fa-key me-2"></i>เปลี่ยนรหัสผ่าน</h2>
                <p class="text-muted">กำหนดรหัสผ่านใหม่เพื่อความปลอดภัย</p>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <form id="passwordForm" class="needs-validation" novalidate>
                        <input type="hidden" name="action" value="change_password">

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="รหัสผ่านปัจจุบัน" required>
                            <label for="current_password">รหัสผ่านปัจจุบัน</label>
                            <div class="invalid-feedback">กรุณากรอกรหัสผ่านปัจจุบัน</div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="รหัสผ่านใหม่" required minlength="4">
                            <label for="new_password">รหัสผ่านใหม่</label>
                            <div class="invalid-feedback">อย่างน้อย 4 ตัวอักษร</div>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่านใหม่" required minlength="4">
                            <label for="confirm_password">ยืนยันรหัสผ่านใหม่</label>
                            <div class="invalid-feedback">กรุณายืนยันรหัสผ่าน</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg shadow-sm rounded-pill text-white">
                                <i class="fas fa-check-circle me-2"></i>ยืนยันการเปลี่ยน
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
        const API_URL = 'api/public_profile_api.php';

        $('#passwordForm').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = this;

            if (form.checkValidity()) {
                let p1 = $('input[name="new_password"]').val();
                let p2 = $('input[name="confirm_password"]').val();

                if (p1 !== p2) {
                    Swal.fire('แจ้งเตือน', 'รหัสผ่านใหม่ไม่ตรงกัน', 'warning');
                    return;
                }

                $.ajax({
                    url: API_URL,
                    method: 'POST',
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'เปลี่ยนรหัสผ่านสำเร็จ',
                                text: res.message,
                                timer: 2000
                            }).then(() => {
                                form.reset();
                                $(form).removeClass('was-validated');
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