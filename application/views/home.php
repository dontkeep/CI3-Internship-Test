<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.bootstrap5.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Internship Test</title>
</head>
<body onload="getData()">
  <div class="container mt-5 mb-5">
    <div class="mb-3">
      <a class="btn btn-success me-2" href="addproyek">Add Project</a>
      <a class="btn btn-info" href="addlocation">Add Location</a>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#locationsModal">View Locations</button>
    </div>

    <table class="table table-striped" id="proyekTable" style="width:100%">
      <thead>
        <th>Nama Proyek</th>
        <th>Nama Client</th>
        <th>Tgl Mulai</th>
        <th>Tgl Selesai</th>
        <th>Pimpinan</th>
        <th>Keterangan</th>
        <th>Actions</th>
      </thead>
      <tbody id="tbody">
      </tbody>
    </table>
  </div>

  <!-- Locations Modal -->
  <div class="modal fade" id="locationsModal" tabindex="-1" aria-labelledby="locationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="locationsModalLabel">Locations List</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-striped" id="locationsTable">
            <thead>
              <th>Nama Lokasi</th>
              <th>Negara</th>
              <th>Provinsi</th>
              <th>Kota</th>
              <th>Actions</th>
            </thead>
            <tbody id="locationsTbody">
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Location Details Modal -->
  <div class="modal fade" id="locationDetailsModal" tabindex="-1" aria-labelledby="locationDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="locationDetailsModalLabel">Location Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="locationDetailsContent">
          <!-- Location details will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    async function getData() {
      axios.get('http://localhost:8080/proyek')
        .then(function (response) {
          console.log(response);
          let resdata = response.data;

          let data = resdata.map(element => [
            element.namaProyek,
            element.client,
            dayjs(element.tglMulai).format('YYYY-MM-DD'),
            dayjs(element.tglSelesai).format('YYYY-MM-DD'),
            element.pimpinanProyek,
            element.keterangan,
            `
              <button class="btn btn-primary btn-sm" onclick="viewLokasi(${element.id})">View Location</button>
              <a href="addproyek?edit=${element.id}" class="btn btn-warning btn-sm">Edit</a>
              <button class="btn btn-danger btn-sm" onclick="deleteProyek(${element.id})">Delete</button>
            `
          ]);

          $('#proyekTable').DataTable({
            data: data,
            columns: [
              { title: "Nama Proyek" },
              { title: "Nama Client" },
              { title: "Tgl Mulai" },
              { title: "Tgl Selesai" },
              { title: "Pimpinan" },
              { title: "Keterangan" },
              { title: "Actions" }
            ],
            destroy: true
          });
        })
        .catch(function (error) {
          console.error("There was an error fetching the data!", error);
        });
    }

    async function loadLocations() {
      axios.get('http://localhost:8080/lokasi')
        .then(function (response) {
          console.log(response);
          let resdata = response.data;

          let data = resdata.map(element => [
            element.namaLokasi,
            element.negara,
            element.provinsi,
            element.kota,
            `
              <a href="addlocation?edit=${element.id}" class="btn btn-warning btn-sm">Edit</a>
              <button class="btn btn-danger btn-sm" onclick="deleteLocation(${element.id})">Delete</button>
            `
          ]);

          $('#locationsTable').DataTable({
            data: data,
            columns: [
              { title: "Nama Lokasi" },
              { title: "Negara" },
              { title: "Provinsi" },
              { title: "Kota" },
              { title: "Actions" }
            ],
            destroy: true
          });
        })
        .catch(function (error) {
          console.error("There was an error fetching the locations!", error);
        });
    }

    function viewLokasi(proyekId) {
      axios.get('http://localhost:8080/proyek')
        .then(function (response) {
          let proyek = response.data.find(p => p.id === proyekId);
          if (proyek && proyek.lokasi) {
            let lokasi = proyek.lokasi;
            let content = lokasi.map(l => `
              <h4>Location Details</h4>
              <p><strong>Nama Lokasi:</strong> ${l.namaLokasi}</p>
              <p><strong>Negara:</strong> ${l.negara}</p>
              <p><strong>Provinsi:</strong> ${l.provinsi}</p>
              <p><strong>Kota:</strong> ${l.kota}</p>
            `).join('<hr/>');

            document.getElementById('locationDetailsContent').innerHTML = content;
            let locationDetailsModal = new bootstrap.Modal(document.getElementById('locationDetailsModal'));
            locationDetailsModal.show();
          } else {
            console.error("No location data found for the selected project.");
          }
        })
        .catch(function (error) {
          console.error("There was an error fetching the project details!", error);
        });
    }

    function deleteProyek(proyekId) {
      if (confirm('Are you sure you want to delete this project?')) {
        axios.delete(`http://localhost:8080/proyek/${proyekId}`)
          .then(response => {
            alert('Project deleted successfully!');
            getData(); // Refresh the table
          })
          .catch(error => {
            console.error('There was an error deleting the project!', error);
          });
      }
    }

    function deleteLocation(locationId) {
      if (confirm('Are you sure you want to delete this location?')) {
        axios.delete(`http://localhost:8080/lokasi/${locationId}`)
          .then(response => {
            alert('Location deleted successfully!');
            // Refresh the locations table in the modal
            loadLocations();
          })
          .catch(error => {
            console.error('There was an error deleting the location!', error);
          });
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      loadLocations(); // Load locations when the modal is opened
    });
  </script>
</body>
</html>
