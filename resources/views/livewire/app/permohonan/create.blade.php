<div class="min-h-screen p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold mb-1">Permohonan Baharu</h1>
    <p class="text-sm text-gray-500 mb-6">Sila pilih jawatan yang ingin dipohon.</p>

    @if(session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border p-6">
        <form wire:submit.prevent="submit">

            {{-- PILIH JAWATAN --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Jawatan yang ingin dipohon</label>
                <select wire:model.live="selected_jawatan_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">-- Sila pilih --</option>
                    @foreach($jawatanOptions as $opt)
                        <option value="{{ $opt['value'] }}"
                                @if(!$opt['has_kelayakan']) class="text-gray-400" @endif>
                            {{ $opt['label'] }}
                            @if(!$opt['has_kelayakan'])
                                (Kelayakan belum ditetapkan)
                            @endif
                        </option>
                    @endforeach
                </select>

                @if($selected_gred)
                    <div class="mt-2 text-sm text-green-600">
                        Gred dipohon: <span class="font-semibold">{{ $selected_gred }}</span>
                    </div>
                @endif

                @error('selected_jawatan_id')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
                @error('selected_gred')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- SEMAKAN KELAYAKAN --}}
            @if($showKelayakan && $kelayakan)
                <div class="mb-6 p-4 rounded-lg {{ $kelayakan['lulus'] ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                    <div class="flex items-center gap-2 mb-2">
                        @if($kelayakan['lulus'])
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-semibold text-green-700">Anda layak memohon jawatan ini</span>
                        @else
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="font-semibold text-yellow-700">Syarat kelayakan belum dipenuhi</span>
                        @endif
                    </div>

                    @if(!$kelayakan['lulus'])
                        <ul class="list-disc list-inside text-sm text-yellow-700 ml-2">
                            @foreach($kelayakan['senarai_semak'] as $semak)
                                <li>{{ $semak }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            {{-- SUBMIT BUTTONS --}}
            <div class="pt-4 flex gap-3">
                <a href="{{ route('app.permohonan.index') }}"
                   class="px-4 py-2 rounded-lg border hover:bg-gray-50 transition">
                    Batal
                </a>

                <button type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700 disabled:opacity-50 transition">
                    <span wire:loading.remove wire:target="submit">Hantar Permohonan</span>
                    <span wire:loading wire:target="submit">Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>
