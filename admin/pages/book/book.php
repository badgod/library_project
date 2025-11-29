<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-book"></i> จัดการหนังสือ</h1>
    <a href="book_form" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> เพิ่มหนังสือใหม่
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table id="bookTable" class="table table-striped align-middle w-100">
            <thead>
                <tr>
                    <th width="50">รูปปก</th>
                    <th>ชื่อเรื่อง</th>
                    <th>หมวดหมู่</th>
                    <th class="text-center">รูปเล่ม (ว่าง/ทั้งหมด)</th>
                    <th class="text-center">E-Book</th>
                    <th width="15%">จัดการ</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let table;

    $(document).ready(function() {
        table = $('#bookTable').DataTable({
            ajax: {
                url: 'api/book_api.php',
                data: {
                    action: 'read_all'
                } // ส่ง action ไปด้วย
            },
            columns: [{
                    data: 'image',
                    render: function(data) {
                        const imgPath = data && data !== 'default.jpg' ? `../assets/images/${data}` : '../assets/images/default.jpg';
                        return `<img src="${imgPath}" class="rounded border" width="40" height="60" style="object-fit: cover;">`;
                    }
                },
                {
                    data: 'title',
                    render: function(data, type, row) {
                        return `<strong>${data}</strong><br><small class="text-muted">ผู้แต่ง: ${row.author || '-'}</small>`;
                    }
                },
                {
                    data: 'category_name'
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data, type, row) {
                        let badgeClass = row.available_copies > 0 ? 'bg-success' : 'bg-secondary';
                        return `<span class="badge ${badgeClass}">${row.available_copies} / ${row.total_copies}</span>`;
                    }
                },
                {
                    data: 'ebook_file',
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (data && row.ebook_id) {
                            // ถ้ามีไฟล์ ให้แสดงปุ่ม Link ไปหน้า read_ebook
                            return `<a href="read_ebook?id=${row.ebook_id}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold">
                                        <i class="fa-solid fa-book-reader me-1"></i> อ่าน
                                    </a>`;
                        } else {
                            // ถ้าไม่มีไฟล์
                            return '<span class="badge bg-light text-secondary border">ไม่มีไฟล์</span>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                        <div class="btn-group" role="group">
                            <a href="book_form?id=${row.title_id}" class="btn btn-sm btn-warning" title="แก้ไข/จัดการ">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="deleteBook(${row.title_id})" title="ลบ">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });
    });

    function deleteBook(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลหนังสือรวมถึงประวัติการยืม ตัวเล่ม และไฟล์ E-Book จะถูกลบทั้งหมด!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ลบข้อมูล'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/book_api.php',
                    method: 'POST',
                    data: {
                        action: 'delete_book',
                        title_id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('ลบสำเร็จ', res.message, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('เกิดข้อผิดพลาด', res.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>