<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-users-gear"></i> จัดการผู้ใช้งานและสมาชิก</h1>
    <a href="user_form" class="btn btn-primary">
        <i class="fa-solid fa-user-plus"></i> เพิ่มผู้ใช้งานใหม่
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table id="usersTable" class="table table-striped w-100">
            <thead>
                <tr>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>Username</th>
                    <th>สิทธิ์</th>
                    <th>สถานะ</th>
                    <th width="20%">จัดการ</th>
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
        table = $('#usersTable').DataTable({
            ajax: 'api/user_api.php',
            columns: [{
                    data: 'employee_id'
                },
                {
                    data: null,
                    render: function(data) {
                        return `${data.first_name} ${data.last_name}`;
                    }
                },
                {
                    data: 'username'
                },
                {
                    data: 'role',
                    render: function(data) {
                        return data === 'admin' ?
                            '<span class="badge bg-danger">Admin</span>' :
                            '<span class="badge bg-primary">Member</span>';
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        if (data === 'active') return '<span class="badge bg-success">Active</span>';
                        if (data === 'inactive') return '<span class="badge bg-secondary">Inactive</span>';
                        return '<span class="badge bg-warning text-dark">Pending</span>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        // ปุ่มแก้ไข ลิงก์ไปหน้า user_form พร้อม ID
                        return `
                        <div class="btn-group" role="group">
                            <a href="user_form?id=${row.user_id}" class="btn btn-sm btn-warning" title="แก้ไข">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button class="btn btn-sm btn-info text-white" onclick="resetPassword(${row.user_id})" title="รีเซ็ตรหัสผ่าน">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser(${row.user_id}, ${row.member_id})" title="ลบ">
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

    function resetPassword(id) {
        Swal.fire({
            title: 'รีเซ็ตรหัสผ่าน?',
            text: "รหัสผ่านจะถูกเปลี่ยนเป็น '123456'",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f0ad4e',
            confirmButtonText: 'ยืนยันรีเซ็ต'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/user_api.php',
                    method: 'POST',
                    data: {
                        action: 'reset_password',
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('เรียบร้อย', res.message, 'success');
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    }
                });
            }
        });
    }

    function deleteUser(uid, mid) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลผู้ใช้และสมาชิกจะถูกลบออกจากระบบ",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ลบข้อมูล'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/user_api.php',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        user_id: uid,
                        member_id: mid
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('ลบสำเร็จ', res.message, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('ลบไม่ได้', res.message, 'error');
                        }
                    }
                });
            }
        });
    }
</script>