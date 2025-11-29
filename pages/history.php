<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>ประวัติการทำรายการ</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">
            <table id="transactionTable" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="20%">วันที่ทำรายการ</th>
                        <th width="20%">กำหนดคืน</th>
                        <th width="20%" class="text-center">จำนวน (เล่ม)</th>
                        <th width="20%" class="text-center">สถานะ</th>
                        <th width="20%" class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-list me-2"></i>รายละเอียดการยืม</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modalLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">กำลังโหลดข้อมูล...</p>
                </div>
                <div id="modalContent" style="display: none;">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>หนังสือ</th>
                                <th class="text-center">สถานะ</th>
                                <th class="text-center">วันที่คืน</th>
                            </tr>
                        </thead>
                        <tbody id="bookList"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/th.js"></script>

<script>
    $(document).ready(function() {
        moment.locale('th'); // ตั้งค่าวันที่เป็นภาษาไทย

        // 1. โหลดตารางหลัก (Transactions)
        $('#transactionTable').DataTable({
            "ajax": {
                "url": "api/history_api.php?action=get_transactions",
                "type": "GET"
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "loan_date",
                    "render": function(data) {
                        return moment(data).format('D MMM YYYY');
                    }
                },
                {
                    "data": "due_date",
                    "render": function(data) {
                        return moment(data).format('D MMM YYYY');
                    }
                },
                {
                    "data": "total_books",
                    "className": "text-center",
                    "render": function(data, type, row) {
                        return `<span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6">${data} เล่ม</span>`;
                    }
                },
                {
                    "data": "status", // borrow, receive, return, somereturn
                    "className": "text-center",
                    "render": function(data, type, row) {
                        // Logic แสดงสถานะรวมของบิล
                        if (data === 'receive') return '<span class="badge bg-warning text-dark">รอรับหนังสือ</span>';
                        if (data === 'return') return '<span class="badge bg-success">คืนครบแล้ว</span>';
                        if (data === 'somereturn') return '<span class="badge bg-info text-dark">คืนบางส่วน</span>';

                        // กรณี borrow (ยืมอยู่) เช็คว่าเกินกำหนดไหม
                        let isOverdue = moment().isAfter(row.due_date, 'day');
                        if (isOverdue) return '<span class="badge bg-danger">เกินกำหนดคืน</span>';

                        return '<span class="badge bg-primary">กำลังยืม</span>';
                    }
                },
                {
                    "data": "loan_id",
                    "className": "text-center",
                    "orderable": false,
                    "render": function(data) {
                        return `<button class="btn btn-outline-primary btn-sm rounded-pill px-3 btn-detail" data-id="${data}">
                                <i class="fas fa-search me-1"></i>ดูรายละเอียด
                            </button>`;
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
            }
        });

        // 2. จัดการเมื่อกดปุ่ม "ดูรายละเอียด"
        $(document).on('click', '.btn-detail', function() {
            let loanId = $(this).data('id');

            // เปิด Modal และแสดง Loading
            $('#detailModal').modal('show');
            $('#modalLoading').show();
            $('#modalContent').hide();
            $('#bookList').empty();

            // เรียก API ดึงรายชื่อหนังสือ
            $.ajax({
                url: 'api/history_api.php',
                data: {
                    action: 'get_details',
                    id: loanId
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success' && res.data.length > 0) {
                        let html = '';
                        res.data.forEach(item => {
                            let img = item.image ? `assets/images/${item.image}` : 'assets/images/blank_cover_book.jpg';

                            let itemStatus = '';
                            if (item.item_status === 'return') {
                                itemStatus = '<span class="badge bg-success">คืนแล้ว</span>';
                            } else {
                                itemStatus = '<span class="badge bg-secondary">ยังไม่คืน</span>';
                            }

                            let returnDate = item.return_date ? moment(item.return_date).format('D MMM YYYY') : '-';

                            html += `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${img}" class="rounded shadow-sm me-3" style="width: 40px; height: 60px; object-fit: cover;">
                                    <span class="fw-bold">${item.title}</span>
                                </div>
                            </td>
                            <td class="text-center">${itemStatus}</td>
                            <td class="text-center text-secondary">${returnDate}</td>
                        </tr>`;
                        });
                        $('#bookList').html(html);
                    } else {
                        $('#bookList').html('<tr><td colspan="3" class="text-center">ไม่พบข้อมูลหนังสือ</td></tr>');
                    }

                    // ซ่อน Loading แสดง Content
                    $('#modalLoading').hide();
                    $('#modalContent').fadeIn();
                },
                error: function() {
                    $('#bookList').html('<tr><td colspan="3" class="text-center text-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>');
                    $('#modalLoading').hide();
                    $('#modalContent').fadeIn();
                }
            });
        });
    });
</script>