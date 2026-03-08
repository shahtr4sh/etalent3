<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Lihat Permohonan</h1>
            <p class="text-sm text-gray-500">Maklumat permohonan anda.</p>
        </div>

        <a href="{{ route('app.permohonan.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-lg border hover:bg-gray-100">
            Kembali
        </a>
    </div>

    @if(!$application)
        <div class="p-4 border rounded-lg bg-red-50 text-red-800">
            Rekod permohonan tidak dijumpai atau anda tidak mempunyai akses.
        </div>
    @else
        @php
            $meta = $statusMeta[$statusKey] ?? $statusMeta['TIADA_STATUS'];
        @endphp

        {{-- PANEL STATUS --}}
        <div class="mb-6">
            <div class="border rounded-xl p-4 {{ $meta['bg'] }} {{ $meta['border'] }}">
                <div class="text-sm font-medium {{ $meta['text'] }}">
                    Status Permohonan
                </div>
                <div class="text-xl font-semibold {{ $meta['text'] }}">
                    {{ $meta['label'] }}
                </div>
            </div>
        </div>

        {{-- Maklumat ringkas --}}
        <div class="bg-white border rounded-xl p-6 space-y-2">
            <p><strong>Reference No:</strong> {{ $application['reference_no'] ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $application['status'] ?? '-' }}</p>
            <p><strong>Aktif:</strong> {{ !empty($application['is_active']) ? 'Aktif' : 'Tidak Aktif' }}</p>
            <p><strong>Gred Dipohon:</strong> {{$application['gred_jawatan'] ?? '-'}}</p>
            <p><strong>Tarikh Cipta:</strong> {{ \Carbon\Carbon::parse($application['created_at'])->format('d/m/Y H:i') }}</p>
        </div>
    @endif
</div>
