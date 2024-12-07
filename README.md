
## Endpoints

### 1. **Menambahkan Data Pegawai**
- **Endpoint:** `POST /api/pegawai`
- **Deskripsi:** Menambahkan data pegawai baru.
- **Request Body:**
  - `nama`: String (required) - Nama pegawai
  - `email`: String (required, unique) - Email pegawai
  - `alamat`: String (required) - Alamat pegawai
  - `jabatan`: String (required) - Jabatan pegawai (misalnya: Admin, Manager, Staff, QA, Intern)
  - `gaji`: Numeric (required) - Gaji pegawai
  - `tanggal_lahir`: Date (required) - Tanggal lahir pegawai (format: YYYY-MM-DD)
  - `foto`: File (optional) - Foto pegawai (jenis file: jpg, jpeg, png, max size 2MB)

### 2. **Menampilkan Data Pegawai**
- **Endpoint:** `GET /api/pegawai`
- **Deskripsi:** Menambahkan data pegawai baru.


###Data Pegawai
-Data Pegawai ditampilkan pada route web /addData
