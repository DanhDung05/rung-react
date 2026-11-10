{{-- ============================================================
     ĐÁNH GIÁ TOUR
     ============================================================ --}}
<div id="reviews" class="mt-12 pt-8 border-t border-border">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-heading text-2xl font-bold">Đánh giá từ khách hàng</h2>
        @if ($tour->reviews_count > 0)
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1 text-warning">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($tour->average_rating) ? 'fill-current' : 'text-border' }}" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-lg font-bold text-text">{{ $tour->average_rating }}</span>
                <span class="text-sm text-text-muted">({{ $tour->reviews_count }} đánh giá)</span>
            </div>
        @endif
    </div>

    {{-- Form gửi đánh giá --}}
    @auth
        @php
            $hasReviewed = $tour->allReviews()->where('user_id', auth()->id())->exists();
        @endphp

        @if (!$hasReviewed)
            <div class="bg-bg-subtle rounded-xl p-6 mb-8">
                <h3 class="font-heading text-lg font-bold mb-4">Viết đánh giá của bạn</h3>

                @if (session('success'))
                    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
                @endif
                @if (session('error'))
                    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
                @endif

                <form action="{{ route('tours.review.store', $tour) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-2">Chấm điểm <span class="text-danger">*</span></label>
                        <div class="flex items-center gap-2" x-data="{ rating: 5, hover: 0 }">
                            <template x-for="star in 5" :key="star">
                                <button type="button"
                                        @click="rating = star"
                                        @mouseenter="hover = star"
                                        @mouseleave="hover = 0"
                                        class="focus:outline-none">
                                    <svg class="w-8 h-8 transition-colors"
                                         :class="(hover || rating) >= star ? 'text-warning fill-current' : 'text-border'"
                                         viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            </template>
                            <input type="hidden" name="rating" :value="rating">
                            <span class="text-sm text-text-muted ml-2" x-text="rating + ' sao'"></span>
                        </div>
                        @error('rating')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Nội dung đánh giá <span class="text-danger">*</span></label>
                        <textarea name="comment" rows="4" required
                                  class="w-full rounded-lg border border-border bg-bg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                  placeholder="Chia sẻ trải nghiệm của bạn về tour này...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-button type="submit" variant="primary">Gửi đánh giá</x-button>
                </form>
            </div>
        @else
            <div class="bg-info/10 border border-info/30 rounded-xl p-4 mb-8 text-sm text-info">
                Bạn đã đánh giá tour này. Cảm ơn bạn!
            </div>
        @endif
    @else
        <div class="bg-bg-subtle rounded-xl p-6 mb-8 text-center">
            <p class="text-sm text-text-muted mb-3">Vui lòng đăng nhập để gửi đánh giá.</p>
            <x-button href="{{ route('login') }}" variant="primary" size="sm">Đăng nhập</x-button>
        </div>
    @endauth

    {{-- Danh sách đánh giá --}}
    @php
        $reviews = $tour->reviews()->with('user')->take(10)->get();
    @endphp

    @if ($reviews->count() > 0)
        <div class="space-y-4">
            @foreach ($reviews as $review)
                <div class="bg-bg rounded-xl border border-border p-5">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-semibold flex-shrink-0">
                            {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="font-medium text-sm truncate">{{ $review->user->name ?? 'Ẩn danh' }}</span>
                                <span class="text-xs text-text-muted">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-border' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-text leading-relaxed">{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 bg-bg-subtle rounded-xl">
            <p class="text-text-muted">Chưa có đánh giá nào cho tour này.</p>
        </div>
    @endif
</div>