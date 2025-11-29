<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <p class="text-center mb-4">
                        <img src="assets/images/logo.png" alt="Logo">
                    </p>
                    <h3 class="text-center mb-4">เข้าสู่ระบบ</h3>
                    <form id="loginForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-user"></i> ชื่อผู้ใช้</label>
                            <input type="text" name="username" class="form-control" placeholder="ชื่อผู้ใช้" required>
                            <div class="invalid-feedback">กรุณากรอกชื่อผู้ใช้</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-lock"></i> รหัสผ่าน</label>
                            <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
                            <div class="invalid-feedback">กรุณากรอกรหัสผ่าน</div>
                        </div>
                        <button type="submit" id="btnLogin" class="btn btn-primary w-100 rounded-pill"><i class="fa-solid fa-right-to-bracket"></i> เข้าสู่ระบบ</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault(); // ป้องกันการ Refresh หน้าจอ
            e.stopPropagation();

            // 2. ตรวจสอบความถูกต้องของฟอร์ม (Validation)
            const form = this;
            if (!form.checkValidity()) {
                $(form).addClass('was-validated'); // แสดงสีแดง/เขียว ตามสถานะ
                return; // หยุดการทำงานถ้าข้อมูลไม่ครบ
            }

            // ถ้าข้อมูลครบแล้ว ให้แสดงว่าผ่านการตรวจสอบ
            $(form).addClass('was-validated');

            // --- ส่วนการส่ง AJAX (Code เดิม) ---
            const btn = $('#btnLogin');
            const originalText = btn.text();

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังตรวจสอบ...');

            $.ajax({
                url: 'api/auth_api.php',
                type: 'POST',
                data: $(this).serialize() + '&mode=signin',
                dataType: 'json',
                success: function(response) {
                    btn.prop('disabled', false).text(originalText);

                    if (response.status === 'success') {
                        // เช็คเงื่อนไขรหัสผ่านเริ่มต้น (ถ้ามีส่งมาจาก API)
                        if (response.change_password == 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'แจ้งเตือนความปลอดภัย',
                                text: 'คุณยังใช้รหัสผ่านเริ่มต้นอยู่ กรุณาเปลี่ยนรหัสผ่าน',
                                showCancelButton: true,
                                confirmButtonText: 'เปลี่ยนรหัสผ่าน',
                                cancelButtonText: 'ไว้ทีหลัง'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'change_password';
                                } else {
                                    window.location.href = 'index'; // ไปหน้าแรก
                                }
                            });
                        } else {
                            // Login สำเร็จปกติ
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = 'index'; // ไปหน้าแรก
                            });
                        }
                    } else {
                        // Login ไม่สำเร็จ
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์'
                    });
                    btn.prop('disabled', false).text(originalText);
                }
            });
        });
    });
</script>