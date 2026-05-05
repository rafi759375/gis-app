<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Jalan Rusak</title>

    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    <style>
        body { margin: 0; }
        #map { height: calc(100vh - 60px); }
    </style>
</head>

<body class="flex flex-col h-screen">

    <!-- NAVBAR -->
    <header class="flex justify-between items-center px-6 py-3 bg-white shadow h-[60px]">

        <h1 class="font-bold">GIS App</h1>

        <div>
            @auth
                <a href="/dashboard" class="px-4 py-2 bg-blue-500 text-gray-800 rounded">
                    Dashboard
                </a>
            @else
                <a href="/login" class="px-4 py-2 text-gray-800 hover:text-blue-500 rounded">
                    Login
                </a>

                <a href="/register" class="ml-2 px-4 py-2 bg-blue-500 text-gray-800 rounded">
                    Register
                </a>
            @endauth
        </div>

    </header>

    <!-- MAP -->
    <div id="map"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- INIT MAP -->
    <script>
        var map = L.map('map').setView([-6.9, 107.6], 10);

// tile
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(map);

// 🔥 ambil lokasi user
map.locate({setView: true, maxZoom: 16});

// sukses
map.on('locationfound', function(e) {
    var radius = e.accuracy;

    L.marker(e.latlng).addTo(map)
        .bindPopup("Kamu di sini")
        .openPopup();

    L.circle(e.latlng, radius).addTo(map);
});

// gagal
map.on('locationerror', function(e) {
    alert("Lokasi tidak bisa diambil, pakai default.");
});
    </script>
@auth
<script>
map.on('click', function(e) {

    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    var form = `
        <b>Lapor Jalan Rusak</b><br><br>

        <form>
            <label>Deskripsi:</label><br>
            <textarea id="desc" rows="3" style="width:100%;"></textarea><br><br>

            <button type="button" onclick="submitReport(${lat}, ${lng})">
                Kirim
            </button>
        </form>
    `;

    L.popup()
        .setLatLng(e.latlng)
        .setContent(form)
        .openOn(map);
});
</script>
@endauth
@guest
<script>
map.on('click', function(e) {
    L.popup()
        .setLatLng(e.latlng)
        .setContent("Silakan login untuk melapor jalan rusak")
        .openOn(map);
});
</script>
@endguest
</body>
</html>