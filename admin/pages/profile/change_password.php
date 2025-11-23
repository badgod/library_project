<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-key me-2"></i> เปลี่ยนรหัสผ่าน</h1>
</div>

<div class="row">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="passwordForm">
                    <input type="hidden" name="action" value="change_password">

                    <div class="mb-3">
                        <label class="form-label">รหัสผ่านปัจจุบัน</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">รหัสผ่านใหม่</label>
                        <input type="password" class="form-control" name="new_password" required minlength="4">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" class="form-control" name="confirm_password" required minlength="4">
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
            e.preventDefault();

            let p1 = $('input[name="new_password"]').val();
            let p2 = $('input[name="confirm_password"]').val();

            if (p1 !== p2) {
                Swal.fire('แจ้งเตือน', 'รหัสผ่านใหม่ไม่ตรงกัน', 'warning');
                return;
            }

            $.ajax({
                url: 'api/profile_api.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('สำเร็จ', res.message, 'success').then(() => {
                            $('#passwordForm')[0].reset();
                        });
                    } else {
                        Swal.fire('ผิดพลาด', res.message, 'error');
                    }
                }
            });
        });
    });
</script>