<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Data dengan API</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Dropzone CSS -->
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" />
    
    <!-- jQuery UI Datepicker CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        .select2-container {
            width: 100% !important;
        }
        .error {
            color: red;
            font-size: 0.8rem;
        }
        .dropzone {
            border: 2px dashed #3498db;
            border-radius: 5px;
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Manajemen Data Pegawai</h4>
                <button id="tambahData" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#formModal">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Data
                </button>
            </div>
            <div class="card-body">
                <table id="apiDataTable" class="table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Role</th>
                            <th>Gaji</th>
                            <th>Tanggal Lahir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data API di sini -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="formModalLabel">Form Tambah Data Pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="pegawaiForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <select class="form-select select2-jabatan" id="jabatan" name="jabatan" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Intern">Intern</option>
                                    <option value="QA">QA</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="gaji" class="form-label">Gaji</label>
                                <input type="int" class="form-control" id="gaji" name="gaji" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <div id="dropzoneUpload" class="dropzone"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Library JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>

    <script>
    $(document).ready(function() {
        // Konfigurasi Umum
        const API_URL = 'http://127.0.0.1:8000/api/pegawai'; // Ganti dengan URL API Anda
        let uploadedFiles = []; // Untuk melacak file yang diunggah

        // Inisialisasi DataTable (seperti sebelumnya)
        let table = $('#apiDataTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: API_URL,
                    type: 'GET',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        alert('Error loading data');
                    }
                },
                columns: [
                    { 
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1; 
                        },
                        orderable: false,
                        searchable: false
                    },
                    { 
                        data: 'nama',
                        name: 'nama',
                        orderable: true,
                        searchable: true 
                    },
                    { 
                        data: 'email',
                        name: 'email',
                        orderable: true,
                        searchable: true 
                    },
                    { 
                        data: 'alamat',
                        name: 'alamat',
                        orderable: true,
                        searchable: true 
                    },
                    { 
                        data: 'jabatan',
                        name: 'jabatan',
                        orderable: true,
                        searchable: true 
                    },
                    { 
                        data: 'gaji',
                        name: 'gaji',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                        // Pastikan data ada dan merupakan angka
                        if (type === 'display' && data != null) {
                            return parseFloat(data).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        }
                        return data; 
                    } 
                    },
                    { 
                        data: 'tanggal_lahir',
                        name: 'tanggal_lahir',
                        orderable: true,
                        searchable: true 
                    }
                ],
                responsive: true,
                language: {
                    search: '_INPUT_',
                    searchPlaceholder: 'Cari data...',
                    lengthMenu: '_MENU_ item per halaman'
                },
                // order: [[1, 'asc']], // Default sorting
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
            });

        // Inisialisasi Select2
    //    $('.select2-role').select2({
    //         placeholder: "Pilih Role",
    //         allowClear: true,
    //         width: '100%',
    //     });

        // Inisialisasi Datepicker
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0'
        });

        // Konfigurasi Dropzone
        const dropzone = new Dropzone("#dropzoneUpload", {
            url: API_URL, // Sesuaikan dengan endpoint upload API
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            maxFiles: 5,
            acceptedFiles: '.pdf,.jpg,.jpeg,.png',
            addRemoveLinks: true,
            dictRemoveFile: 'Hapus',
            dictMaxFilesExceeded: 'Maksimal 5 file',
            init: function() {
                this.on('addedfile', function(file) {
                    uploadedFiles.push(file);
                });
                this.on('removedfile', function(file) {
                    const index = uploadedFiles.indexOf(file);
                    if (index > -1) {
                        uploadedFiles.splice(index, 1);
                    }
                });
            }
        });


        // Fungsi Tambah Data
        $(document).on("submit", "#pegawaiForm", function (e) {
        e.preventDefault(); // Mencegah form reload halaman secara default

        let formData = new FormData(this); // Mengambil data form

        $.ajax({
            url: API_URL, // Ganti dengan URL endpoint API Anda
            type: "POST",
            data: formData,
            processData: false, // Jangan memproses data
            contentType: false, // Biarkan browser mengatur content type
            success: function (response) {
                alert("Data berhasil ditambahkan!");
                console.log(response);
                // Reset form setelah sukses
                location.reload();
            },
            error: function (xhr) {
                alert("Terjadi kesalahan saat menambahkan data!");
                console.error(xhr.responseText);
        }
    });
});



        // Tombol Simpan Data
        $('#simpanData').on('click', function() {
            const mode = $('#formMode').val();
            if (mode === 'tambah') {
                tambahData();
            } else {
                editData();
            }
        });

        // Fungsi View Detail (Contoh)
        function viewDetail(id) {
            $.ajax({
                url: API_URL + '/detail/' + id,
                type: 'GET',
                success: function(response) {
                    // Isi modal dengan data detail
                    $('#nama').val(response.nama);
                    $('#email').val(response.email);
                    $('#tanggalLahir').val(response.tanggalLahir);
                    $('#departemen').val(response.departemen).trigger('change');
                    
                    // Nonaktifkan form
                    $('#dataForm input, #dataForm select').prop('disabled', true);
                    $('#simpanData').hide();
                }
            });
        }

        // Fungsi Edit Data (Contoh)
        function editDataById(id) {
            $.ajax({
                url: API_URL + '/detail/' + id,
                type: 'GET',
                success: function(response) {
                    // Set mode edit
                    $('#formMode').val('edit');
                    $('#editId').val(id);
                    $('#modalTitle').text('Edit Data');

                    // Isi form
                    $('#nama').val(response.nama);
                    $('#email').val(response.email);
                    $('#tanggalLahir').val(response.tanggalLahir);
                    $('#departemen').val(response.departemen).trigger('change');

                    // Aktifkan form
                    $('#dataForm input, #dataForm select').prop('disabled', false);
                    $('#simpanData').show();

                    // Buka modal
                    $('#formModal').modal('show');
                }
            });
        }

        // Fungsi Hapus Data
        function deleteData(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: API_URL + '/hapus/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            table.ajax.reload();
                            Swal.fire('Berhasil', 'Data berhasil dihapus', 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal', 'Gagal menghapus data', 'error');
                        }
                    });
                }
            });
        }
    });
    </script>

    <!-- Sweet Alert untuk notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>