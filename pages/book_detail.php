<div class="container py-5">
    <div id="book-content" class="row">
        <div class="col-12 text-center" style="margin-top: 50px;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">กำลังโหลดข้อมูลหนังสือ...</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const bookId = urlParams.get('id');

        // ตรวจสอบ path ของ API ว่าถูกต้องหรือไม่
        // ถ้าเข้าผ่าน index.php ปกติจะเป็น 'api/...' แต่ถ้าเข้าไฟล์ตรงๆ อาจต้องแก้
        const apiPath = 'api/book_api.php'; 

        if (bookId) {
            loadBookDetail(bookId);
        } else {
            $('#book-content').html(`
                <div class="col-12 text-center">
                    <div class="alert alert-danger">ไม่พบรหัสหนังสือ (ID is missing)</div>
                    <a href="books" class="btn btn-secondary">กลับหน้ารายการ</a>
                </div>
            `);
        }

        function loadBookDetail(id) {
            $.ajax({
                url: apiPath,
                type: 'GET',
                data: { action: 'book_detail', id: id },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success' && res.data) {
                        renderBook(res.data);
                    } else {
                        // กรณีเชื่อมต่อได้ แต่ API บอกว่า Error (เช่น SQL ผิด)
                        console.error("API Response Error:", res);
                        $('#book-content').html(`
                            <div class="col-12 text-center alert alert-warning">
                                <h3>ไม่พบข้อมูลหนังสือ</h3>
                                <p>${res.message || 'Unknown error'}</p>
                                <a href="books" class="btn btn-primary mt-3">กลับหน้ารายการ</a>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    // *** ส่วนนี้จะช่วยบอกสาเหตุที่แท้จริง ***
                    console.error("AJAX Error:", xhr.responseText);
                    
                    let errorMsg = 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้';
                    let errorDetail = '';

                    if (xhr.status === 404) {
                        errorMsg = 'หาไฟล์ API ไม่เจอ (404)';
                        errorDetail = `ระบบหาไฟล์ <b>${apiPath}</b> ไม่เจอ<br>โปรดตรวจสอบว่ามีโฟลเดอร์ api อยู่จริง`;
                    } else if (xhr.status === 500) {
                        errorMsg = 'เกิดข้อผิดพลาดที่เซิร์ฟเวอร์ (500)';
                        errorDetail = 'กรุณาเช็คการเชื่อมต่อ Database หรือไฟล์ connectdb.php';
                    } else if (status === 'parsererror') {
                        errorMsg = 'ข้อมูลที่ได้ไม่ใช่ JSON';
                        errorDetail = 'อาจมีข้อความ Error จาก PHP แทรกเข้ามาในผลลัพธ์ (กด F12 ดู Console)';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: errorMsg,
                        html: errorDetail,
                        footer: `<span class="text-danger">${xhr.responseText.substring(0, 100)}...</span>`
                    });

                    $('#book-content').html(`<div class="col-12 text-center text-danger">เกิดข้อผิดพลาด (${xhr.status})</div>`);
                }
            });
        }

        function renderBook(book) {
            let imgSrc = book.image ? `assets/images/${book.image}` : 'assets/images/blank_cover_book.jpg';
            let copies = parseInt(book.copies_available);
            let statusBadge, actionButton;

            // ตรวจสอบสถานะสต็อก
            if (copies > 0) {
                statusBadge = `<span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>ว่าง (${copies} เล่ม)</span>`;
                actionButton = `
                    <button class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm btn-add-cart" 
                            data-id="${book.title_id}" 
                            data-title="${book.title.replace(/"/g, '&quot;')}">
                        <i class="fas fa-cart-plus me-2"></i>หยิบใส่ตะกร้า
                    </button>`;
            } else {
                statusBadge = `<span class="badge bg-secondary fs-6"><i class="fas fa-times-circle me-1"></i>ถูกยืมหมดแล้ว</span>`;
                actionButton = `
                    <button class="btn btn-secondary btn-lg px-4 rounded-pill shadow-sm" disabled>
                        <i class="fas fa-ban me-2"></i>หนังสือหมด
                    </button>`;
            }

            // ตรวจสอบสถานะ E-Book
            let ebookButton = '';
            if (book.has_ebook > 0 && book.ebook_id) {
                ebookButton = `
                    <a href="read_ebook?id=${book.ebook_id}" class="btn btn-outline-success btn-lg px-4 rounded-pill ms-2">
                        <i class="fas fa-tablet-alt me-2"></i>อ่าน E-Book
                    </a>`;
            }

            let html = `
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <img src="${imgSrc}" class="card-img-top" alt="${book.title}" style="max-height: 500px; object-fit: cover;">
                </div>
            </div>
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="books">หนังสือทั้งหมด</a></li>
                        <li class="breadcrumb-item active" aria-current="page">${book.title}</li>
                    </ol>
                </nav>

                <h1 class="fw-bold mb-3 display-6">${book.title}</h1>
                <div class="mb-4">
                    ${statusBadge}
                    <span class="badge bg-info text-dark ms-2"><i class="fas fa-layer-group me-1"></i>${book.category_name || 'ทั่วไป'}</span>
                </div>
                
                <h5 class="text-muted mb-2"><strong>ผู้แต่ง:</strong> ${book.author || '-'}</h5>
                <p class="mb-4 text-muted"><strong>ISBN:</strong> ${book.isbn || '-'}</p>
                
                <div class="card bg-light border-0 rounded-3 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold"><i class="fas fa-info-circle me-2"></i>รายละเอียด</h5>
                        <p class="card-text text-secondary" style="white-space: pre-line;">${book.description || 'ไม่มีรายละเอียดเพิ่มเติม'}</p>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-wrap gap-2">
                    ${actionButton}
                    ${ebookButton}
                    <a href="books" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">ย้อนกลับ</a>
                </div>
            </div>
            `;
            $('#book-content').html(html);
        }

        // Event Listener: หยิบใส่ตะกร้า
        $(document).on('click', '.btn-add-cart', function() {
            let id = $(this).data('id');
            let title = $(this).data('title');
            let btn = $(this);

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>กำลังเพิ่ม...');

            $.post('api/cart_api.php', { action: 'add', id: id, title: title }, function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-cart-plus me-2"></i>หยิบใส่ตะกร้า');
                
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: 'เพิ่ม "' + title + '" ลงตะกร้าเรียบร้อย',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'warning', title: 'แจ้งเตือน', text: res.message });
                }
            }, 'json')
            .fail(function(xhr) {
                // แจ้ง Error ของตะกร้าด้วย
                btn.prop('disabled', false).html('<i class="fas fa-cart-plus me-2"></i>หยิบใส่ตะกร้า');
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด (Cart)',
                    text: 'Status: ' + xhr.status + ' ' + xhr.responseText
                });
            });
        });
    });
</script>