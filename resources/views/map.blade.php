<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS - Jombang</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="icon" href="{{ asset('assets/image/kabupaten.png') }}" type="image/png">


    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geoman-free@2.14.0/dist/leaflet-geoman.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body {
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }

        #map {
            flex: 1;
            position: relative;
            /* Jangan gunakan absolute jika tidak diperlukan */
            z-index: 0;
        }



        #sidebar {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 300px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            z-index: 1000;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.4s ease, opacity 0.3s ease;
            box-sizing: border-box;
            padding: 60px 20px 10px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            border-right: 2px solid #e5e7eb82;
        }

        #sidebar.open {
            transform: translateX(0);
            opacity: 1;
        }

        #hamburger {
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 20%;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 3000;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            backdrop-filter: blur(5px);
        }

        #hamburger i {
            font-size: 20px;
            color: #199a1ea1;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 5px;
            margin-top: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .tab-button {
            flex: 1;
            padding: 10px;
            text-align: center;
            background: #f4f4f4;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            box-sizing: border-box;
        }

        .tab-button.active {
            background: #3dac1495;
            font-weight: bold;
            box-sizing: border-box;
        }

        .tab-content {
            padding: 10px;
            box-sizing: border-box;
            width: 100%;
            max-width: 300px;
        }

        .tab-pane {
            display: none;
            border-radius: 10px;
        }

        .tab-pane.active {
            display: block;
            border-radius: 10px;
            overflow-x: hidden;
        }

        .menu-header {
            cursor: pointer;
            font-weight: bold;
            margin: 15px 0;
            padding: 15px;
            background-color: rgba(240, 240, 240, 0.9);
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .menu-header:hover {
            background-color: rgba(181, 228, 151, 0.9);
            transform: translateY(-2px);
        }

        .menu-header i {
            font-size: 16px;
            color: #ffffff;
        }

        .menu-content {
            margin-left: 20px;
            display: none;
            padding-left: 10px;
            border-left: 2px solid #3dac14;
            animation: fadeIn 0.3s ease;
        }

        .menu-content input {
            margin-right: 5px;
        }

        .visible {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Styling untuk topbar */
        #topbar {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 2000;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #e5e7eb6c;
            border-radius: 15px;
        }


        .topbar-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo-img {
            height: 40px !important;
            margin-top: 5px;
            width: auto;
            margin-right: 0px;
            object-fit: cover;
        }

        .logo-title {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
        }

        .topbar-search {
            width: 250px;
            height: 35px;
            margin-right: 20px;
            padding: 0 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.2s ease;
        }

        .topbar-search:focus {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .topbar-icons i {
            font-size: 20px;
            color: #111827;
            margin-left: 15px;
            cursor: pointer;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .topbar-icons i:hover {
            color: #4f46e5;
            transform: scale(1.1);
        }



        .popup-table {
            border-collapse: collapse;
            width: 100%;
        }

        .popup-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .popup-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .popup-table tr:hover {
            background-color: #f1f1f1;
        }

        .popup-table td:first-child {
            font-weight: bold;
            text-align: left;
            background-color: #f4f4f4;
        }

        .legend {
            position: absolute;
            top: 80px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            font-size: 14px;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            width: 250px;
            box-sizing: border-box;
            overflow: visible;
        }


        .legend h4 {
            margin: 0;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .legend ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .legend li {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend .legend-color {
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin-right: 10px;
            border-radius: 3px;
        }


        #topbar .logo-title,
        .tab-button,
        .menu-header,
        .topbar-nav,
        .legend h4,
        .tabs {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <header id="topbar">
        <div class="topbar-container">
            <div class="logo">
                <a href="https://www.jombangkab.go.id/beranda">
                    <img src="{{ asset('assets/image/jombang.png') }}" alt="Logo" class="logo-img">
                </a>
            </div>
            <nav class="topbar-nav">

                <a href="https://sambang.jombangkab.go.id/"><img src="{{ asset('assets/image/sambang.png') }}"
                        alt="Logo" class="logo-img"></a>

            </nav>
        </div>
    </header>

    <button id="hamburger"><i class="fas fa-bars"></i></button>
    <div id="sidebar">
        <!-- Tab Navigation -->
        <div class="tabs">
            <button class="tab-button active" data-tab="menu-tab">Menu</button>
            <button class="tab-button" data-tab="graph-tab">Grafik</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Menu Tab -->
            <div id="menu-tab" class="tab-pane active">
                <input type="text" id="search-menu" placeholder="Pencarian ..."
                    style="font-family: 'Poppins', sans-serif; width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">

                @php
                    // Mendapatkan semua folder OPD
                    $opdDirectories = array_filter(glob(public_path('geojson/*')), 'is_dir');
                @endphp

                @foreach ($opdDirectories as $opdDir)
                    @php
                        $opdName = basename($opdDir); // Nama folder sebagai nama OPD
                        $geojsonFiles = array_diff(scandir($opdDir), ['.', '..']); // Daftar file GeoJSON di dalam folder OPD
                    @endphp

                    <div class="menu-header" onclick="toggleMenu('menu-{{ $opdName }}')">
                        {{ $opdName }} <i class="fas fa-chevron-down"></i>
                    </div>

                    <div id="menu-{{ $opdName }}" class="menu-content">
                        @foreach ($geojsonFiles as $file)
                            @php
                                $layerName = pathinfo($file, PATHINFO_FILENAME);
                            @endphp
                            <div class="checkbox-container">
                                <label>
                                    <input type="checkbox"
                                        onchange="toggleLayer('{{ $opdName }}/{{ $layerName }}')">
                                    {{ $layerName }}
                                </label>
                            </div>
                            <div id="opacity-color-settings-{{ $opdName }}-{{ $layerName }}" class="settings"
                                style="display: none; margin-top: 10px;">

                                <input type="range" min="0" max="1" step="0.1" value="0.5"
                                    onchange="updateOpacity('{{ $opdName }}/{{ $layerName }}', this.value)">
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <!-- Grafik Tab -->
            <div id="graph-tab" class="tab-pane">

                {{-- repo 1 --}}
                {{-- <div class="menu-header" onclick="toggleMenu('graph-bapenda')">
                    Bapenda <i class="fas fa-chevron-down"></i>
                </div>

                <div id="graph-bapenda" class="menu-content">
                    <button onclick="showPopup('iframe-bapenda')">Lihat Grafik</button>
                </div> --}}

                {{-- repo 2 --}}
                <div class="menu-header" onclick="toggleMenu('graph-bpkad')">
                    BPKAD <i class="fas fa-chevron-down"></i>
                </div>

                <div id="graph-bpkad" class="menu-content">
                    <button onclick="showPopup('iframe-bpkad')">Lihat Grafik</button>
                </div>

                <div id="overlay"
                    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:1000;"
                    onclick="closePopup()"></div>
            </div>

            <script>
                function showPopup(iframeId) {
                    const iframeContent = {
                        // 'iframe-bapenda': '<iframe width="100%" height="500" seamless frameborder="0" scrolling="yes" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vSv4fcd29BLBBvk8A7aWa-mJiW-NH1gW-RVAyIubJ1fvuKdWLv-LjqPpzgbdwaSDzuRnlFqx8vRc4Ug/pubchart?oid=1798993963&amp;format=interactive"></iframe>',
                        'iframe-bpkad': '<iframe width="100%" height="400" seamless frameborder="0" scrolling="yes" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vSv4fcd29BLBBvk8A7aWa-mJiW-NH1gW-RVAyIubJ1fvuKdWLv-LjqPpzgbdwaSDzuRnlFqx8vRc4Ug/pubchart?oid=2123918905&amp;format=interactive"></iframe><p>Sumber : Badan Pengelolaan Keuangan dan Aset Daerah</p>'
                    };
                    document.getElementById('iframe-container').innerHTML = iframeContent[iframeId] || '';
                    document.getElementById('iframe-popup').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                }

                function closePopup() {
                    document.getElementById('iframe-popup').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                    document.getElementById('iframe-container').innerHTML = '';
                }
            </script>
        </div>
    </div>

    {{-- popup iframe nya --}}
    <div id="iframe-popup"
        style="display:none; position:fixed; top:53%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; width:80%; max-width:900px; box-shadow:0 4px 8px rgba(0,0,0,0.3); z-index:1001; border-radius:20px; ">
        <button onclick="closePopup()"
            style="float:right; background:none; border:none; font-size:16px; cursor:pointer;">✖</button>
        <div id="iframe-container" style="margin-top:10px;"></div>
    </div>

    <script>
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active'));

                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>



    <div id="map"></div>
    <div id="legend" class="legend">
        <h4> </h4>
        <ul id="legend-list"></ul>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geoman-free@2.14.0/dist/leaflet-geoman.min.js"></script>

    <script>
        const map = L.map('map', {
            zoomControl: false
        }).setView([-8.1, 111.9], 10);
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        // Basemap
        const openStreetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        });

        const googleSatellite = L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>',
            maxZoom: 19
        });

        // ArcGIS (ESRI) basemap
        const esriWorldImagery = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; <a href="https://www.esri.com">Esri</a> &mdash; Source: Esri, USGS, NOAA',
                maxZoom: 19
            });

        openStreetMap.addTo(map);
        let layers = {};

        const baseMaps = {
            "Peta OSM": openStreetMap,
            "Peta GS": googleSatellite,
            "Peta ESRI": esriWorldImagery
        };

        L.control.layers(baseMaps, null, {
            position: 'bottomright'
        }).addTo(map);


        function toggleLayer(layerPath) {
            const layerName = layerPath;
            const settingsDiv = document.getElementById(`opacity-color-settings-${layerPath.replace('/', '-')}`);

            if (layers[layerName]) {
                map.removeLayer(layers[layerName]);
                delete layers[layerName];
                settingsDiv.style.display = 'none';

                removeLegendItems(layerName);
            } else {
                loadGeoJSON(layerPath, layerName);
                settingsDiv.style.display = 'block';
            }
        }


        function updateOpacity(layerName, value) {
            if (layers[layerName]) {
                layers[layerName].setStyle({
                    fillOpacity: parseFloat(value)
                });
            }
        }


        const desaColors = {};
        const kecamatanColors = {};

        function toggleMenu(menuId) {
            const menu = document.getElementById(menuId);
            menu.classList.toggle('visible');

            const menuHeader = menu.previousElementSibling.querySelector(
                'i');
            menuHeader.classList.toggle('fa-chevron-down');
            menuHeader.classList.toggle('fa-chevron-up');
        }


        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        document.getElementById('search-menu').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const labels = document.querySelectorAll('#sidebar .menu-content label');

            labels.forEach(label => {
                const text = label.textContent.toLowerCase();
                if (text.includes(query)) {
                    label.style.display = 'block';
                } else {
                    label.style.display = 'none';
                }
            });

            const menuContents = document.querySelectorAll('#sidebar .menu-content');
            menuContents.forEach(menu => {
                const hasVisibleItems = Array.from(menu.querySelectorAll('label')).some(label => label.style
                    .display === 'block');
                if (hasVisibleItems) {
                    menu.style.display = 'block';
                } else {
                    menu.style.display = 'none';
                }
            });
        });

        // loadgeojson untuk kecamatan
        function loadGeoJSON(layerPath, layerName, type = 'kecamatan') {
            const url = `/geojson/${layerPath}.geojson`;


            removeLegendItems(layerName);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const uniqueCategories = new Set(); // Untuk menyimpan kategori unik untuk legenda

                    const layer = L.geoJSON(data, {
                        style: function(feature) {
                            const borderColor = "black";
                            const borderWidth = 1;
                            let fillColor;

                            if (type === 'kecamatan') {
                                const kecamatan = feature.properties.kecamatan || "default";
                                fillColor = getColorForKecamatan(kecamatan);

                                // Tambahkan kategori berdasarkan warna kecamatan
                                const category = getLegendLabelForPercentage(kecamatan);
                                uniqueCategories.add(category);
                            }

                            return {
                                color: borderColor,
                                weight: borderWidth,
                                fillColor: fillColor,
                                fillOpacity: 0.5,
                                interactive: true
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            let popupContent = `<table class="popup-table">`;
                            for (const key in feature.properties) {
                                if (feature.properties.hasOwnProperty(key)) {
                                    popupContent += `
                                <tr>
                                    <td><strong>${key}</strong></td>
                                    <td>${feature.properties[key] || "null"}</td>
                                </tr>`;
                                }
                            }
                            popupContent += `</table>`;
                            layer.bindPopup(popupContent);

                            layer.on({
                                mouseover: function(e) {
                                    e.target.setStyle({
                                        color: "green",
                                        weight: 3
                                    });
                                },
                                mouseout: function(e) {
                                    e.target.setStyle({
                                        color: "black",
                                        weight: 1
                                    });
                                }
                            });
                        }
                    }).addTo(map);

                    layers[layerName] = layer;
                    map.fitBounds(layer.getBounds());

                    // Tambahkan kategori ke legenda
                    uniqueCategories.forEach(category => {
                        const [label, color] = category;
                        addToLegend(label, color, layerName);
                    });
                })
                .catch(error => console.error('Error loading GeoJSON:', error));
        }



        function getLegendLabelForPercentage(kecamatan) {
            const percentages = {
                "Kabuh": 56.82,
                "Jombang": 50.59,
                "Sumobito": 61.91,
                "Wonosalam": 57.21,
                "Tembelang": 47.54,
                "Ploso": 56.89,
                "Plandaan": 52.84,
                "Peterongan": 56.36,
                "Perak": 61.07,
                "Ngusikan": 61.68,
                "Ngoro": 61.63,
                "Mojowarno": 58.35,
                "Mojoagung": 52.15,
                "Megaluh": 57.81,
                "Kudu": 52.97,
                "Kesamben": 51.09,
                "Jogoroto": 55.83,
                "Gudo": 60.28,
                "Diwek": 54.20,
                "Bareng": 56.65,
                "Bandarkedungmulyo": 62.18,
            };

            const percentage = percentages[kecamatan] || 0;

            // Menggunakan kategori baru untuk warna
            if (percentage <= 50) {
                return ['0-50% (Rendah)', 'red'];
            } else if (percentage <= 60) {
                return ['51-60% (Sedang)', 'yellow'];
            } else if (percentage <= 80) {
                return ['61-80% (Tinggi)', 'green'];
            } else {
                return ['81-100% (Hijau Terang)',
                    'lightgreen'
                ];
            }
        }


        function getColorForKecamatan(kecamatan) {
            // Persentase penyerapan anggaran (contoh data)
            const percentages = {
                "Kabuh": 56.82,
                "Jombang": 50.59,
                "Sumobito": 61.91,
                "Wonosalam": 57.21,
                "Tembelang": 47.54,
                "Ploso": 56.89,
                "Plandaan": 52.84,
                "Peterongan": 56.36,
                "Perak": 61.07,
                "Ngusikan": 61.68,
                "Ngoro": 61.63,
                "Mojowarno": 58.35,
                "Mojoagung": 52.15,
                "Megaluh": 57.81,
                "Kudu": 52.97,
                "Kesamben": 51.09,
                "Jogoroto": 55.83,
                "Gudo": 60.28,
                "Diwek": 54.20,
                "Bareng": 56.65,
                "Bandarkedungmulyo": 62.18,
            };

            // Ambil persentase untuk kecamatan
            const percentage = percentages[kecamatan] || 0;

            // Tentukan warna berdasarkan persentase yang sudah diperbarui
            if (percentage <= 50) {
                return 'red'; // Merah untuk 0-50%
            } else if (percentage <= 60) {
                return 'yellow'; // Kuning untuk 51-60%
            } else if (percentage <= 80) {
                return 'green'; // Hijau untuk 61-80%
            } else {
                return 'lightgreen'; // Hijau terang untuk lebih dari 80% (opsional)
            }
        }


        function addToLegend(label, color, layerName) {
            const legendList = document.getElementById('legend-list');
            const existingEntry = Array.from(legendList.children).find(
                (li) => li.textContent.includes(label) && li.dataset.layer === layerName
            );
            if (existingEntry) return;

            const legendItem = document.createElement('li');
            legendItem.className = `legend-item`;
            legendItem.dataset.layer = layerName;
            legendItem.innerHTML = `
        <div class="legend-color" style="background-color: ${color};"></div>
        ${label}
    `;
            legendList.appendChild(legendItem);
        }

        function removeLegendItems(layerName) {
            const legendList = document.getElementById('legend-list');
            const legendItems = legendList.querySelectorAll(`[data-layer="${layerName}"]`);
            legendItems.forEach(item => item.remove());
        }


        function toggleLegendVisibility(type, isVisible) {
            const legendItems = document.querySelectorAll(`[data-layer="${type}"]`);
            legendItems.forEach(item => {
                item.style.display = isVisible ? 'block' : 'none';
            });
        }

        function createFeatureModal(feature, layerName) {
            // Buat kontainer modal
            const modalContainer = document.createElement('div');
            modalContainer.innerHTML = `
        <div id="feature-modal" style="
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
            max-width: 500px;
            width: 90%;
            max-height: 80%;
            overflow-y: auto;
        ">
            <div style="
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                border-bottom: 2px solid #f0f0f0;
                padding-bottom: 10px;
            ">
                <h2 style="margin: 0; color: #333;">${layerName}</h2>
                <button id="close-modal" style="
                    background: none;
                    border: none;
                    font-size: 24px;
                    color: #888;
                    cursor: pointer;
                    transition: color 0.3s ease;
                " onmouseover="this.style.color='#000'" onmouseout="this.style.color='#888'">
                    ×
                </button>
            </div>
            
            <div id="feature-details">
                ${renderFeaturePropertiesTable(feature)}
            </div>
        </div>
        
        <div id="modal-overlay" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        "></div>
    `;

            document.body.appendChild(modalContainer);

            // Tambahkan event listener untuk menutup modal
            document.getElementById('close-modal').addEventListener('click', closeModal);
            document.getElementById('modal-overlay').addEventListener('click', closeModal);
        }

        function renderFeaturePropertiesTable(feature) {
            // Tampilkan properti fitur dalam format tabel yang rapi
            if (!feature.properties) return '<p>Tidak ada informasi tambahan.</p>';

            let propertiesHTML = `
        <table style="
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        ">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="
                        padding: 12px; 
                        text-align: left; 
                        border-bottom: 2px solid #dee2e6;
                        color: #495057;
                    ">Atribut</th>
                    <th style="
                        padding: 12px; 
                        text-align: left; 
                        border-bottom: 2px solid #dee2e6;
                        color: #495057;
                    ">Keterangan</th>
                </tr>
            </thead>
            <tbody>
    `;

            // Urutkan kunci agar 'kecamatan' atau 'nama' muncul di atas
            const sortedKeys = Object.keys(feature.properties).sort((a, b) => {
                const priorityKeys = ['kecamatan', 'nama', 'desa'];
                const aPriority = priorityKeys.indexOf(a.toLowerCase());
                const bPriority = priorityKeys.indexOf(b.toLowerCase());

                if (aPriority > -1 && bPriority === -1) return -1;
                if (aPriority === -1 && bPriority > -1) return 1;
                return aPriority - bPriority;
            });

            sortedKeys.forEach(key => {
                const value = feature.properties[key] || 'N/A';
                propertiesHTML += `
            <tr style="border-bottom: 1px solid #e9ecef;">
                <td style="
                    padding: 10px; 
                    font-weight: 600; 
                    color: #212529;
                    text-transform: capitalize;
                ">${key}</td>
                <td style="
                    padding: 10px; 
                    color: #495057;
                ">${value}</td>
            </tr>
        `;
            });

            propertiesHTML += `
            </tbody>
        </table>
    `;

            return propertiesHTML;
        }

        function closeModal() {
            const modal = document.getElementById('feature-modal');
            const overlay = document.getElementById('modal-overlay');

            if (modal) modal.remove();
            if (overlay) overlay.remove();
        }

        var latitude = -7.55555589792736;
        var longitude = 112.22681069495013;
        var zoomLevel = 10;

        var resetButton = L.Control.extend({
            options: {
                position: 'topright'
            },

            onAdd: function(map) {
                var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                var link = L.DomUtil.create('a', 'leaflet-control-reset', container);
                link.href = '#';
                link.title = 'Reset View';

                // Tambahkan ikon Font Awesome ke tombol
                var icon = L.DomUtil.create('i', 'fa-solid fa-dove', link);

                L.DomEvent.on(link, 'click', function(e) {
                    L.DomEvent.stopPropagation(e);

                    // Kembalikan ke koordinat dan zoom awal
                    map.setView([latitude, longitude], zoomLevel);
                });

                return container;
            }
        });
        map.addControl(new resetButton());
    </script>
</body>

</html>
