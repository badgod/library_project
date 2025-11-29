<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa-solid fa-basket-shopping me-2"></i>ตะกร้าหนังสือรอการจอง</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info text-center py-5 shadow-sm rounded-4">
            <h4><i class="fas fa-info-circle me-2"></i>ไม่มีรายการหนังสือในตะกร้า</h4>
            <a href="books" class="btn btn-primary mt-3 rounded-pill px-4">ไปเลือกหนังสือ</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body p-0 overflow-hidden rounded-4">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ชื่อเรื่อง</th>
                                    <th class="text-center" width="120">จำนวน</th>
                                    <th class="text-center" width="120">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <h5 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($item['title']) ?></h5>
                                        </td>
                                        <td class="text-center"><span class="badge bg-secondary rounded-pill">1 เล่ม</span></td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-6 btn-remove-item"
                                                data-id="<?= $id ?>"
                                                data-title="<?= htmlspecialchars($item['title'], ENT_QUOTES) ?>">
                                                <i class="fas fa-trash"></i> ลบหนังสือ
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white rounded-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">สรุปรายการ</h4>
                        <div class="d-flex justify-content-between mb-3 fs-5">
                            <span>จำนวนหนังสือ:</span>
                            <span class="fw-bold text-primary"><?= count($_SESSION['cart']) ?> เล่ม</span>
                        </div>
                        <div class="alert alert-warning fs-6 rounded-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            เมื่อกดยืนยัน ระบบจะทำการจองหนังสือให้ท่าน กรุณาไปรับหนังสือที่ห้องสมุดภายใน 3 วัน
                        </div>
                        <button id="btn-confirm" class="btn btn-success w-100 py-2 rounded-pill shadow-sm mb-2 fs-5">
                            <i class="fas fa-check-circle me-2"></i>ยืนยันการจอง
                        </button>
                        <button id="btn-clear" class="btn btn-outline-secondary w-100 rounded-pill">
                            ล้างรายการทั้งหมด
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {

        // 1. ฟังก์ชันลบรายการ (ใช้ Event Listener)
        $('.btn-remove-item').click(function() {
            let id = $(this).data('id');
            let title = $(this).data('title');

            Swal.fire({
                title: 'ลบรายการ?',
                text: `ต้องการลบ "${title}" ออกจากตะกร้าใช่ไหม`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // แสดง Loading
                    Swal.fire({
                        title: 'กำลังลบ...',
                        didOpen: () => Swal.showLoading()
                    });

                    $.post('api/cart_api.php', {
                            action: 'remove',
                            id: id
                        }, function(res) {
                            if (res.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบเรียบร้อย',
                                    timer: 1000,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('แจ้งเตือน', res.message || 'ลบไม่สำเร็จ', 'error');
                            }
                        }, 'json')
                        .fail(function(xhr) {
                            Swal.fire('Error', 'ไม่สามารถเชื่อมต่อกับ Server ได้ (Code: ' + xhr.status + ')', 'error');
                        });
                }
            });
        });

        // 2. ฟังก์ชันล้างตะกร้าทั้งหมด
        $('#btn-clear').click(function() {
            Swal.fire({
                title: 'ล้างตะกร้าทั้งหมด?',
                text: "คุณต้องการลบรายการทั้งหมดใช่หรือไม่",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('api/cart_api.php', {
                        action: 'clear'
                    }, function(res) {
                        if (res.status === 'success') location.reload();
                    }, 'json');
                }
            });
        });

        // 3. ฟังก์ชันยืนยันการจอง
        $('#btn-confirm').click(function() {
            Swal.fire({
                title: 'ยืนยันการจองหนังสือ?',
                text: "กรุณาตรวจสอบรายการก่อนยืนยัน",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'ยืนยันการจอง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'กำลังดำเนินการ...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.post('api/booking_api.php', {
                            action: 'confirm'
                        }, function(res) {
                            if (res.status === 'success') {
                                Swal.fire({
                                    title: 'จองสำเร็จ!',
                                    text: 'กรุณาติดต่อรับหนังสือที่ห้องสมุดภายใน 3 วัน',
                                    icon: 'success',
                                    confirmButtonText: 'ตกลง'
                                }).then(() => {
                                    window.location.href = 'history'; // ไปหน้าประวัติ
                                });
                            } else {
                                Swal.fire('เกิดข้อผิดพลาด', res.message, 'error');
                            }
                        }, 'json')
                        .fail(function(xhr) {
                            Swal.fire('Error', 'Server Error: ' + xhr.responseText, 'error');
                        });
                }
            });
        });

    });
</script>