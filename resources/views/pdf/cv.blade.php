<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Curriculum Vitae - {{ $staff->nama_dengan_gelaran }}</title>
    <style>

        * {
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* Kalau nak RTL untuk Arabic sections je */
        .arabic-section {
            direction: rtl;
            text-align: right;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-right: 25px;
            overflow: hidden;
            background-color: #f0f0f0;
            border: 2px solid #2c3e50;
            flex-shrink: 0;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 10px;
            font-size: 10pt;
        }
        .header {
            margin-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 16pt;
            color: #2c3e50;
        }
        .header .subtitle {
            font-size: 12pt;
            color: #7f8c8d;
            margin: 2px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .content-item {
            margin-bottom: 8px;
            font-size: 10pt;
            line-height: 1.5;
        }
        .academic-item {
            margin-bottom: 6px;
        }
        .publication-item {
            margin-bottom: 8px;
        }
        .supervision-item {
            margin-bottom: 8px;
        }
        .small-note {
            color: #7f8c8d;
            font-size: 9pt;
            font-style: italic;
        }
        footer {
            margin-top: 30px;
            text-align: center;
            color: #95a5a6;
            font-size: 9pt;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header" >
    {{-- Profile Image --}}
    <div class="profile-image">
        @if(!empty($profileImage))
            <img src="{{ $profileImage }}" alt="">
        @else
            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#f0f0f0; border-radius:50%;">
                <span style="font-size:24px; color:#7f8c8d;">👤</span>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div style="flex:1;">
        <h1 style="margin:0 0 5px 0;">{{ $staff->nama_dengan_gelaran }}</h1>
        <div style="font-size:12pt; margin:2px 0;">
            {{ $staff->jawatanStafTerkini->nama_jawatan ?? 'N/A' }} ({{ $staff->jawatanStafTerkini->gred_jawatan ?? 'N/A' }})
        </div>
        <div style="font-size:12pt; margin:2px 0;">
            {{ $staff->jabatanStaf->nama_jabatan ?? 'N/A' }}
        </div>
        <div style="font-size:12pt; margin:2px 0;">
            {{ $staff->emel_rasmi ?? 'N/A'}}
        </div>
    </div>
</div>

{{-- 1. ACADEMIC QUALIFICATION --}}
<div class="section">
    <div class="section-title">Academic Qualification</div>
    @forelse($staff->akademikStaf as $akademik)
        <div class="content-item academic-item">
            {{ $akademik->kod_bidang ?? '' }},
            {{ $akademik->tahap_akademik ?? 'N/A' }},
            {{ $akademik->tahun_tamat ?? 'N/A' }}
        </div>
    @empty
        <div class="content-item">No academic records found.</div>
    @endforelse
</div>

{{-- 2. PUBLICATIONS (APA Format) --}}
<div class="section">
    <div class="section-title">Publications</div>
    @forelse($staff->penerbitan as $pub)
        <div class="content-item publication-item">
            {{ $pub->formatted_authors }}
            ({{ $pub->tahun ?? 'n.d.' }}).
            {{ $pub->title }}
        </div>
    @empty
        <div class="content-item">No publications found.</div>
    @endforelse
</div>

{{-- 3. SUPERVISION --}}
<div class="section">
    <div class="section-title">Supervision</div>

    {{-- PhD/Doctoral Students --}}
    @if($phdSupervisions->count() > 0)
        @foreach($phdSupervisions as $sup)
            <div class="content-item supervision-item">
                {{ $sup->tajuk }};
                @if($sup->penyelia_bersama)
                    Co-supervisor: {{ $sup->penyelia_bersama }};
                @endif
                {{ $sup->program->namaprog_bm ?? $sup->kod_prog ?? 'N/A' }}
            </div>
        @endforeach
    @endif

    {{-- Master Students --}}
    @if($masterSupervisions->count() > 0)
        @foreach($masterSupervisions as $sup)
            <div class="content-item supervision-item">
                {{ $sup->tajuk }};
                @if($sup->penyelia_bersama)
                    Co-supervisor: {{ $sup->penyelia_bersama }};
                @endif
                {{ $sup->program->namaprog_bm ?? $sup->kod_prog ?? 'N/A' }}
            </div>
        @endforeach
    @endif

    {{-- Bachelor/Other Students --}}
    @if($degreeSupervisions->count() > 0)
        @foreach($degreeSupervisions as $sup)
            <div class="content-item supervision-item">
                {{ $sup->tajuk }};
                @if($sup->penyelia_bersama)
                    Co-supervisor: {{ $sup->penyelia_bersama }};
                @endif
                {{ $sup->program->namaprog_bm ?? $sup->kod_prog ?? 'N/A' }}
            </div>
        @endforeach
    @endif

    @if($phdSupervisions->isEmpty() && $masterSupervisions->isEmpty() && $degreeSupervisions->isEmpty())
        <div class="content-item">No supervision records found.</div>
    @endif
</div>

<footer>
    Generated by eTalent System on {{ date('d/m/Y') }}
</footer>
</body>
</html>
