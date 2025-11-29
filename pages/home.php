<div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true"
            aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">

        <div class="carousel-item active">
            <img src="assets/images/slide_1.jpg" class="d-block w-100" alt="ภาพสไลด์ต้อนรับห้องสมุด">
            <div class="container">
                <div class="carousel-caption text-start">
                    <h1>ยินดีต้อนรับสู่ระบบจัดการห้องสมุด</h1>
                    <p class="opacity-75">
                        ค้นหาหนังสือและทรัพยากรดิจิทัลทั้งหมดของหน่วยงานคุณ
                    </p>
                    <p>
                        <a class="btn btn-lg btn-primary rounded-pill" href="signin">สมัครสมาชิกวันนี้</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <img src="assets/images/slide_2.jpg" class="d-block w-100" alt="ภาพสไลด์ E-Book">
            <div class="container">
                <div class="carousel-caption">
                    <h1>เข้าถึง E-Book ได้ทุกที่ทุกเวลา</h1>
                    <p>
                        สำหรับสมาชิก: อ่านหนังสืออิเล็กทรอนิกส์ได้ทันทีโดยไม่เสียเวลาจัดส่ง
                    </p>
                    <p><a class="btn btn-lg btn-primary rounded-pill" href="signin">เข้าสู่ระบบเพื่ออ่าน</a></p>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <img src="assets/images/slide_3.jpg" class="d-block w-100" alt="ภาพสไลด์การจัดส่ง">
            <div class="container">
                <div class="carousel-caption text-end">
                    <h1>บริการตลอด 24 ชั่วโมง</h1>
                    <p>
                        ตรวจสอบสถานะการยืมหนังสือและจัดการการจองของคุณได้อย่างง่ายดาย
                    </p>
                    <p>
                        <a class="btn btn-lg btn-primary rounded-pill" href="history">ดูประวัติการยืม</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<div class="container marketing">

    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
            <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-book-open me-2"></i>หนังสือมาใหม่ <span class="text-muted fs-6 fw-normal">(New Arrivals)</span></h4>
            <a href="books" class="btn btn-outline-primary btn-sm rounded-pill px-3">ดูทั้งหมด <i class="fas fa-arrow-right"></i></a>
        </div>

        <div id="new-arrivals-list" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
            <h4 class="fw-bold text-danger mb-0"><i class="fa-solid fa-fire me-2"></i>หนังสือยอดนิยม <span class="text-muted fs-6 fw-normal">(Popular Books)</span></h4>
            <a href="books" class="btn btn-outline-danger btn-sm rounded-pill px-3">ดูทั้งหมด <i class="fas fa-arrow-right"></i></a>
        </div>

        <div id="popular-books-list" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {
        function createBookCard(book) {
            let imgSrc = book.image ? `assets/images/${book.image}` : 'assets/images/blank_cover_book.jpg';

            // ตรวจสอบสถานะ E-Book
            let ebookBadge = '';
            if (book.has_ebook > 0) {
                // มี E-book: สีเขียวชัดเจน
                ebookBadge = '<span class="badge bg-success bg-opacity-75 rounded-pill" style="font-size: 0.7rem;"><i class="fas fa-tablet-alt me-1"></i>E-Book</span>';
            } else {
                // ไม่มี: สีเทาและจางลง (opacity)
                ebookBadge = '<span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem; opacity: 0.3;"><i class="fas fa-tablet-alt me-1"></i>E-Book</span>';
            }

            return `
            <div class="col">
                <div class="card h-100 shadow-sm border-0 book-card-hover">
                    <div class="position-relative overflow-hidden" style="height: 260px;">
                        <img src="${imgSrc}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="${book.title}">
                        
                        <div class="position-absolute top-0 end-0 m-2">
                            ${ebookBadge}
                        </div>

                        <div class="book-overlay d-flex justify-content-center align-items-center">
                            <a href="book_detail?id=${book.title_id}" class="btn btn-light rounded-circle shadow m-1" title="ดูรายละเอียด"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold text-truncate mb-1" title="${book.title}">${book.title}</h6>
                        <p class="card-text text-muted small mb-2"><i class="fas fa-user-edit me-1"></i> ${book.author || '-'}</p>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-3 pt-0">
                        <a href="book_detail?id=${book.title_id}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">รายละเอียด</a>
                    </div>
                </div>
            </div>
        `;
        }

        // 1. โหลดหนังสือมาใหม่
        $.ajax({
            url: 'api/book_api.php',
            data: {
                action: 'new_arrivals'
            },
            dataType: 'json',
            success: function(res) {
                let html = '';
                if (res.status === 'success' && res.data.length > 0) {
                    res.data.forEach(book => {
                        html += createBookCard(book);
                    });
                } else {
                    html = '<div class="col-12 text-center text-muted">ไม่พบข้อมูลหนังสือ</div>';
                }
                $('#new-arrivals-list').html(html);
            }
        });

        // 2. โหลดหนังสือยอดนิยม
        $.ajax({
            url: 'api/book_api.php',
            data: {
                action: 'popular_books'
            },
            dataType: 'json',
            success: function(res) {
                let html = '';
                if (res.status === 'success' && res.data.length > 0) {
                    res.data.forEach(book => {
                        html += createBookCard(book);
                    });
                } else {
                    html = '<div class="col-12 text-center text-muted">ไม่พบข้อมูลหนังสือ</div>';
                }
                $('#popular-books-list').html(html);
            }
        });
    });
</script>

<style>
    /* CSS Effect */
    .book-card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .book-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .book-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .book-card-hover:hover .book-overlay {
        opacity: 1;
    }
</style>