<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Add/Edit Project</title>
</head>
<body>
<div class="container mt-5">
    <h2 id="formTitle">Add Project</h2>
    <form id="addEditProyekForm">
        <input type="hidden" id="proyekId" name="proyekId">
        <div class="mb-3">
            <label for="namaProyek" class="form-label">Nama Proyek</label>
            <input type="text" class="form-control" id="namaProyek" name="namaProyek" required>
        </div>
        <div class="mb-3">
            <label for="client" class="form-label">Client</label>
            <input type="text" class="form-control" id="client" name="client" required>
        </div>
        <div class="mb-3">
            <label for="tglMulai" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tglMulai" name="tglMulai" required>
        </div>
        <div class="mb-3">
            <label for="tglSelesai" class="form-label">Tanggal Selesai</label>
            <input type="date" class="form-control" id="tglSelesai" name="tglSelesai" required>
        </div>
        <div class="mb-3">
            <label for="pimpinanProyek" class="form-label">Pimpinan Proyek</label>
            <input type="text" class="form-control" id="pimpinanProyek" name="pimpinanProyek" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <select class="form-select" id="lokasi" name="lokasi" required>
            </select>
        </div>
        <button type="button" class="btn btn-primary" onclick="submitProyek()">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const proyekId = urlParams.get('edit');
    
    if (proyekId) {
        document.getElementById('formTitle').textContent = 'Edit Project';
        document.getElementById('proyekId').value = proyekId;
        
        axios.get(`http://localhost:8080/proyek/${proyekId}`)
            .then(response => {
                const proyek = response.data;
                document.getElementById('namaProyek').value = proyek.namaProyek;
                document.getElementById('client').value = proyek.client;
                document.getElementById('tglMulai').value = proyek.tglMulai;
                document.getElementById('tglSelesai').value = proyek.tglSelesai;
                document.getElementById('pimpinanProyek').value = proyek.pimpinanProyek;
                document.getElementById('keterangan').value = proyek.keterangan;
                
                axios.get('http://localhost:8080/lokasi')
                    .then(response => {
                        const lokasiSelect = document.getElementById('lokasi');
                        response.data.forEach(lokasi => {
                            const option = document.createElement('option');
                            option.value = lokasi.id;
                            option.textContent = `${lokasi.namaLokasi}, ${lokasi.kota}, ${lokasi.provinsi}, ${lokasi.negara}`;
                            if (proyek.lokasi.some(l => l.id === lokasi.id)) {
                                option.selected = true;
                            }
                            lokasiSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('There was an error fetching the locations!', error);
                    });
            })
            .catch(error => {
                console.error('There was an error fetching the project details!', error);
            });
    } else {
        axios.get('http://localhost:8080/lokasi')
            .then(response => {
                const lokasiSelect = document.getElementById('lokasi');
                response.data.forEach(lokasi => {
                    const option = document.createElement('option');
                    option.value = lokasi.id;
                    option.textContent = `${lokasi.namaLokasi}, ${lokasi.kota}, ${lokasi.provinsi}, ${lokasi.negara}`;
                    lokasiSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('There was an error fetching the locations!', error);
            });
    }
});

function submitProyek() {
    const proyekId = document.getElementById('proyekId').value;
    const lokasiId = document.getElementById('lokasi').value;
    const data = {
        namaProyek: document.getElementById('namaProyek').value,
        client: document.getElementById('client').value,
        tglMulai: document.getElementById('tglMulai').value,
        tglSelesai: document.getElementById('tglSelesai').value,
        pimpinanProyek: document.getElementById('pimpinanProyek').value,
        keterangan: document.getElementById('keterangan').value,
    };
    
    const url = proyekId
        ? `http://localhost:8080/proyek/${proyekId}?lokasiIds=${lokasiId}`
        : `http://localhost:8080/proyek?lokasiIds=${lokasiId}`;
    
    const method = proyekId ? 'put' : 'post';
    
    axios({
        method: method,
        url: url,
        data: data
    })
    .then(response => {
        alert('Project saved successfully!');
        window.location.href = 'welcome'; 
    })
    .catch(error => {
        console.error('There was an error saving the project!', error);
    });
}   
</script>
</body>
</html>
