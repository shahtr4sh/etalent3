@if($penerbitan->isEmpty())
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        <p class="text-gray-500 italic">Tiada rekod penerbitan.</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($penerbitan as $pub)
            <div class="bg-white border rounded-lg p-4 hover:shadow-md transition">
                <p class="text-sm font-medium">{{ $pub->title }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $pub->formatted_authors }} ({{ $pub->tahun ?? 'n.d.' }})
                </p>
                @if($pub->indexes->isNotEmpty())
                    <div class="mt-2 flex flex-wrap gap-1">
                        @foreach($pub->indexes as $index)
                            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">
                                {{ $index->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
