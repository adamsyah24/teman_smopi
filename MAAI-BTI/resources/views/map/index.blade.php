@extends('layouts.app')

@section('content')
    <h2>Peta Irigasi</h2>

    {{-- SVG dan elemen interaktif lainnya akan di sini --}}
    <svg width="1000" height="1000" viewBox="0 0 1000 1000">
        {{-- Bangunan --}}
        @foreach ($bangunans as $b)
            <circle class="bangunan-draggable" data-id="{{ $b->id }}" cx="{{ $b->x }}" cy="{{ $b->y }}"
                r="16" fill="#0066dd" />
        @endforeach

        {{-- Saluran --}}
        @foreach ($salurans as $s)
            {{-- Garis saluran --}}
            <line id="saluran-{{ $s->id }}" class="saluran-draggable" onclick="showInfoSaluran({{ $s->id }})" data-id="{{ $s->id }}"
                x1="{{ $s->x1 }}" y1="{{ $s->y1 }}" x2="{{ $s->x2 }}" y2="{{ $s->y2 }}"
                stroke="#4499ee" stroke-width="6" />
        @endforeach


        {{-- Petak --}}
        @foreach ($petaks as $p)
            <rect  onclick="showInfoPetak({{ $p->id }})" class="petak-draggable golongan_{{ $p->golongan }}" data-id="{{ $p->id }}"
                data-golongan="{{ $p->golongan }}" x="{{ $p->x }}" y="{{ $p->y }}"
                width="{{ $p->width }}" height="{{ $p->height }}" fill="#bbffee" stroke="#334455"
                stroke-width="2" />
        @endforeach
    </svg>


    <div id="leftmenu"
        style="display: none; position: absolute; top: 50px; left: 50px; background: #fff; padding: 10px; border: 1px solid #ccc;">
        <button onclick="$('#leftmenu').hide()">Tutup</button>
        <div id="g_info"></div>
    </div>
@endsection
