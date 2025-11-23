<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-gear fa-fw"></i> การตั้งค่าระบบ</h1>
</div>

<div class="row">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-cogs"></i> กำหนดค่ายืม-คืน และค่าปรับ
            </div>
            <div class="card-body">
                <form id="settingsForm">
                    <div class="mb-3">
                        <label class="form-label">จำนวนวันที่ยืมได้สูงสุด (วัน)</label>
                        <input type="number" class="form-control" name="max_loan_days" required min="1">
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">ต่ออายุก่อนหมดอายุ (วัน)</label>
                            <input type="number" class="form-control" name="renew_days_before_due" required min="0">
                        </div>
                        <div class="col">
                            <label class="form-label">ต่ออายุหลังหมดอายุ (วัน)</label>
                            <input type="number" class="form-control" name="renew_days_after_due" required min="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">จำนวนครั้งยืมต่อสูงสุด</label>
                            <input type="number" class="form-control" name="max_renew_count" required min="0">
                        </div>
                        <div class="col">
                            <label class="form-label">รวมวันที่ต่ออายุได้สูงสุด (วัน)</label>
                            <input type="number" class="form-control" name="max_renew_days" required min="0">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-danger fw-bold">ค่าปรับต่อวัน (บาท)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="fine_per_day" required min="0">
                            <span class="input-group-text">บาท</span>
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> บันทึกการตั้งค่า
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // 1. โหลดข้อมูลเมื่อเปิดหน้าเว็บ
        loadSettings();

        function loadSettings() {
            $.ajax({
                url: 'api/settings_api.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data) {
                        let d = response.data;
                        // นำข้อมูลใส่ลง input ตาม name attribute
                        $('input[name="max_loan_days"]').val(d.max_loan_days);
                        $('input[name="renew_days_before_due"]').val(d.renew_days_before_due);
                        $('input[name="renew_days_after_due"]').val(d.renew_days_after_due);
                        $('input[name="max_renew_count"]').val(d.max_renew_count);
                        $('input[name="max_renew_days"]').val(d.max_renew_days);
                        $('input[name="fine_per_day"]').val(d.fine_per_day);
                    } else {
                        Swal.fire('Error', 'ไม่สามารถโหลดข้อมูลได้', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'เชื่อมต่อ Server ไม่ได้', 'error');
                }
            });
        }

        // 2. บันทึกข้อมูล
        $('#settingsForm').on('submit', function(e) {
            e.preventDefault();

            // ตรวจสอบข้อมูลฝั่ง Client (Validation ง่ายๆ)
            let fine = $('input[name="fine_per_day"]').val();
            if (fine < 0) {
                Swal.fire('เตือน', 'ค่าปรับห้ามติดลบ', 'warning');
                return;
            }

            $.ajax({
                url: 'api/settings_api.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'กำลังบันทึก...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('สำเร็จ', response.message, 'success');
                    } else {
                        Swal.fire('ผิดพลาด', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                }
            });
        });
    });
</script>