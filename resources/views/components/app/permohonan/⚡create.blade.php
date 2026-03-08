<div class="p-6 max-w-3xl">
    <h1 class="text-2xl font-semibold mb-4">Permohonan Baharu</h1>

    @error('general')
    <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-200">
        {{ $message }}
    </div>
    @enderror

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-300">CV</label>
                <input type="file" wire:model="doc_cv" class="block w-full text-sm mt-1" />
                @error('doc_cv') <div class="text-sm text-red-300 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-gray-300">Surat Sokongan</label>
                <input type="file" wire:model="doc_support_letter" class="block w-full text-sm mt-1" />
                @error('doc_support_letter') <div class="text-sm text-red-300 mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Tambah input lain ikut keperluan -->
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700">
                Simpan Draf
            </button>

            <a href="{{ route('app.permohonan.index') }}"
               class="px-4 py-2 rounded-lg bg-white/10 text-gray-200 hover:bg-white/15">
                Kembali
            </a>
        </div>
    </form>
</div>
