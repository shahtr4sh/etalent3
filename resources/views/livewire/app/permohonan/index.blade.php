<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Permohonan Kenaikan Pangkat</h1>
            <p class="text-sm text-gray-500">Senarai permohonan anda (aktif dan tidak aktif).</p>
        </div>

        <div>
            <a href="{{ route('app.permohonan.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700">
                Permohonan Baharu
            </a>
        </div>
    </div>

    {{-- TABLE PERMOHONAN SAHAJA --}}
    <div class="bg-white border rounded-xl overflow-hidden">
        <div class="px-4 py-3 border-b text-sm text-gray-600">
            Senarai Permohonan
        </div>

        @if(empty($applications))
            <div class="p-10 text-center">
                <div class="text-gray-800 font-medium">Tiada permohonan.</div>
                <div class="text-sm text-gray-500 mt-1">Sila klik “Permohonan Baharu” untuk mula.</div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-600">
                    <tr class="border-b">
                        <th class="px-4 py-3">Reference No</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aktif</th>
                        <th class="px-4 py-3">Tarikh Cipta</th>
                        <th class="px-4 py-3">Tindakan</th>
                    </tr>
                    </thead>

                    <tbody class="text-gray-800">
                    @foreach($applications as $row)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $row['reference_no'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['status'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                {{ !empty($row['is_active']) ? 'Aktif' : 'Tidak Aktif' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($row['created_at'])->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('app.permohonan.show', $row['id']) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg border hover:bg-gray-100">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        @endif
    </div>
</div>
