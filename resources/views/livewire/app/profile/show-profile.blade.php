<div>
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">

        {{-- Header dengan Gambar Profile Kiri dan Button CV Kanan --}}
        <div class="flex items-center justify-between mb-6">
            {{-- KIRI: Gambar Profile --}}
            <div class="flex items-center gap-4">
                <div class="relative">
                    @php
                        $profileImage = null;
                        if ($pemohon && $pemohon->gambar_profil) {
                            $imagePath = storage_path('app/public/' . $pemohon->gambar_profil);
                            if (file_exists($imagePath)) {
                                $imageData = file_get_contents($imagePath);
                                $profileImage = 'data:image/jpeg;base64,' . base64_encode($imageData);
                            }
                        }
                    @endphp
                    @if($profileImage)
                        <img src="{{ $profileImage }}"
                             alt="Profile"
                             class="w-32 h-32 rounded-full object-cover border-2 border-primary-200 shadow-sm">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-primary-100 to-purple-100 flex items-center justify-center border-2 border-primary-200 shadow-sm">
                            <span class="text-4xl font-bold text-primary-600">
                                {{ $pemohon ? substr($pemohon->nama, 0, 1) : '?' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KANAN: Button CV --}}
            @if($pemohon)
                <a href="{{ route('app.profil.cv', ['staff_id' => $pemohon->staff_id]) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Generate CV</span>
                </a>
            @endif
        </div>

        @if($pemohon)
            {{-- INFO ASAS - Susunan Baru: Nama, ID, Email, Jawatan Hakiki --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- KIRI: Nama, ID, Email --}}
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-semibold text-lg">
                            @if($pemohon->gelaran)
                                {{ $pemohon->gelaran->gelaran }} {{ $pemohon->nama }}
                            @else
                                {{ $pemohon->nama }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Staff ID</p>
                        <p class="font-semibold">{{ $pemohon->staff_id }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Official Email</p>
                        <p class="font-semibold">{{ $pemohon->emel_rasmi }}</p>
                    </div>

                    {{-- JABATAN --}}
                    <div>
                        <p class="text-sm text-gray-500">Department</p>
                        @if($pemohon->jabatanStaf)
                            <div class="flex items-baseline gap-2 flex-wrap">
                                <p class="font-medium text-gray-900 text-base">
                                    {{ $pemohon->jabatanStaf->nama_jabatan}}
                                </p>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No Record</p>
                        @endif
                    </div>

                    {{-- JAWATAN HAKIKI --}}
                    <div>
                        <p class="text-sm text-gray-500">Current Position</p>
                        @if($pemohon->jawatanStafTerkini)
                            <div class="flex items-baseline gap-2 flex-wrap">
                                <p class="font-medium text-gray-900 text-base">
                                    {{ $pemohon->jawatanStafTerkini->nama_jawatan ?? '-' }}
                                </p>
                                <p class="text-gray-700 font-semibold">
                                    - {{ $pemohon->jawatanStafTerkini->kod_kumpulan ?? '' }}{{ $pemohon->jawatanStafTerkini->gred_jawatan ?? '' }}
                                </p>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No Record</p>
                        @endif
                    </div>
                </div>

                {{-- Markah Purata Laporan Nilaian Prestasi Tahunan  --}}
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500 mb-3">Evaluation Mark: Laporan Nilaian Prestasi Tahunan (LNPT) </p>

                        @if($semuaMarkah->isNotEmpty())
                            <div class="space-y-2">
                                @foreach($semuaMarkah as $index => $markah)
                                    <div class="flex items-center justify-between p-2 {{ $index === 0 ? 'bg-white rounded-lg shadow-sm' : 'border-b border-gray-100 last:border-0' }}">
                                        <div class="flex items-center gap-3">
                                            @if($index === 0)
                                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                            @endif
                                            <span class="text-sm {{ $index === 0 ? 'font-semibold' : 'text-gray-600' }}">
                                                {{ $markah->tahun_markah }}
                                            </span>
                                        </div>
                                        <span class="font-bold {{ $markah->jum_mark >= 80 ? 'text-green-600' : 'text-red-600' }} {{ $index === 0 ? 'text-lg' : 'text-base' }}">
                                            {{ number_format($markah->jum_mark, 2) }}%
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 italic text-sm">No LNPT mark recorded.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- REKOD AKADEMIK --}}
            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-3">Academic Record</h2>
                <hr class="mb-4">
                @if($pemohon->akademikStaf->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Academic Level</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year Completed</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Field of Study</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pemohon->akademikStaf as $a)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 text-sm">{{ $a->tahap_akademik ?? '—' }}</td>
                                    <td class="px-5 py-4 text-sm">{{ $a->tahun_tamat ?? '—' }}</td>
                                    <td class="px-5 py-4 text-sm">{{ $a->kod_bidang ?? '—' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 italic">No academic past recorded.</p>
                @endif
            </div>

            {{-- PERFORMANCE EVALUATION --}}
            <div class="mt-8">
                <div class="flex items-center gap-3 mb-3">
                    <h2 class="text-lg font-semibold">Service Evaluation</h2>
                </div>
                <hr class="mb-4">

                @if($performanceEvaluations->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mark</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($performanceEvaluations as $performance)
                                @php
                                    $statusColor = match(true) {
                                        $performance->performance_mark >= 90 => 'text-green-600',
                                        $performance->performance_mark >= 80 => 'text-blue-600',
                                        $performance->performance_mark >= 70 => 'text-yellow-600',
                                        default => 'text-red-600'
                                    };
                                    $statusBg = match(true) {
                                        $performance->performance_mark >= 90 => 'bg-green-100',
                                        $performance->performance_mark >= 80 => 'bg-blue-100',
                                        $performance->performance_mark >= 70 => 'bg-yellow-100',
                                        default => 'bg-red-100'
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 text-sm font-medium">{{ $performance->year }}</td>
                                    <td class="px-5 py-4 text-sm">
                                <span class="font-bold {{ $statusColor }}">
                                    {{ number_format($performance->performance_mark, 1) }}%
                                </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm">
                                <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $statusBg }} {{ $statusColor }}">
                                    {{ $performance->status_text }}
                                </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary Stats --}}
                    @php
                        $avgMark = $performanceEvaluations->avg('performance_mark');
                        $trend = $performanceEvaluations->count() > 1
                            ? $performanceEvaluations->first()->performance_mark - $performanceEvaluations->last()->performance_mark
                            : 0;
                    @endphp

                @else
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <p class="text-gray-500 italic">No service evaluation mark.</p>
                    </div>
                @endif
            </div>

            {{-- DISCIPLINARY RECORD - CARD VIEW --}}
            <div class="mt-8">
                <div class="flex items-center gap-3 mb-3">
                    <h2 class="text-lg font-semibold">Disciplinary Record</h2>
                    <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
            {{ $tatatertib->count() }} Record(s)
        </span>
                </div>
                <hr class="mb-4">

                @if($tatatertib->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($tatatertib as $record)
                            <div class="bg-white border-l-4 border-red-400 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <p class="text-sm text-gray-900">{{ $record->description }}</p>
                                            <span class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                    {{ \Carbon\Carbon::parse($record->tarikh_conduct)->format('d/m/Y') }}
                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-8 text-center border border-gray-200">
                        <p class="text-gray-500 italic">No disciplinary record</p>
                    </div>
                @endif
            </div>

            {{-- REKOD PENYELIAAN --}}
            <div class="mt-8">
                <div class="flex items-center gap-3 mb-3">
                    <h2 class="text-lg font-semibold">Supervision Record</h2>
                    <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
            {{ $penyeliaan->count() }} Thesis Supervised
        </span>
                </div>
                <hr class="mb-4">

                {{-- Jadual 1: Penyelia Utama --}}
                <div class="mb-8">
                    <h3 class="text-base font-semibold text-gray-800 mb-2">Lead Supervisor</h3>

                    @if(collect($penyeliaanUtama)->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thesis Title</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supervision Type</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($penyeliaanUtama as $tesis)
                                    @php
                                        $programName = $tesis->program?->namaprog_bm ?? $tesis->kod_prog ?? '-';
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-4 text-sm">{{ $tesis->tajuk ?? '-' }}</td>
                                        <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        Lead Supervisor
                                    </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm">{{ $programName }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No record as lead supervisor.</p>
                    @endif
                </div>

                {{-- Jadual 2: Penyelia Bersama --}}
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-2">Co-supervisor</h3>

                    @if(collect($penyeliaanBersama)->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thesis Title</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supervision Type</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($penyeliaanBersama as $tesis)
                                    @php
                                        $programName = $tesis->program?->namaprog_bm ?? $tesis->kod_prog ?? '-';
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-4 text-sm">{{ $tesis->tajuk ?? '-' }}</td>
                                        <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Co-supervisor
                                    </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm">{{ $programName }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No record as co-supervisor.</p>
                    @endif
                </div>
            </div>

            {{-- REKOD PENERBITAN --}}
            <div class="mt-8">
                <div class="flex items-center gap-3 mb-3">
                    <h2 class="text-lg font-semibold">Publications</h2>
                    <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
                        {{ $totalPenerbitan }} Publications
                    </span>
                </div>
                <hr class="mb-4">
                @if($penerbitan->isNotEmpty())
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publish Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Index</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($penerbitan as $pub)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm" colspan="2">
                                        <div>
                                            {{ $pub->formatted_authors }}
                                            ({{ $pub->tahun ?? 'n.d.' }}).
                                            {{ $pub->formatted_title }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($pub->indexes as $index)
                                                <span class="inline-block px-2 py-1 text-xs rounded-full bg-{{ $index->name == 'WOS' ? 'red' : ($index->name == 'Scopus' ? 'blue' : 'gray') }}-100 text-{{ $index->name == 'WOS' ? 'red' : ($index->name == 'Scopus' ? 'blue' : 'gray') }}-800">
                                                    {{ $index->name }}
                                                </span>
                                            @empty
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                            {{ ucfirst(str_replace('_', ' ', $pub->type ?? 'other')) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <p class="text-gray-500 italic">No publication record.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
