<div>
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold mb-4">Profil Staf</h1>

        {{-- BUTTON CV --}}
        <a href="{{ route('app.profil.cv') }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Muat Turun CV
        </a>
    </div>

        @if($pemohon)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nama --}}
                <div>
                    <p class="text-sm text-gray-500">Nama Penuh</p>
                    <p class="font-semibold text-lg">
                        @if($pemohon->gelaran)
                            {{ $pemohon->gelaran->gelaran }} {{ $pemohon->nama }}
                        @else
                            {{ $pemohon->nama }}
                        @endif
                    </p>
                </div>

                {{-- Staff ID --}}
                <div>
                    <p class="text-sm text-gray-500">Staff ID</p>
                    <p class="font-semibold">{{ $pemohon->staff_id }}</p>
                </div>

                {{-- Emel --}}
                <div>
                    <p class="text-sm text-gray-500">Emel Rasmi</p>
                    <p class="font-semibold">{{ $pemohon->emel_rasmi }}</p>

                    @if($pemohon->markahTerkini)
                        @php $markah = $pemohon->markahTerkini; @endphp
                        <div class="col-span-2 md:col-span-1 mt-2">
                            <p class="text-sm text-gray-500">Markah Prestasi</p>
                            <p class="text-xl font-bold {{ $markah->jum_mark >= 80 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($markah->jum_mark, 2) }}%
                            </p>
                        </div>
                    @endif
                </div>

                {{-- === JAWATAN HAKIKI === --}}
                <div class="col-span-2 md:col-span-1 bg-purple-50 p-4 rounded-lg border border-purple-200 mt-2">
                    <p class="text-sm text-purple-700 font-semibold mb-2">JAWATAN HAKIKI (SEMASA)</p>

                    @if($pemohon->jawatanStafTerkini)
                        <div class="flex items-baseline gap-2">
                            <p class="font-medium text-purple-800 text-base">
                                {{ $pemohon->jawatanStafTerkini->nama_jawatan ?? '-' }}
                            </p>
                            <p class="text-purple-600 font-semibold">
                                - {{ $pemohon->jawatanStafTerkini->kod_kumpulan ?? '' }} {{ $pemohon->jawatanStafTerkini->gred_jawatan ?? '' }}
                            </p>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Tiada rekod jawatan semasa</p>
                    @endif

                </div>
            </div>
        @endif


        {{-- Rekod Jawatan Staf (Sejarah) --}}
        <div class="mt-6">
            <p class="text-lg font-semibold">Rekod Jawatan Staf</p>
            <hr class="my-2">
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-neutral-200">
                                <thead>
                                <tr class="text-neutral-500">
                                    <th class="px-5 py-3 text-sm font-medium text-left uppercase">Jawatan</th>
                                    <th class="px-5 py-3 text-sm font-medium text-left uppercase">Gred</th>
                                    <th class="px-5 py-3 text-sm font-medium text-left uppercase">Status</th>
                                </tr>
                                </thead>
                                @foreach($pemohon->jawatanStaf as $j)
                                    <tbody class="divide-y divide-neutral-200">
                                    <tr class="text-neutral-800">
                                        <td class="px-5 py-4 text-xs font-medium whitespace-nowrap">{{ $j->nama_jawatan ?? '-' }}</td>
                                        <td class="px-5 py-4 text-xs whitespace-nowrap">{{ $pemohon->jawatanStafTerkini->kod_kumpulan ?? '' }} {{ $j->gred_jawatan ?? '-' }}</td>
                                        <td class="px-5 py-4 text-xs whitespace-nowrap">
                                            @if($j->terkini)
                                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Aktif</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekod Akademik --}}
        <div class="mt-6">
            <p class="text-lg font-semibold">Rekod Akademik Staf</p>
            <hr class="my-2">
            @if($pemohon->akademikStaf->isEmpty())
                <p style="text-indent: 40px;">Tiada rekod akademik.</p>
            @else
                <div class="flex flex-col">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-neutral-200">
                                    <thead>
                                    <tr class="text-neutral-500">
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase">Tahap Akademik</th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase">Tahun Tamat</th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase">Bidang</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200">
                                    @foreach($pemohon->akademikStaf as $a)
                                        <tr class="text-neutral-800">
                                            <td class="px-5 py-4 text-xs font-medium whitespace-nowrap">{{ $a->tahap_akademik ?? '—' }}</td>
                                            <td class="px-5 py-4 text-xs whitespace-nowrap">{{ $a->tahun_tamat ?? '—' }}</td>
                                            <td class="px-5 py-4 text-xs whitespace-normal break-words max-w-75">{{ $a->kod_bidang ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Rekod Penyeliaan Tesis --}}
        <div class="mt-6">
            <div class="flex items-center gap-3 mb-2">
                <p class="text-lg font-semibold">Rekod Penyeliaan Tesis</p>
                <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $penyeliaan->count() }} Tesis Diselia
                </span>
            </div>

            <hr class="mb-2">
            @if($penyeliaan->isEmpty())
                <p style="text-indent: 40px;">Tiada rekod penyeliaan.</p>
            @else
                <div class="flex flex-col">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-neutral-200">
                                    <thead>
                                    <tr class="text-neutral-500">
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase">Tajuk Tesis</th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase">Jenis Penyelia</th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase">Program</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200">
                                    @foreach($penyeliaan as $tesis)
                                        @php
                                            $staffId = $pemohon->staff_id;
                                            $jenisPenyelia = $tesis->getJenisPenyelia($staffId);
                                            $programName = $tesis->program?->namaprog_bm ?? $tesis->kod_prog ?? '-';
                                        @endphp
                                        <tr class="text-neutral-800">
                                            <td class="px-5 py-4 text-xs whitespace-normal break-words max-w-75">{{ $tesis->tajuk ?? '-' }}</td>
                                            <td class="px-5 py-4 text-xs whitespace-nowrap">
                                            <span class="px-2 py-1 rounded text-xs
                                                {{ $jenisPenyelia == 'Penyelia Utama' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $jenisPenyelia }}
                                            </span>
                                            </td>
                                            <td class="px-5 py-4 text-xs whitespace-nowrap">{{ $programName }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- REKOD PENERBITAN --}}
    <div class="mt-8">
        {{-- Header dengan count dan search --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <p class="text-lg font-semibold">Penerbitan</p>
                <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
                {{ $totalPenerbitan }} Penerbitan
            </span>
            </div>

            {{-- Kotak Carian --}}
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchPenerbitan"
                    placeholder="Carian..."
                    class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500"
                >
                <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <hr class="mb-4">

        @if($penerbitan->isEmpty())
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-500 italic">Tiada rekod penerbitan.</p>
            </div>
        @else
            {{-- Info "Memaparkan X senarai" --}}
            <div class="text-sm text-gray-600 mb-3">
                Memaparkan <span class="font-semibold">{{ $penerbitan->count() }}</span> senarai
            </div>

            {{-- TABLE PENERBITAN DENGAN COLUMN INDEKS --}}
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publication Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Index</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publication Type</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($penerbitan as $pub)
                        @php
                            // Tentukan peranan berdasarkan authors
                            $staffId = $pemohon->staff_id;
                            $author = $pub->authors->firstWhere('nostaf', $staffId);
                            $peranan = $author ? 'Ketua Projek' : 'Penyelidik Bersama'; // Adjust logic ikut data

                            // Format indeks
                            $indexes = $pub->indexes->pluck('name')->toArray();
                            $indexBadges = [];
                            foreach ($indexes as $index) {
                                $color = match($index) {
                                    'WOS' => 'bg-red-100 text-red-800',
                                    'Scopus' => 'bg-blue-100 text-blue-800',
                                    'ERA' => 'bg-green-100 text-green-800',
                                    'MyCite' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $indexBadges[] = '<span class="inline-block px-2 py-1 text-xs rounded-full ' . $color . '">' . $index . '</span>';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ $pub->title }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $pub->formatted_authors }} ({{ $pub->tahun ?? 'n.d.' }})
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                @if($pub->publish_date)
                                    {{ \Carbon\Carbon::parse($pub->publish_date)->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($pub->indexes as $index)
                                        @php
                                            $color = match($index->name) {
                                                'WOS' => 'bg-red-100 text-red-800',
                                                'Scopus' => 'bg-blue-100 text-blue-800',
                                                'ERA' => 'bg-green-100 text-green-800',
                                                'MyCite' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-block px-2 py-1 text-xs rounded-full {{ $color }}">
                                            {{ $index->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                @php
                                    $type = $pub->type ?? 'other';
                                     $typeColors = [
                                        'journal' => 'bg-purple-100 text-purple-800',
                                        'book' => 'bg-blue-100 text-blue-800',
                                        'book_chapter' => 'bg-indigo-100 text-indigo-800',
                                        'proceeding' => 'bg-orange-100 text-orange-800',
                                        'other' => 'bg-gray-100 text-gray-800',
                                    ];

                                     $typeLabels = [
                                        'journal' => 'Journal',
                                        'book' => 'Book',
                                        'book_chapter' => 'Book Chapter',
                                        'proceeding' => 'Proceeding',
                                        'other' => 'Other Publication',
                                    ];
                                    $color = $typeColors[$type] ?? 'bg-gray-100 text-gray-800';
                                    $label = $typeLabels[$type] ?? ucfirst(str_replace('_', ' ', $type));

                                @endphp
                                {{-- PUBLICATION TYPE dari column 'type' --}}
                                <span class="px-2 py-1 text-xs rounded-full {{ $color }}">
                                    {{ $label }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

