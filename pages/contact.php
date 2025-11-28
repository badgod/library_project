<div class="container py-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold"><i class="fa-solid fa-headset me-2 text-primary"></i>ติดต่อเรา</h2>
        <p class="text-muted">หากมีข้อสงสัยหรือต้องการความช่วยเหลือ สามารถติดต่อเราได้ผ่านช่องทางด้านล่าง</p>
    </div>

    <div class="row g-5">

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">ข้อมูลติดต่อ</h4>

                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-white p-3 rounded-circle text-primary shadow-sm">
                                <i class="fa-solid fa-location-dot fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold mb-1">ที่อยู่</h6>
                            <p class="text-muted small mb-0">
                                123 อาคารบรรณสาร ถนนความรู้ <br>
                                แขวงหนังสือ เขตปัญญา กรุงเทพฯ 10XXX
                            </p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-white p-3 rounded-circle text-success shadow-sm">
                                <i class="fa-solid fa-phone fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold mb-1">เบอร์โทรศัพท์</h6>
                            <p class="text-muted small mb-0">02-123-4567 (เวลาทำการ)</p>
                            <p class="text-muted small mb-0">089-999-9999 (สายด่วน)</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-white p-3 rounded-circle text-danger shadow-sm">
                                <i class="fa-solid fa-envelope fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold mb-1">อีเมล</h6>
                            <p class="text-muted small mb-0">support@library.com</p>
                            <p class="text-muted small mb-0">admin@library.com</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">แผนที่</h6>
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.6416624898236!2d100.538183!3d13.7445778!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29ed800000001%3A0x2d189c7d0d625516!2sBangkok!5e0!3m2!1sen!2sth!4v1620000000000!5m2!1sen!2sth"
                            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4">ส่งข้อความถึงเรา</h4>

                    <form id="contactForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" placeholder="ชื่อ-นามสกุล" required>
                                    <label for="name">ชื่อ-นามสกุล</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" placeholder="อีเมล" required>
                                    <label for="email">อีเมลติดต่อกลับ</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" placeholder="หัวข้อเรื่อง" required>
                                    <label for="subject">หัวข้อเรื่อง</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="ข้อความ" id="message" style="height: 150px" required></textarea>
                                    <label for="message">รายละเอียด / ข้อความ</label>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100 shadow-sm">
                                    <i class="fas fa-paper-plane me-2"></i>ส่งข้อความ
                                </button>
                            </div>
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
    // Script จำลองการส่งข้อความ (เพราะยังไม่มี Backend รองรับส่วนนี้)
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'success',
            title: 'ส่งข้อความสำเร็จ',
            text: 'เจ้าหน้าที่จะติดต่อกลับโดยเร็วที่สุดครับ',
            confirmButtonColor: '#0d6efd'
        }).then(() => {
            this.reset();
        });
    });
</script>