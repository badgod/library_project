<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-user-pen me-2"></i> แก้ไขข้อมูลส่วนตัว</h1>
</div>

<div class="row">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="profileForm">
                    <input type="hidden" name="action" value="update_info">
                    
                    <div class="mb-3">
                        <label class="form-label">ชื่อผู้ใช้ (Username)</label>
                        <input type="text" class="form-control" name="username" disabled readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">รหัสพนักงาน</label>
                        <input type="text" class="form-control" name="employee_id" disabled readonly>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อจริง</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">นามสกุล</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">อีเมล</label>
                        <input type="email" class="form-control" name="email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control" name="tel">
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // 1. โหลดข้อมูลมาแสดง
    $.ajax({
        url: 'api/profile_api.php',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success' && res.data) {
                let d = res.data;
                $('input[name="username"]').val(d.username);
                $('input[name="employee_id"]').val(d.employee_id);
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
        $.ajax({
            url: 'api/profile_api.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire('สำเร็จ', res.message, 'success').then(() => {
                        location.reload(); // รีโหลดเพื่ออัปเดตชื่อบน Navbar
                    });
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }
        });
    });
});
</script>