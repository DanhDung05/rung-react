@php
    $isEdit = isset($tour) && $tour->exists;
    $action = $isEdit ? route('admin.tours.update', $tour) : route('admin.tours.store');
    $method = $isEdit ? 'PUT' : 'POST';

    // Chuẩn bị data cho itinerary
    $itineraries = old('itineraries', $isEdit
        ? $tour->itineraries->map(fn($i) => [
            'day_number'    => $i->day_number,
            'title'         => $i->title,
            'description'   => $i->description,
            'distance_km'   => $i->distance_km,
            'altitude'      => $i->altitude,
            'meals'         => $i->meals,
            'accommodation' => $i->accommodation,
        ])->toArray()
        : [['day_number' => 1, 'title' => '', 'description' => '', 'distance_km' => '', 'altitude' => '', 'meals' => '', 'accommodation' => '']]
    );

    if (empty($itineraries)) {
        $itineraries = [['day_number' => 1, 'title' => '', 'description' => '', 'distance_km' => '', 'altitude' => '', 'meals' => '', 'accommodation' => '']];
    }

    // Destinations đã chọn
    $selectedDestinations = old('destination_ids', $isEdit
        ? $tour->destinations->pluck('id')->toArray()
        : []
    );

    // Gallery hiện có
    $galleryItems = $isEdit ? $tour->media()->collection(\App\Models\Media::COLLECTION_GALLERY)->get() : collect();
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method($method)

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- CỘT TRÁI --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Thông tin cơ bản --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Thông tin cơ bản</h3>

                <x-input name="name" label="Tên tour" required
                         :value="old('name', $tour->name ?? '')"
                         placeholder="Ví dụ: Trekking Sapa 3N2Đ" />

                <x-input name="slug" label="Slug (để trống sẽ tự sinh)"
                         :value="old('slug', $tour->slug ?? '')"
                         placeholder="trekking-sapa-3n2d" />

                <x-select name="category_id" label="Danh mục" required
                          :options="$categories->pluck('name', 'id')->prepend('-- Chọn danh mục --', '')"
                          :selected="old('category_id', $tour->category_id ?? '')" />

                <x-textarea name="excerpt" label="Mô tả ngắn" rows="3"
                            :value="old('excerpt', $tour->excerpt ?? '')" />

                {{-- TinyMCE được đặt trong div riêng, tách biệt khỏi Alpine --}}
                <div>
                    <x-tinymce name="content" label="Nội dung chi tiết"
                               :value="old('content', $tour->content ?? '')"
                               hint="Sử dụng trình soạn thảo để nhập nội dung đẹp." />
                </div>
            </div>

            {{-- Điểm đến --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Điểm đến liên quan</h3>
                <p class="text-sm text-text-muted mb-3">Chọn một hoặc nhiều điểm đến (giữ Ctrl/Cmd để chọn nhiều).</p>

                <select name="destination_ids[]" multiple size="8"
                        class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-base text-text
                               focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}"
                                @if(in_array($destination->id, $selectedDestinations)) selected @endif>
                            {{ $destination->name }}
                            @if($destination->region) — {{ $destination->region }} @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Thông số hành trình --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Thông số hành trình</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input name="duration_days" type="number" label="Số ngày" min="0"
                             :value="old('duration_days', $tour->duration_days ?? '')"
                             placeholder="3" />

                    <x-input name="duration_nights" type="number" label="Số đêm" min="0"
                             :value="old('duration_nights', $tour->duration_nights ?? '')"
                             placeholder="2" />

                    <x-input name="distance_km" type="number" step="0.01" label="Quãng đường (km)"
                             :value="old('distance_km', $tour->distance_km ?? '')"
                             placeholder="45.5" />

                    <x-input name="price_reference" type="number" label="Giá tham khảo (VND)"
                             :value="old('price_reference', $tour->price_reference ?? '')"
                             placeholder="3500000"
                             hint="Số nguyên, đơn vị VND." />

                    <x-input name="altitude_min" type="number" label="Độ cao thấp nhất (m)"
                             :value="old('altitude_min', $tour->altitude_min ?? '')"
                             placeholder="200" />

                    <x-input name="altitude_max" type="number" label="Độ cao cao nhất (m)"
                             :value="old('altitude_max', $tour->altitude_max ?? '')"
                             placeholder="3143" />

                    <x-select name="difficulty" label="Độ khó"
                              :options="[
                                  '' => '-- Chọn độ khó --',
                                  'easy' => 'Dễ',
                                  'moderate' => 'Trung bình',
                                  'hard' => 'Khó',
                                  'expert' => 'Chuyên gia',
                              ]"
                              :selected="old('difficulty', $tour->difficulty ?? '')" />

                    <x-input name="best_time" label="Thời điểm thích hợp"
                             :value="old('best_time', $tour->best_time ?? '')"
                             placeholder="Tháng 9 - Tháng 11" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input name="start_point" label="Điểm xuất phát"
                             :value="old('start_point', $tour->start_point ?? '')"
                             placeholder="Hà Nội" />

                    <x-input name="end_point" label="Điểm kết thúc"
                             :value="old('end_point', $tour->end_point ?? '')"
                             placeholder="Sapa" />
                </div>

                <x-textarea name="notes" label="Lưu ý & Chuẩn bị" rows="4"
                            :value="old('notes', $tour->notes ?? '')"
                            hint="Hành lý, trang phục, lưu ý an toàn..." />
            </div>

            {{-- ITINERARY --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6"
                 x-data="itineraryManager({{ Js::from($itineraries) }})">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-heading text-lg font-semibold">Lịch trình theo ngày</h3>
                    <button type="button"
                            @click="addDay()"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium
                                   text-primary bg-primary-light hover:bg-primary hover:text-text-inverse transition-base">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Thêm ngày
                    </button>
                </div>

                <p class="text-sm text-text-muted mb-4">
                    Điền lịch trình từng ngày. Để trống ngày nào cũng không sao, nhưng nên điền tiêu đề.
                </p>

                <div class="space-y-4">
                    <template x-for="(day, index) in days" :key="index">
                        <div class="border border-border rounded-lg p-4 bg-bg-subtle/50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-medium text-sm">
                                    Ngày <span x-text="day.day_number || (index + 1)"></span>
                                </span>
                                <button type="button"
                                        @click="removeDay(index)"
                                        x-show="days.length > 1"
                                        class="text-danger hover:bg-danger/10 p-1 rounded transition-base"
                                        title="Xóa ngày">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-text-muted mb-1">Ngày thứ</label>
                                        <input type="number" min="1"
                                               :name="`itineraries[${index}][day_number]`"
                                               x-model.number="day.day_number"
                                               class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                                      focus:outline-none focus:ring-1 focus:ring-primary">
                                    </div>
                                    <div class="md:col-span-3">
                                        <label class="block text-xs font-medium text-text-muted mb-1">Tiêu đề *</label>
                                        <input type="text"
                                               :name="`itineraries[${index}][title]`"
                                               x-model="day.title"
                                               placeholder="Hà Nội → Sapa"
                                               class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                                      focus:outline-none focus:ring-1 focus:ring-primary">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-text-muted mb-1">Mô tả</label>
                                    <textarea :name="`itineraries[${index}][description]`"
                                              x-model="day.description"
                                              rows="2"
                                              class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                                     focus:outline-none focus:ring-1 focus:ring-primary"></textarea>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-text-muted mb-1">Quãng đường (km)</label>
                                        <input type="number" step="0.01" min="0"
                                               :name="`itineraries[${index}][distance_km]`"
                                               x-model="day.distance_km"
                                               class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                                      focus:outline-none focus:ring-1 focus:ring-primary">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-text-muted mb-1">Độ cao (m)</label>
                                        <input type="number" min="0"
                                               :name="`itineraries[${index}][altitude]`"
                                               x-model="day.altitude"
                                               class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                                      focus:outline-none focus:ring-1 focus:ring-primary">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-text-muted mb-1">Bữa ăn</label>
                                        <input type="text"
                                               :name="`itineraries[${index}][meals]`"
                                               x-model="day.meals"
                                               placeholder="B, L, D"
                                               class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                                      focus:outline-none focus:ring-1 focus:ring-primary">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-text-muted mb-1">Chỗ nghỉ</label>
                                        <input type="text"
                                               :name="`itineraries[${index}][accommodation]`"
                                               x-model="day.accommodation"
                                               placeholder="Homestay"
                                               class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                                                      focus:outline-none focus:ring-1 focus:ring-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- SEO --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">SEO</h3>

                <x-input name="meta_title" label="Meta title"
                         :value="old('meta_title', $tour->meta_title ?? '')" />

                <x-textarea name="meta_description" label="Meta description" rows="2"
                            :value="old('meta_description', $tour->meta_description ?? '')"
                            hint="Khuyến nghị 150-160 ký tự." />
            </div>

        </div>

        {{-- CỘT PHẢI --}}
        <div class="space-y-6">

            {{-- Ảnh đại diện --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>

                @if ($isEdit && $tour->featured_image)
                    <div class="mb-4">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image) }}"
                             alt="{{ $tour->name }}"
                             class="w-full aspect-video object-cover rounded-md border border-border">
                        <p class="text-xs text-text-muted mt-2">Ảnh hiện tại</p>
                    </div>
                @endif

                <input type="file" name="featured_image"
                       accept="image/jpeg,image/png,image/webp"
                       class="block w-full text-sm text-text border border-border rounded-md cursor-pointer
                              file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0
                              file:bg-primary file:text-text-inverse file:font-medium
                              hover:file:bg-primary-hover">
                <p class="text-xs text-text-muted mt-2">JPG, PNG, WEBP. Tối đa 20MB.</p>
            </div>

            {{-- Ảnh phụ (Gallery) --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh phụ (Gallery)</h3>
                <p class="text-xs text-text-muted mb-3">Có thể chọn nhiều ảnh cùng lúc.</p>

                @if ($galleryItems->count() > 0)
                    <div id="gallery-preview" class="grid grid-cols-2 gap-3 mb-4">
                        @foreach ($galleryItems as $media)
                            <div class="relative group gallery-item" data-media-id="{{ $media->id }}">
                                <img src="{{ $media->url }}"
                                     alt="{{ $media->alt_text ?? $tour->name }}"
                                     class="w-full h-24 object-cover rounded-lg">
                                <button type="button"
                                        class="btn-remove-gallery absolute top-1 right-1 w-6 h-6 rounded-full bg-danger text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition"
                                        data-media-id="{{ $media->id }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <input type="file" name="gallery[]" multiple accept="image/*"
                       class="block w-full text-sm text-text border border-border rounded-md cursor-pointer
                              file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0
                              file:bg-secondary file:text-white file:font-medium
                              hover:file:bg-secondary-hover">
                <p class="text-xs text-text-muted mt-2">Chọn nhiều ảnh cùng lúc. JPG, PNG, WEBP.</p>
            </div>

            {{-- Trạng thái & Cài đặt --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Trạng thái & Cài đặt</h3>

                <x-select name="status" label="Trạng thái" required
                          :options="['draft' => 'Nháp', 'published' => 'Đã xuất bản']"
                          :selected="old('status', $tour->status ?? 'draft')" />

                <x-input name="published_at" type="datetime-local" label="Thời gian xuất bản"
                         :value="old('published_at', isset($tour) && $tour->published_at ? $tour->published_at->format('Y-m-d\TH:i') : '')" />

                <x-input name="sort_order" type="number" label="Thứ tự hiển thị"
                         :value="old('sort_order', $tour->sort_order ?? 0)" />

                <x-checkbox name="is_featured" value="1" label="Đánh dấu nổi bật"
                            :checked="old('is_featured', $tour->is_featured ?? false)" />
            </div>

        </div>

    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.tours.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.tours.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo tour' }}
            </x-button>
        </div>
    </div>

</form>

{{-- Alpine component cho itinerary --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('itineraryManager', (initialDays) => ({
        days: initialDays.length > 0 ? initialDays : [{ day_number: 1, title: '', description: '', distance_km: '', altitude: '', meals: '', accommodation: '' }],

        addDay() {
            const nextNumber = this.days.length + 1;
            this.days.push({
                day_number: nextNumber,
                title: '',
                description: '',
                distance_km: '',
                altitude: '',
                meals: '',
                accommodation: '',
            });
        },

        removeDay(index) {
            if (this.days.length <= 1) return;
            this.days.splice(index, 1);
            this.days.forEach((day, i) => {
                day.day_number = i + 1;
            });
        },
    }));
});
</script>

{{-- Xử lý xóa ảnh gallery (AJAX) --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-remove-gallery').forEach(btn => {
        btn.addEventListener('click', function() {
            const mediaId = this.dataset.mediaId;
            if (!confirm('Xóa ảnh này?')) return;

            fetch(`{{ url('/admin/media') }}/${mediaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.closest('.gallery-item').remove();
                }
            })
            .catch(err => console.error('Lỗi xóa ảnh:', err));
        });
    });
});
</script>