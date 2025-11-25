<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-key me-2"></i>เปลี่ยนรหัสผ่าน</h1>
</div>

<div class="row">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="passwordForm" class="needs-validation" novalidate>
                    <input type="hidden" name="action" value="change_password">

                    <div class="mb-3">
                        <label class="form-label">รหัสผ่านปัจจุบัน</label>
                        <input type="password" class="form-control" name="current_password" required>
                        <div class="invalid-feedback">กรุณากรอกรหัสผ่านปัจจุบัน</div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">รหัสผ่านใหม่</label>
                        <input type="password" class="form-control" name="new_password" required minlength="4">
                        <div class="invalid-feedback">กรุณากรอกรหัสผ่านใหม่ (อย่างน้อย 4 ตัวอักษร)</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" class="form-control" name="confirm_password" required minlength="4">
                        <div class="invalid-feedback">กรุณายืนยันรหัสผ่านใหม่</div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-key"></i> ยืนยันเปลี่ยนรหัสผ่าน
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#passwordForm').on('submit', function(e) {
            // หยุดการทำงานปกติ
            e.preventDefault();
            e.stopPropagation();

            const form = this;

            // 3. ตรวจสอบความถูกต้องของฟอร์ม (HTML5 Validation)
            if (form.checkValidity()) {
                
                // ตรวจสอบเพิ่มเติม: รหัสผ่านตรงกันไหม
                let p1 = $('input[name="new_password"]').val();
                let p2 = $('input[name="confirm_password"]').val();

                if (p1 !== p2) {
                    Swal.fire('แจ้งเตือน', 'รหัสผ่านใหม่ไม่ตรงกัน', 'warning');
                    // ลบ class เพื่อให้ผู้ใช้กด submit ใหม่ได้โดยไม่ติดขัด (หรือจะปล่อยไว้ก็ได้)
                } else {
                    // ข้อมูลถูกต้องครบถ้วน -> ส่ง Ajax
                    $.ajax({
                        url: 'api/profile_api.php',
                        method: 'POST',
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                Swal.fire('สำเร็จ', res.message, 'success').then(() => {
                                    $('#passwordForm')[0].reset();
                                    $('#passwordForm').removeClass('was-validated'); // ล้างสีเขียวออก
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
            }

            // เพิ่ม class เพื่อแสดงสีเขียว/แดง
            $(form).addClass('was-validated');
        });
    });
</script>