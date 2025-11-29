<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2" id="pageTitle"><i class="fa-solid fa-book-medical"></i> เพิ่มหนังสือใหม่</h1>
    <a href="book" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
    </a>
</div>

<ul class="nav nav-tabs mb-3" id="bookTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">ข้อมูลหนังสือ</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link disabled" id="copy-tab" data-bs-toggle="tab" data-bs-target="#copy" type="button" role="tab">จัดการตัวเล่ม (Physical)</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link disabled" id="ebook-tab" data-bs-toggle="tab" data-bs-target="#ebook" type="button" role="tab">ไฟล์ E-Book</button>
    </li>
</ul>

<div class="tab-content" id="bookTabContent">

    <div class="tab-pane fade show active" id="info" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="bookForm" class="needs-validation" novalidate enctype="multipart/form-data">
                    <input type="hidden" name="action" id="formAction" value="create_book">
                    <input type="hidden" name="title_id" id="titleId">
                    <input type="hidden" name="old_image" id="oldImage">

                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="border rounded p-2 mb-2 bg-light position-relative">
                                <img id="previewImage" src="../assets/images/blank_cover_book.jpg" class="img-fluid" style="max-height: 300px;">
                            </div>
                            <label class="form-label fw-bold">รูปปกหนังสือ</label>
                            <input type="file" class="form-control" name="image" id="imageInput" accept="image/jpeg, image/png">
                            <div class="invalid-feedback">กรุณาเลือกไฟล์รูปภาพที่ถูกต้อง</div>
                            <small class="text-muted">รองรับไฟล์ .jpg, .png ขนาดไม่เกิน 2MB</small>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">ชื่อเรื่อง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="bookTitle" required>
                                <div class="invalid-feedback">กรุณากรอกชื่อหนังสือ</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                                    <select class="form-select" name="category_id" id="categoryId" required>
                                        <option value="">-- เลือกหมวดหมู่ --</option>
                                    </select>
                                    <div class="invalid-feedback">กรุณาเลือกหมวดหมู่</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ผู้แต่ง</label>
                                    <input type="text" class="form-control" name="author" id="bookAuthor">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ISBN</label>
                                <input type="text" class="form-control" name="isbn" id="bookIsbn">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">รายละเอียด/เรื่องย่อ</label>
                                <textarea class="form-control" name="description" id="bookDesc" rows="4"></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa-solid fa-save me-2"></i> บันทึกข้อมูล
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="copy" role="tabpanel">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="text-primary mb-3"><i class="fa-solid fa-barcode"></i> เพิ่มเล่มหนังสือ</h5>
                <form id="addCopyForm" class="row g-3 align-items-end needs-validation" novalidate>
                    <input type="hidden" name="action" value="add_copy">
                    <input type="hidden" name="title_id" id="copyTitleId">
                    <div class="col-md-6">
                        <label class="form-label">เลขทะเบียนหนังสือ (Accession No) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="accession_no" required placeholder="เช่น BK001-01">
                        <div class="invalid-feedback">กรุณากรอกเลขทะเบียน</div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fa-solid fa-plus"></i> เพิ่ม
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="copyTable">
                    <thead class="table-light">
                        <tr>
                            <th>เลขทะเบียน</th>
                            <th>สถานะ</th>
                            <th width="10%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="copyTableBody">
                        <tr>
                            <td colspan="3" class="text-center">กำลังโหลด...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="ebook" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body text-center p-5">
                <div id="ebookStatusArea"></div>
                <hr class="my-4">
                <h5 class="mb-3">อัปโหลดไฟล์ใหม่ (PDF)</h5>
                <form id="ebookForm" class="d-inline-block text-start needs-validation" style="max-width: 400px;" novalidate>
                    <input type="hidden" name="action" value="upload_ebook">
                    <input type="hidden" name="title_id" id="ebookTitleId">
                    <div class="mb-3">
                        <div class="input-group has-validation">
                            <input type="file" class="form-control" name="ebook_file" accept=".pdf" required>
                            <button class="btn btn-primary" type="submit">อัปโหลด</button>
                            <div class="invalid-feedback">กรุณาเลือกไฟล์ PDF</div>
                        </div>
                        <small class="text-muted">เฉพาะไฟล์ .pdf เท่านั้น</small>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<p>&nbsp;</p>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        loadCategories();
        checkEditMode();

        // [แก้ไข] ตรวจสอบ URL Parameter ว่าต้องเปิด Tab Copy ไหม
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'copy') {
            const triggerEl = document.querySelector('#copy-tab');
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }

        // Preview Image
        $('#imageInput').change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#previewImage').attr('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // 1. Submit Book Info (Tab 1)
        $('#bookForm').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const form = this;
            if (!form.checkValidity()) {
                $(form).addClass('was-validated');
                return;
            }

            let formData = new FormData(this);

            $.ajax({
                url: 'api/book_api.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            if ($('#formAction').val() === 'create_book') {
                                // [แก้ไข] บันทึกเสร็จ ให้ Redirect กลับมาหน้านี้พร้อม ID และสั่งให้เปิด Tab Copy
                                window.location.href = 'book_form?id=' + res.new_id + '&tab=copy';
                            } else {
                                loadBookData($('#titleId').val());
                                $(form).removeClass('was-validated');
                            }
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                }
            });
        });

        // ... (Code JS ส่วน Add Copy และ Ebook เหมือนเดิม) ...
        $('#addCopyForm').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = this;
            if (!form.checkValidity()) {
                $(form).addClass('was-validated');
                return;
            }
            $.ajax({
                url: 'api/book_api.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        form.reset();
                        $(form).removeClass('was-validated');
                        loadCopies($('#copyTitleId').val());
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'เพิ่มเล่มหนังสือแล้ว'
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        });

        $('#ebookForm').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = this;
            if (!form.checkValidity()) {
                $(form).addClass('was-validated');
                return;
            }
            let formData = new FormData(this);
            $.ajax({
                url: 'api/book_api.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire('สำเร็จ', res.message, 'success');
                        form.reset();
                        $(form).removeClass('was-validated');
                        loadBookData($('#ebookTitleId').val());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });
        });
    });

    // --- Helper Functions ---
    function loadCategories() {
        $.ajax({
            url: 'api/category_api.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                let html = '<option value="">-- เลือกหมวดหมู่ --</option>';
                res.data.forEach(cat => {
                    html += `<option value="${cat.category_id}">${cat.name}</option>`;
                });
                $('#categoryId').html(html);
            }
        });
    }

    function checkEditMode() {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        if (id) {
            $('#pageTitle').html('<i class="fa-solid fa-pen-to-square"></i> จัดการข้อมูลหนังสือ');
            $('#formAction').val('update_book');
            $('#titleId').val(id);
            $('#copyTitleId').val(id);
            $('#ebookTitleId').val(id);
            $('#copy-tab, #ebook-tab').removeClass('disabled');
            loadBookData(id);
            loadCopies(id);
        } else {
            $('#info-tab').append(' <span class="badge bg-warning text-dark">Step 1</span>');
        }
    }

    function loadBookData(id) {
        $.ajax({
            url: 'api/book_api.php?action=read_all',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                const book = res.data.find(b => b.title_id == id);
                if (book) {
                    $('#bookTitle').val(book.title);
                    $('#categoryId').val(book.category_id);
                    $('#bookAuthor').val(book.author);
                    $('#bookIsbn').val(book.isbn);
                    $('#bookDesc').val(book.description);
                    $('#oldImage').val(book.image);

                    if (book.image && book.image !== 'default.jpg' && book.image !== 'blank_cover_book.jpg') {
                        $('#previewImage').attr('src', '../assets/images/' + book.image);
                    } else {
                        // [แก้ไข] กรณีไม่มีรูป หรือเป็น default ให้ใช้ blank_cover_book
                        $('#previewImage').attr('src', '../assets/images/blank_cover_book.jpg');
                    }

                    if (book.ebook_file) {
                        $('#ebookStatusArea').html(`
                            <div class="alert alert-success">
                                <i class="fa-solid fa-file-pdf fa-3x mb-3"></i><br>
                                <h4>มีไฟล์ E-Book แล้ว</h4>
                                <p class="mb-3">${book.ebook_file}</p>
                                <a href="read_ebook?id=${book.ebook_id}" target="_blank" class="btn btn-outline-success me-2">
                                    <i class="fa-solid fa-eye"></i> ดูไฟล์
                                </a>
                                <button onclick="deleteEbook(${id})" class="btn btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i> ลบไฟล์
                                </button>
                            </div>
                        `);
                    } else {
                        $('#ebookStatusArea').html(`
                            <div class="text-muted mb-3">
                                <i class="fa-solid fa-cloud-arrow-up fa-3x"></i><br>
                                <span>ยังไม่มีไฟล์ E-Book</span>
                            </div>
                        `);
                    }
                }
            }
        });
    }

    // ... (ฟังก์ชัน loadCopies, deleteCopy, deleteEbook เหมือนเดิม) ...
    function loadCopies(id) {
        $.ajax({
            url: 'api/book_api.php',
            method: 'GET',
            data: {
                action: 'get_copies',
                title_id: id
            },
            dataType: 'json',
            success: function(res) {
                let html = '';
                if (res.data.length > 0) {
                    res.data.forEach(copy => {
                        let statusBadge = copy.status === 'available' ?
                            '<span class="badge bg-success">ว่าง</span>' :
                            '<span class="badge bg-warning text-dark">ถูกยืม</span>';
                        html += `
                            <tr>
                                <td>${copy.accession_no}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCopy(${copy.copy_id})">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="3" class="text-center text-muted">ยังไม่มีข้อมูลเล่มหนังสือ</td></tr>';
                }
                $('#copyTableBody').html(html);
            }
        });
    }

    function deleteCopy(id) {
        // ใช้ SweetAlert2 แทน confirm() ธรรมดา
        Swal.fire({
            title: 'แน่ใจหรือไม่?',
            text: "คุณต้องการลบเล่มหนังสือนี้ใช่ไหม? การกระทำนี้ไม่สามารถย้อนกลับได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // สีแดงสำหรับปุ่มลบ
            cancelButtonColor: '#3085d6', // สีฟ้าสำหรับปุ่มยกเลิก
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้ากดยืนยัน ให้ส่ง AJAX ไปลบ
                $.ajax({
                    url: 'api/book_api.php',
                    method: 'POST',
                    data: {
                        action: 'delete_copy',
                        copy_id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            // แสดงข้อความเมื่อลบสำเร็จ
                            Swal.fire(
                                'ลบสำเร็จ!',
                                'ข้อมูลเล่มหนังสือถูกลบเรียบร้อยแล้ว',
                                'success'
                            );
                            loadCopies($('#copyTitleId').val()); // โหลดตารางใหม่
                        } else {
                            Swal.fire('เกิดข้อผิดพลาด', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
                    }
                });
            }
        });
    }

    function deleteEbook(titleId) {
        Swal.fire({
            title: 'ลบไฟล์ E-Book?',
            text: 'ไฟล์จะถูกลบออกจาก Server ทันที',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ลบไฟล์'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/book_api.php',
                    method: 'POST',
                    data: {
                        action: 'delete_ebook',
                        title_id: titleId
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('Deleted', res.message, 'success');
                            loadBookData(titleId);
                        }
                    }
                });
            }
        });
    }
</script>