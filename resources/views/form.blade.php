<!DOCTYPE html>
<html lang="id">
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Web Pengaduan SMOPI</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .header-container {
            max-width: 100%;
            margin: 0 auto 20px auto;
            background: #fefad9;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .header-container img {
            height: 124px;
        }

        .header-container h1 {
            font-size: 22px;
            margin: 0;
        }

        .header-container p {
            font-size: 14px;
            margin: 5px 0 0 0;
            color: #555;
        }

        .form-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fafafa;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button[type="submit"] {
            background-color: #1a73e8;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #1669c1;
        }

        button[type="button"] {
            background-color: #e0e0e0;
            color: #333;
        }

        button[type="button"]:hover {
            background-color: #d5d5d5;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header-container">
        <img src="{{ asset('assets/logo-teman-smopi.png') }}" alt="Logo Instansi" />
        <h1>Kementerian Pekerjaan Umum</h1>
        <p>Sistem Layanan SMOPI - Versi 1.0</p>
    </div>

    <!-- Form -->
    <div class="form-container">
        <h2>Form TEMAN SMOPI</h2>
        <form id="pengaduanForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama">Nama<span style="color: red">*</span></label>
                <input placeholder="Contoh: Supriyadi" type="text" id="nama" name="nama" required />
            </div>

            <div class="form-group">
                <label for="nohp">Nomor WhatsApp<span style="color: red">*</span></label>
                <input placeholder="Contoh: 081231412234" type="text" id="nohp" name="nohp" required />
            </div>

            <div class="form-group">
                <label for="asal_instansi">Kewenangan Pengelolaan DI<span style="color: red">*</span></label>
                <select id="asal_instansi" name="asal_instansi" required>
                    <option value="">-- Pilih Asal --</option>
                    <option value="BBWS Citarum">BBWS Citarum</option>
                    <option value="BBWS Serayu Opak">BBWS Serayu Opak</option>
                    <option value="BBWS Bengawan Solo">BBWS Bengawan Solo</option>
                    <option value="Kantor Pusat PU">Kantor Pusat PU</option>
                    <option value="Lainnya">Instansi Lainnya</option>
                </select>
            </div>
            <div class="form-group" id="form-di">
                <label for="nama_di">Daerah Irigasi<span style="color: red">*</span></label>
                <input type="text" id="nama_di" name="nama_di" required />
            </div>

            <div class="form-group" id="form-instansi" style="display: none;">
                <label for="nama_instansi">Nama Instansi<span style="color: red">*</span></label>
                <input type="text" id="nama_instansi" name="nama_instansi" />
            </div>

            <div class="form-group">
                <label for="pengajar">Nama Pengajar<span style="color: red">*</span></label>
                <select id="pengajar" name="pengajar" required>
                    <option value="">-- Pilih Mentor/ PIC --</option>
                    @foreach ($users as $item)
                        <option value="{{ $item->id }}" data-id="{{ $item->id }}">{{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="nama_akun">Nama Akun<span style="color: red">*</span></label>
                <input placeholder="Contoh: Supriyadi123" type="text" id="nama_akun" name="nama_akun" required />
            </div>

            <div class="form-group">
                <label for="jenis_akun">Jenis Akun Petugas<span style="color: red">*</span></label>
                <select id="jenis_akun" name="jenis_akun" required>
                    <option value="">-- Pilih Jenis Akun --</option>
                    <option value="admin">Admin</option>
                    <option value="j1">Jenjang 1</option>
                    <option value="j2">Jenjang 2</option>
                    <option value="pengamat">Pengamat</option>
                    <option value="mantri">Mantri/ Juru</option>
                    <option value="ppa">Petugas Pintu Air (PPA)</option>
                    <option value="pob">Petugas Operasi Bendung (POB)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="menu_kendala">Menu/ Halaman Muncul Kendala<span style="color: red">*</span></label>
                <select id="menu_kendala" name="menu_kendala" required>
                    <option value="">-- Pilih Halaman --</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->ID }}" data-id="{{ $item->ID }}">{{ $item->NAMA_KATEGORI }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Kendala<span style="color: red">*</span></label>
                <textarea id="deskripsi" name="deskripsi" required></textarea>
            </div>

            <div class="form-group">
                <label for="bukti">Bukti<span style="color: red">*</span> <span
                        style="font-size: 12px; color: red">(Mohon kirim Screen Capture yang jelas dan benar agar dapat
                        diproses.)</span> </label>
                <input type="file" id="bukti" name="bukti" required />
            </div>

            <div class="form-group">
                <label style="color: red">* Jika terdapat lebih dari 1 kendala, mohon isikan ulang form ini.</label>

            </div>

            <div class="button-group">
                <button type="submit">Kirim</button>
                <button type="button" onclick="clearForm()">Clear Form</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("pengaduanForm").addEventListener("submit", function(e) {
            e.preventDefault();

            var form = document.getElementById("pengaduanForm");
            var formData = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('pengaduan.submit') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": token
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert("Pengaduan berhasil dikirim!");
                    window.location.href = "{{ url()->current() }}";
                })
                .catch(error => {
                    alert("Terjadi kesalahan saat mengirim pengaduan.");
                });
        });

        function clearForm() {
            document.getElementById("pengaduanForm").reset();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const jenisAkun = document.getElementById('jenis_akun');
            const menuKendala = document.getElementById('menu_kendala');

            const allOptions = Array.from(menuKendala.querySelectorAll('option'));

            jenisAkun.addEventListener('change', function() {
                const selectedValue = this.value;

                // Simpan option default
                const defaultOption = allOptions.find(opt => opt.value === "");

                // Kosongkan dropdown dulu
                menuKendala.innerHTML = '';
                if (defaultOption) menuKendala.appendChild(defaultOption);

                allOptions.forEach(option => {
                    const id = parseInt(option.getAttribute('data-id'));

                    if (!id) return; // skip option kosong

                    if (selectedValue === 'admin' && id >= 1 && id <= 7) {
                        menuKendala.appendChild(option);
                    } else if (selectedValue === 'j1' && id === 8) {
                        menuKendala.appendChild(option);
                    } else if (selectedValue === 'j2' && id >= 9 && id <= 17) {
                        menuKendala.appendChild(option);
                    } else if (selectedValue === 'pengamat' && id >= 18 && id <= 37) {
                        menuKendala.appendChild(option);
                    } else if (selectedValue === 'mantri' && id >= 38 && id <= 44) {
                        menuKendala.appendChild(option);
                    } else if (selectedValue === 'ppa' && id >= 45 && id <= 46) {
                        menuKendala.appendChild(option);
                    } else if (selectedValue === 'pob' && id === 47) {
                        menuKendala.appendChild(option);
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const selectAsal = document.getElementById('asal_instansi');
            const formDI = document.getElementById('form-di');
            const formInstansi = document.getElementById('form-instansi');
            const inputDI = document.getElementById('nama_di');
            const inputInstansi = document.getElementById('nama_instansi');

            selectAsal.addEventListener('change', function() {
                if (this.value === 'Lainnya') {
                    formDI.style.display = 'none';
                    inputDI.removeAttribute('required');

                    formInstansi.style.display = 'block';
                    inputInstansi.setAttribute('required', 'required');
                } else {
                    formDI.style.display = 'block';
                    inputDI.setAttribute('required', 'required');

                    formInstansi.style.display = 'none';
                    inputInstansi.removeAttribute('required');
                }
            });
        });
    </script>
    <script>
        document.getElementById("pengaduanForm").addEventListener("submit", function() {
            document.getElementById("pageLoading").style.display = "flex";
        });
    </script>
    <div id="pageLoading"
        style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;
        display: none;
     ">
        <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
        </div>
    </div>
</body>

</html>
