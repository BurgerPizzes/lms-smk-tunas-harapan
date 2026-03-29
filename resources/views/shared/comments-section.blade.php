@props([
    'commentable_type' => null,
    'commentable_id' => null,
    'comments' => null,
])

@if(isset($comments) && $comments->count() > 0)
<div id="comments-section" class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
            <span class="inline-flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
                Komentar ({{ $comments->total() }})
            </span>
        </h3>
    </div>

    {{-- Comments List --}}
    <div class="space-y-4" id="comments-list">
        @foreach($comments as $comment)
            <div class="animate-fade-in" id="comment-{{ $comment->id }}">
                {{-- Main Comment --}}
                <div class="flex gap-3">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        @if($comment->user->avatar)
                            <img src="{{ Storage::url($comment->user->avatar) }}"
                                 alt="{{ $comment->user->name }}"
                                 class="w-9 h-9 rounded-full object-cover">
                        @else
                            <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl px-4 py-3">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $comment->user->name }}
                                </span>
                                <x-badge text="{{ $comment->user->roles->first()?->name ?? 'User' }}" color="gray" />
                                @if($comment->is_edited)
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">(diedit)</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap break-words">
                                {{ $comment->body }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-4 mt-1.5 ml-1">
                            <span class="text-[11px] text-gray-400 dark:text-gray-500">
                                {{ $comment->created_at?->diffForHumans() }}
                            </span>

                            {{-- Reply Button --}}
                            <button type="button"
                                    onclick="toggleReplyForm({{ $comment->id }})"
                                    class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.75c0-.75.27-1.477.77-2.032L10.5 1.5l6.73 7.218a2.954 2.954 0 0 1 .77 2.032v6.25A2.75 2.75 0 0 1 15.25 19.5H8.75a2.75 2.75 0 0 1-2.75-2.75v-6.25Z" />
                                </svg>
                                Balas
                            </button>

                            {{-- Delete Button (author or admin) --}}
                            @if(auth()->check() && (auth()->id() === $comment->user_id || is_admin()))
                                <button type="button"
                                        onclick="deleteComment({{ $comment->id }})"
                                        class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    Hapus
                                </button>
                            @endif
                        </div>

                        {{-- Nested Reply Form --}}
                        <div id="reply-form-{{ $comment->id }}" class="hidden mt-3 ml-2">
                            <div class="flex gap-2">
                                <div class="flex-1">
                                    <textarea id="reply-body-{{ $comment->id }}"
                                              rows="2"
                                              class="w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors resize-none"
                                              placeholder="Tulis balasan..."></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 mt-2">
                                <button type="button"
                                        onclick="toggleReplyForm({{ $comment->id }})"
                                        class="px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                                    Batal
                                </button>
                                <button type="button"
                                        onclick="submitReply({{ $comment->id }}, '{{ $commentable_type }}', {{ $commentable_id }})"
                                        class="px-3 py-1.5 text-xs font-medium bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                                    Kirim Balasan
                                </button>
                            </div>
                        </div>

                        {{-- Replies --}}
                        @if($comment->replies && $comment->replies->count() > 0)
                            <div class="mt-3 space-y-3 pl-2 border-l-2 border-gray-200 dark:border-gray-600">
                                @foreach($comment->replies as $reply)
                                    <div class="flex gap-3" id="comment-{{ $reply->id }}">
                                        {{-- Reply Avatar --}}
                                        <div class="flex-shrink-0">
                                            @if($reply->user->avatar)
                                                <img src="{{ Storage::url($reply->user->avatar) }}"
                                                     alt="{{ $reply->user->name }}"
                                                     class="w-7 h-7 rounded-full object-cover">
                                            @else
                                                <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                                                    <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400">
                                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Reply Content --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="bg-white dark:bg-gray-800 rounded-lg px-3 py-2 border border-gray-100 dark:border-gray-700">
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $reply->user->name }}</span>
                                                    @if($reply->is_edited)
                                                        <span class="text-[10px] text-gray-400 dark:text-gray-500">(diedit)</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap break-words">
                                                    {{ $reply->body }}
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-3 mt-1 ml-1">
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500">
                                                    {{ $reply->created_at?->diffForHumans() }}
                                                </span>
                                                @if(auth()->check() && (auth()->id() === $reply->user_id || is_admin()))
                                                    <button type="button"
                                                            onclick="deleteComment({{ $reply->id }})"
                                                            class="text-[10px] text-gray-400 hover:text-red-500 transition-colors">
                                                        Hapus
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($comments && $comments->hasPages())
        <div class="pt-2">
            <x-pagination :items="$comments" />
        </div>
    @endif
</div>
@endif

{{-- Add Comment Form --}}
<div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700" id="add-comment-form">
    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tambahkan Komentar</h4>

    <form id="comment-form" onsubmit="submitComment(event, '{{ $commentable_type }}', {{ $commentable_id }})">
        @csrf
        <div class="flex gap-3">
            {{-- Avatar --}}
            <div class="flex-shrink-0">
                @auth
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-9 h-9 rounded-full object-cover">
                    @else
                        <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="flex-1">
                <textarea id="comment-body"
                          name="body"
                          rows="3"
                          required
                          class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors resize-y"
                          placeholder="Tulis komentar Anda..."></textarea>

                <div class="flex justify-end mt-2">
                    <button type="submit"
                            id="submit-comment-btn"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                        Kirim
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Submit main comment via AJAX
    function submitComment(event, commentableType, commentableId) {
        event.preventDefault();

        const body = document.getElementById('comment-body').value.trim();
        if (!body) return;

        const btn = document.getElementById('submit-comment-btn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Mengirim...';

        const token = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/comments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                commentable_type: commentableType,
                commentable_id: commentableId,
                body: body,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('comment-body').value = '';
                // Reload comments list
                if (typeof loadComments === 'function') {
                    loadComments();
                } else {
                    location.reload();
                }
            } else {
                alert(data.message || 'Gagal mengirim komentar.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg> Kirim';
        });
    }

    // Toggle reply form
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        if (form) {
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                document.getElementById('reply-body-' + commentId).focus();
            }
        }
    }

    // Submit reply via AJAX
    function submitReply(parentId, commentableType, commentableId) {
        const body = document.getElementById('reply-body-' + parentId).value.trim();
        if (!body) return;

        const token = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/comments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                commentable_type: commentableType,
                commentable_id: commentableId,
                parent_id: parentId,
                body: body,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload to show new reply
                location.reload();
            } else {
                alert(data.message || 'Gagal mengirim balasan.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }

    // Delete comment via AJAX
    function deleteComment(commentId) {
        if (!confirm('Apakah Anda yakin ingin menghapus komentar ini?')) return;

        const token = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/comments/' + commentId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById('comment-' + commentId);
                if (el) {
                    el.style.transition = 'opacity 0.3s, transform 0.3s';
                    el.style.opacity = '0';
                    el.style.transform = 'translateX(-10px)';
                    setTimeout(() => el.remove(), 300);
                }
            } else {
                alert(data.message || 'Gagal menghapus komentar.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
</script>
@endpush
