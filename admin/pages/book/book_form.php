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
                            <div class="border rounded p-2 mb-2 bg-light">
                                <img id="previewImage" src="../assets/images/default.jpg" class="img-fluid" style="max-height: 300px;">
                            </div>
                            <label class="form-label fw-bold">รูปปกหนังสือ</label>
                            <input type="file" class="form-control" name="image" id="imageInput" accept="image/*">
                            <small class="text-muted">แนะนำขนาดไฟล์ไม่เกิน 2MB (jpg, png)</small>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">ชื่อเรื่อง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="bookTitle" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                                    <select class="form-select" name="category_id" id="categoryId" required>
                                        <option value="">-- เลือกหมวดหมู่ --</option>
                                    </select>
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
                <form id="addCopyForm" class="row g-3 align-items-end">
                    <input type="hidden" name="action" value="add_copy">
                    <input type="hidden" name="title_id" id="copyTitleId">
                    <div class="col-md-6">
                        <label class="form-label">เลขทะเบียนหนังสือ (Accession No) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="accession_no" required placeholder="เช่น BK001-01">
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
                <div id="ebookStatusArea">
                </div>

                <hr class="my-4">

                <h5 class="mb-3">อัปโหลดไฟล์ใหม่ (PDF)</h5>
                <form id="ebookForm" class="d-inline-block text-start" style="max-width: 400px;">
                    <input type="hidden" name="action" value="upload_ebook">
                    <input type="hidden" name="title_id" id="ebookTitleId">

                    <div class="input-group mb-3">
                        <input type="file" class="form-control" name="ebook_file" accept=".pdf" required>
                        <button class="btn btn-primary" type="submit">อัปโหลด</button>
                    </div>
                    <small class="text-muted">เฉพาะไฟล์ .pdf เท่านั้น</small>
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
        // 1. โหลดหมวดหมู่ลง Select
        loadCategories();

        // 2. ตรวจสอบว่าเป็นการแก้ไขหรือไม่
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        if (id) {
            // โหมดแก้ไข: เปิด Tab ทั้งหมด และโหลดข้อมูล
            $('#pageTitle').html('<i class="fa-solid fa-pen-to-square"></i> จัดการข้อมูลหนังสือ');
            $('#formAction').val('update_book');
            $('#titleId').val(id);
            $('#copyTitleId').val(id);
            $('#ebookTitleId').val(id);

            // เปิดใช้งาน Tabs
            $('#copy-tab, #ebook-tab').removeClass('disabled');

            // โหลดข้อมูล
            loadBookData(id);
            loadCopies(id);
        } else {
            // โหมดเพิ่มใหม่: แจ้งเตือน user
            $('#info-tab').append(' <span class="badge bg-warning text-dark">Step 1</span>');
        }

        // 3. Preview Image เมื่อเลือกไฟล์
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

        // 4. Submit Book Info (Tab 1)
        $('#bookForm').on('submit', function(e) {
            e.preventDefault();

            // ใช้ FormData เพราะมีการอัปโหลดไฟล์
            let formData = new FormData(this);

            $.ajax({
                url: 'api/book_api.php',
                type: 'POST',
                data: formData,
                contentType: false, // ต้องกำหนดเป็น false
                processData: false, // ต้องกำหนดเป็น false
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
                            // ถ้าเป็นการเพิ่มใหม่ ให้ Refresh ไปหน้าแก้ไขของ ID ใหม่ (เพื่อให้เพิ่ม Copy/Ebook ต่อได้)
                            if ($('#formAction').val() === 'create_book') {
                                // *ต้องรู้ ID ที่เพิ่งสร้าง ในที่นี้เราอาจต้องให้ API return ID กลับมา*
                                // หรือให้ redirect กลับไปหน้ารายการก่อนง่ายที่สุด
                                window.location.href = 'book';
                            } else {
                                // ถ้าแก้ไขอยู่แล้ว ก็โหลดข้อมูลใหม่
                                loadBookData(id);
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

        // 5. Submit Add Copy (Tab 2)
        $('#addCopyForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'api/book_api.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#addCopyForm')[0].reset();
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

        // 6. Submit E-Book (Tab 3)
        $('#ebookForm').on('submit', function(e) {
            e.preventDefault();
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
                        $('#ebookForm')[0].reset();
                        loadBookData($('#ebookTitleId').val()); // โหลดข้อมูลใหม่เพื่ออัปเดตสถานะไฟล์
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

    function loadBookData(id) {
        // ใช้ api read_all แล้ว filter ใน JS หรือสร้าง endpoint get_one ก็ได้ 
        // แต่เพื่อความง่าย ผมจะใช้ read_all แล้ววนหา (หรือคุณจะเพิ่ม action=get_one ใน API ก็ได้ครับ)
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

                    if (book.image && book.image !== 'default.jpg') {
                        $('#previewImage').attr('src', '../assets/images/' + book.image);
                    }

                    // Update E-Book Status UI
                    if (book.ebook_file) {
                        $('#ebookStatusArea').html(`
                            <div class="alert alert-success">
                                <i class="fa-solid fa-file-pdf fa-3x mb-3"></i><br>
                                <h4>มีไฟล์ E-Book ในระบบแล้ว</h4>
                                <p>${book.ebook_file}</p>
                                <a href="../uploads/ebooks/${book.ebook_file}" target="_blank" class="btn btn-outline-success me-2">
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
        if (!confirm('ต้องการลบเล่มนี้ใช่ไหม?')) return;
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
                    loadCopies($('#copyTitleId').val());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
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