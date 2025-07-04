<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPASI Laravel</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">

    {{-- Tambahkan CSS kustom --}}
    <link rel="stylesheet" href="{{ asset('css/skema.css') }}">

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- GSAP + Draggable --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Draggable.min.js"></script>
</head>

<body class="bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">SIPASI</a>
        </div>
    </nav>

    {{-- Content --}}
    <main class="container-fluid p-3">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Petak
            Draggable.create(".petak-draggable", {
                type: "x,y",
                onDragEnd: function() {
                    const el = this.target;
                    const id = $(el).data('id');
                    const svg = document.querySelector("svg");

                    const bbox = el.getBoundingClientRect();
                    const svgPoint = screenToSVGCoords(svg, bbox.left, bbox.top);

                    el.setAttribute("x", svgPoint.x);
                    el.setAttribute("y", svgPoint.y);

                    gsap.set(el, {
                        x: 0,
                        y: 0
                    });

                    $.ajax({
                        url: `/api/petak/${id}`,
                        method: 'PUT',
                        data: {
                            x: svgPoint.x,
                            y: svgPoint.y,
                            _token: '{{ csrf_token() }}'
                        }
                    });
                }
            });
        });


        // {{-- Script AJAX & GSAP --}}

        function showDetail(id) {
            $.get('/api/bangunan/' + id, function(data) {
                $('#g_info').html(`
                <h5>${data.name}</h5>
                <p>Koordinat: ${data.x}, ${data.y}</p>
            `);
                $('#leftmenu').show();
            });
        }

        $(document).ready(function() {
            Draggable.create(".bangunan-draggable", {
                type: "x,y",
                onDragEnd: function() {
                    const el = this.target;
                    const id = $(el).data('id');
                    const svg = document.querySelector("svg");

                    const bbox = el.getBoundingClientRect();
                    const centerX = bbox.left + bbox.width / 2;
                    const centerY = bbox.top + bbox.height / 2;

                    const svgPoint = screenToSVGCoords(svg, centerX, centerY);

                    // Update atribut koordinat SVG
                    el.setAttribute("cx", svgPoint.x);
                    el.setAttribute("cy", svgPoint.y);

                    // Hapus transform translate yang ditambahkan GSAP
                    gsap.set(el, {
                        x: 0,
                        y: 0
                    });

                    // Simpan ke DB
                    $.ajax({
                        url: `/api/bangunan/${id}`,
                        method: 'PUT',
                        data: {
                            x: svgPoint.x,
                            y: svgPoint.y,
                            _token: '{{ csrf_token() }}'
                        }
                    });
                }
            });
        });

        function screenToSVGCoords(svg, screenX, screenY) {
            const pt = svg.createSVGPoint();
            pt.x = screenX;
            pt.y = screenY;
            return pt.matrixTransform(svg.getScreenCTM().inverse());
        }

        Draggable.create(".saluran-draggable", {
            type: "x,y",
            onDragEnd: function() {
                const el = this.target;
                const id = $(el).data('id');
                const svg = document.querySelector("svg");

                const bbox = el.getBoundingClientRect();
                const topLeftScreen = {
                    x: bbox.left,
                    y: bbox.top
                };

                const svgStart = screenToSVGCoords(svg, topLeftScreen.x, topLeftScreen.y);

                // Hitung offset antara posisi lama dengan posisi baru (pergerakan)
                const dx = svgStart.x - parseFloat(el.getAttribute("x1"));
                const dy = svgStart.y - parseFloat(el.getAttribute("y1"));

                const newX1 = parseFloat(el.getAttribute("x1")) + dx;
                const newY1 = parseFloat(el.getAttribute("y1")) + dy;
                const newX2 = parseFloat(el.getAttribute("x2")) + dx;
                const newY2 = parseFloat(el.getAttribute("y2")) + dy;

                el.setAttribute("x1", newX1);
                el.setAttribute("y1", newY1);
                el.setAttribute("x2", newX2);
                el.setAttribute("y2", newY2);
                gsap.set(el, {
                    x: 0,
                    y: 0
                });

                $.ajax({
                    url: `/api/saluran/${id}`,
                    type: 'PUT',
                    data: {
                        x1: newX1,
                        y1: newY1,
                        x2: newX2,
                        y2: newY2,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        console.log('Saluran disimpan:', res);
                    }
                });
            }
        });

        function showInfoPetak(id) {
            $.get('/api/petak/' + id, function(data) {
                $('#g_info').html(`
            <h5>${data.name}</h5>
            <p>Posisi: (${data.x}, ${data.y})</p>
            <p>Ukuran: ${data.width} x ${data.height}</p>
            <p>Golongan: ${data.golongan}</p>
        `);
                $('#leftmenu').show();
            });
        }

        function showInfoSaluran(id) {
            $.get('/api/saluran/' + id, function(data) {
                $('#g_info').html(`
            <h5>${data.name}</h5>
            <p>Dari: (${data.x1}, ${data.y1})</p>
            <p>Ke: (${data.x2}, ${data.y2})</p>
        `);
                $('#leftmenu').show();
            });
        }
    </script>

</body>

</html>
