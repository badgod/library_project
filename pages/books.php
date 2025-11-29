<div class="container py-5">

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="d-flex align-items-center mb-3">
                <h2 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-book me-2"></i>หนังสือทั้งหมด</h2>
                <span class="badge bg-secondary ms-3 rounded-pill" id="result-count">0 เล่ม</span>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body p-4">
                    <form id="searchForm" class="row g-3">
                        <div class="col-md-6 col-lg-7">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 rounded-end-pill ps-2"
                                    id="search_query" placeholder="พิมพ์ชื่อหนังสือ, ผู้แต่ง หรือ ISBN...">
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <select class="form-select rounded-pill" id="category_select">
                                <option value="" selected>ทุกหมวดหมู่</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-lg-2">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                                ค้นหา
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="books-container" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4 min-vh-50">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div id="no-result" class="text-center py-5 d-none">
        <img src="assets/images/logo.png" alt="No Result" class="mb-3 opacity-25" style="filter: grayscale(100%); height: 80px;">
        <h4 class="text-muted">ไม่พบหนังสือที่ค้นหา</h4>
        <p class="text-secondary">ลองเปลี่ยนคำค้นหา หรือเลือกหมวดหมู่ใหม่อีกครั้ง</p>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. โหลดรายชื่อหมวดหมู่มาใส่ Dropdown
        $.ajax({
            url: 'api/public_book_api.php',
            data: {
                action: 'get_categories'
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let options = '<option value="" selected>ทุกหมวดหมู่</option>';
                    res.data.forEach(cat => {
                        options += `<option value="${cat.category_id}">${cat.name}</option>`;
                    });
                    $('#category_select').html(options);
                }
            }
        });

        // 2. ฟังก์ชันโหลดหนังสือ
        function loadBooks(query = '', catId = '') {
            // แสดง Loading
            $('#books-container').html(`
            <div class="col-12 text-center py-5 mt-5">
                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
        `);
            $('#no-result').addClass('d-none');

            $.ajax({
                url: 'api/public_book_api.php',
                data: {
                    action: 'search_books',
                    q: query,
                    cat: catId
                },
                dataType: 'json',
                success: function(res) {
                    let html = '';
                    if (res.status === 'success' && res.data.length > 0) {
                        $('#result-count').text(res.data.length + ' เล่ม');

                        res.data.forEach(book => {
                            let imgSrc = book.image ? `assets/images/${book.image}` : 'assets/images/blank_cover_book.jpg';

                            // ตรวจสอบสถานะ E-Book
                            let ebookBadge = '';
                            if (book.has_ebook > 0) {
                                ebookBadge = '<span class="badge bg-success bg-opacity-75 rounded-pill" style="font-size: 0.7rem;"><i class="fas fa-tablet-alt me-1"></i>E-Book</span>';
                            } else {
                                ebookBadge = '<span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem; opacity: 0.3;"><i class="fas fa-tablet-alt me-1"></i>E-Book</span>';
                            }

                            html += `
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
                                        <p class="card-text text-muted small mb-2 text-truncate">
                                            <i class="fas fa-layer-group me-1"></i> ${book.category_name || 'ทั่วไป'}
                                        </p>
                                        <p class="card-text text-secondary small mb-0 text-truncate">
                                            <i class="fas fa-user-edit me-1"></i> ${book.author || '-'}
                                        </p>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 p-3 pt-0">
                                        <a href="book_detail?id=${book.title_id}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">รายละเอียด</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        });

                        $('#books-container').html(html);
                    } else {
                        $('#result-count').text('0 เล่ม');
                        $('#books-container').html('');
                        $('#no-result').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#books-container').html('<div class="col-12 text-center text-danger py-5">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>');
                }
            });
        }

        // 3. เรียกโหลดครั้งแรก (แสดงทั้งหมด)
        loadBooks();

        // 4. ดักจับการกดปุ่มค้นหา
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            let q = $('#search_query').val();
            let c = $('#category_select').val();
            loadBooks(q, c);
        });
    });
</script>

<style>
    /* CSS เดียวกับหน้า Home เพื่อความต่อเนื่อง */
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