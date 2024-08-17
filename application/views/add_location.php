<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Add/Edit Location</title>
</head>
<body>
<div class="container mt-5">
    <h2 id="formTitle">Add Location</h2>
    <form id="addEditLocationForm">
        <input type="hidden" id="locationId" name="locationId">
        <div class="mb-3">
            <label for="namaLokasi" class="form-label">Nama Lokasi</label>
            <input type="text" class="form-control" id="namaLokasi" name="namaLokasi" required>
        </div>
        <div class="mb-3">
            <label for="negara" class="form-label">Negara</label>
            <input type="text" class="form-control" id="negara" name="negara" required>
        </div>
        <div class="mb-3">
            <label for="provinsi" class="form-label">Provinsi</label>
            <input type="text" class="form-control" id="provinsi" name="provinsi" required>
        </div>
        <div class="mb-3">
            <label for="kota" class="form-label">Kota</label>
            <input type="text" class="form-control" id="kota" name="kota" required>
        </div>
        <button type="button" class="btn btn-primary" onclick="submitLocation()">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const locationId = urlParams.get('edit');

    if (locationId) {
        document.getElementById('formTitle').textContent = 'Edit Location';
        document.getElementById('locationId').value = locationId;

        // Fetch location details
        axios.get(`http://localhost:8080/lokasi/${locationId}`)
            .then(response => {
                const location = response.data;
                document.getElementById('namaLokasi').value = location.namaLokasi;
                document.getElementById('negara').value = location.negara;
                document.getElementById('provinsi').value = location.provinsi;
                document.getElementById('kota').value = location.kota;
            })
            .catch(error => {
                console.error('There was an error fetching the location details!', error);
            });
    }
});

function submitLocation() {
    const locationId = document.getElementById('locationId').value;
    const data = {
        namaLokasi: document.getElementById('namaLokasi').value,
        negara: document.getElementById('negara').value,
        provinsi: document.getElementById('provinsi').value,
        kota: document.getElementById('kota').value,
    };

    const url = locationId
        ? `http://localhost:8080/lokasi/${locationId}`
        : 'http://localhost:8080/lokasi';

    const method = locationId ? 'put' : 'post';

    axios({
        method: method,
        url: url,
        data: data
    })
    .then(response => {
        alert('Location saved successfully!');
        window.location.href = 'welcome';
    })
    .catch(error => {
        console.error('There was an error saving the location!', error);
    });
}
</script>
</body>
</html>
