<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="p-4">
    @if(isset($items['error']))
        <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-4 rounded-lg">
            ⚠️ {{ $items['error'] }}
        </div>
    @else
        <div class="space-y-3">
            @foreach($items as $item)
                <div class="flex items-center justify-between p-4 rounded-lg border
                    {{ $item['status'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">

                    <div class="flex-1 pr-4">
                        <p class="font-medium"><strong>{{ $item['label'] }}</strong></p>
                        <p class="text-sm text-gray-600">
                            Diperlukan:
                            <span class="font-semibold">{{ $item['required'] }} {{ $item['unit'] }}</span>
                                |    Dimiliki:
                            <span class="font-semibold">
                                @if($item['unit'] === '%')
                                    {{ number_format($item['current'], 2) }}%
                                @else
                                    {{ $item['current'] }} {{ $item['unit'] }}
                                @endif
                            </span>
                            @if($item['status'])
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-green-200 text-green-800">
                                   🟡 Layak
                            </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-200 text-red-800">
                                   🔴 Tidak Layak
                                @endif
                            </span>
                                <br><br>
                                @endforeach
                        </p>
                    </div>

                    <div class="flex items-center">

                        {{-- Summary --}}
                        @php
                            $allPassed = collect($items)->every(fn($item) => $item['status'] === true);
                        @endphp

                        <div class="mt-6 p-4 rounded-lg {{ $allPassed ? 'bg-green-100 border border-green-300' : 'bg-yellow-100 border border-yellow-300' }}">
                            <div class="flex items-center gap-2">
                                <hr><br>
                                @if($allPassed)
                                    <span class="font-semibold text-green-700">✓ Permohonan LULUS secara keseluruhan</span>
                                @else
                                    <span class="font-semibold text-yellow-700">⚠ Permohonan TIDAK LULUS - beberapa syarat belum dipenuhi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br>
                    @endif
                </div>
        </div>
</div>
