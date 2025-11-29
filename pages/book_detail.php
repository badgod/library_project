<div class="container py-5">
    <div id="book-content" class="row">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // ดึง ID จาก URL Parameter
        const urlParams = new URLSearchParams(window.location.search);
        const bookId = urlParams.get('id');

        if (bookId) {
            loadBookDetail(bookId);
        } else {
            $('#book-content').html('<div class="col-12 text-center text-danger">ไม่พบรหัสหนังสือ</div>');
        }

        function loadBookDetail(id) {
            $.ajax({
                url: 'api/public_book_api.php',
                data: {
                    action: 'book_detail',
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success' && res.data) {
                        renderBook(res.data);
                    } else {
                        $('#book-content').html('<div class="col-12 text-center">ไม่พบข้อมูลหนังสือ</div>');
                    }
                },
                error: function() {
                    $('#book-content').html('<div class="col-12 text-center text-danger">เกิดข้อผิดพลาดในการเชื่อมต่อ</div>');
                }
            });
        }

        function renderBook(book) {
            let imgSrc = book.image ? `assets/images/${book.image}` : 'assets/images/blank_cover_book.jpg';
            let statusBadge = '';

            // สถานะตัวเล่ม
            if (parseInt(book.copies_available) > 0) {
                statusBadge = `<span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>ว่าง (${book.copies_available} เล่ม)</span>`;
            } else {
                statusBadge = `<span class="badge bg-secondary fs-6"><i class="fas fa-times-circle me-1"></i>ไม่ว่าง</span>`;
            }

            // สถานะ E-book
            let ebookBadge = '';
            if (book.has_ebook > 0) {
                ebookBadge = `<span class="badge bg-success bg-opacity-75 fs-6 ms-2"><i class="fas fa-tablet-alt me-1"></i>มี E-Book</span>`;
            } else {
                ebookBadge = `<span class="badge bg-secondary fs-6 ms-2" style="opacity: 0.3;"><i class="fas fa-tablet-alt me-1"></i>ไม่มี E-Book</span>`;
            }

            let html = `
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <img src="${imgSrc}" class="card-img-top" alt="${book.title}">
                </div>
            </div>
            <div class="col-md-8">
                <h2 class="fw-bold mb-3">${book.title}</h2>
                <div class="mb-3">
                    ${statusBadge}
                    ${ebookBadge} <span class="badge bg-info text-dark ms-2"><i class="fas fa-layer-group me-1"></i>${book.category_name || 'ทั่วไป'}</span>
                </div>
                
                <h5 class="text-muted mb-3">ผู้แต่ง: ${book.author || '-'}</h5>
                <p class="mb-1"><strong>ISBN:</strong> ${book.isbn || '-'}</p>
                <hr>
                
                <h5 class="fw-bold mt-4">รายละเอียด</h5>
                <p class="text-secondary">${book.description || 'ไม่มีรายละเอียด'}</p>

                <div class="mt-5">
                    <button class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">
                        <i class="fas fa-book-reader me-2"></i>จอง / ยืมหนังสือ
                    </button>
                    <button class="btn btn-outline-success btn-lg px-4 rounded-pill ms-2" ${book.has_ebook > 0 ? '' : 'disabled style="opacity:0.5;"'}>
                        <i class="fas fa-tablet-alt me-2"></i>อ่าน E-Book
                    </button>
                    
                    <a href="index" class="btn btn-outline-secondary btn-lg px-4 rounded-pill ms-2 text-decoration-none text-muted">
                        ย้อนกลับ
                    </a>
                </div>
            </div>
        `;
            $('#book-content').html(html);
        }
    });
</script>