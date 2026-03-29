@props([
    'comment' => null,
])

@if($comment)
    <div class="flex space-x-3 py-4 {{ ! $loop->last ? 'border-b border-gray-100 dark:border-gray-700/50' : '' }}">
        {{-- Avatar --}}
        <div class="flex-shrink-0">
            <div class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                {{ strtoupper(substr($comment->user?->name ?? 'U', 0, 1)) }}
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $comment->user?->name ?? 'Unknown' }}</span>
                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $comment->created_at?->diffForHumans() ?? '' }}</span>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 leading-relaxed">{{ $comment->body }}</p>

            {{-- Actions --}}
            <div class="flex items-center space-x-4 mt-2">
                <button type="button" class="text-xs text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors">
                    Balas
                </button>
                @if(auth()->check() && auth()->id() === $comment->user_id)
                    <button type="button" class="text-xs text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 font-medium transition-colors">
                        Edit
                    </button>
                    <button type="button" onclick="if(confirm('Hapus komentar ini?')) window.location.href='{{ route('comments.destroy', $comment->id) }}'" class="text-xs text-gray-400 hover:text-red-600 dark:hover:text-red-400 font-medium transition-colors">
                        Hapus
                    </button>
                @endif
            </div>

            {{-- Replies --}}
            @if($comment->replies && $comment->replies->count() > 0)
                <div class="mt-3 pl-4 space-y-0 border-l-2 border-gray-200 dark:border-gray-700">
                    @foreach($comment->replies as $reply)
                        <div class="flex space-x-3 py-2">
                            <div class="w-7 h-7 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                {{ strtoupper(substr($reply->user?->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $reply->user?->name ?? 'Unknown' }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $reply->created_at?->diffForHumans() ?? '' }}</span>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ $reply->body }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
