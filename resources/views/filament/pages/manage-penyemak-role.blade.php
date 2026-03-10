<x-filament::page>
    <div class="max-w-2xl mx-auto">
        <x-filament::card>
            <div class="p-6">
                <div class="text-center mb-6">
                    <x-filament::icon
                        icon="heroicon-o-shield-check"
                        class="w-12 h-12 text-primary-600 mx-auto mb-3"
                    />
                    <h2 class="text-xl font-semibold">Tambah Penyemak</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Masukkan Staff ID untuk jadikan pengguna sebagai PENYEMAK
                    </p>
                </div>

                <form wire:submit="assignRole" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Staff ID</label>
                        <input type="text"
                               wire:model="staff_id"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Contoh: 11952018">
                        @error('staff_id')
                        <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                        Tambah Penyemak
                    </button>
                </form>

                {{-- Senarai Penyemak --}}
                @php
                    $role = Spatie\Permission\Models\Role::where('name', 'penyemak')->first();
                    $penyemaks = $role ? $role->users()->latest()->take(10)->get() : collect();
                @endphp

                @if($penyemaks->isNotEmpty())
                    <div class="mt-8 border-t pt-6">
                        <h3 class="font-medium mb-3">Senarai Penyemak Terkini</h3>
                        <div class="space-y-2">
                            @foreach($penyemaks as $penyemak)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <span class="font-medium">{{ $penyemak->name }}</span>
                                        <span class="text-xs text-gray-500 ml-2">({{ $penyemak->staff_id }})</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                                        Penyemak
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </x-filament::card>
    </div>
</x-filament::page>
