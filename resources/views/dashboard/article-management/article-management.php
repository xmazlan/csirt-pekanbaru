<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Flux\Flux;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new
#[Title('Manajemen Publikasi & Berita Kegiatan — Dashboard PKU-CSIRT')]
class extends Component
{
    use WithFileUploads;

    public string $search = '';

    public string $categoryFilter = '';

    public string $statusFilter = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $category = 'Keamanan Siber';

    public string $status = 'published';

    public string $published_at = '';

    public string $thumbnail = '/sandiman/perkuat.jpg';

    /**
     * @var mixed
     */
    public $thumbnailFile = null;

    public string $custom_thumbnail = '';

    public bool $showMediaPicker = false;

    public string $mediaPickerSearch = '';

    public string $author = 'Diskominfotiksan Pekanbaru';

    public string $content = '';

    public ?int $pendingDeleteId = null;

    public string $pendingDeleteTitle = '';

    public function mount(): void
    {
        $this->published_at = now()->toDateString();
    }

    public function startCreate(): void
    {
        $this->reset(['title', 'content', 'custom_thumbnail', 'thumbnailFile', 'editingId', 'showMediaPicker']);
        $this->category = 'Keamanan Siber';
        $this->status = 'published';
        $this->thumbnail = '/sandiman/perkuat.jpg';
        $this->author = 'Diskominfotiksan Pekanbaru';
        $this->published_at = now()->toDateString();
        $this->showForm = true;
    }

    public function startEdit(int $id): void
    {
        $article = app(CsirtDataService::class)->findArticle($id);

        if (! $article) {
            Flux::toast(
                text: 'Artikel yang dipilih tidak ditemukan.',
                heading: 'Gagal Memuat Data',
                variant: 'danger',
            );

            return;
        }

        $this->editingId = $id;
        $this->title = (string) ($article['title'] ?? '');
        $this->category = (string) ($article['category'] ?? 'Keamanan Siber');
        $rawStatus = (string) ($article['status'] ?? 'published');
        $this->status = in_array($rawStatus, ['published', 'selesai'], true) ? 'published' : 'draft';
        $this->published_at = (string) ($article['published_at'] ?? now()->toDateString());
        $this->author = (string) ($article['author'] ?? 'Diskominfotiksan Pekanbaru');
        $this->content = (string) ($article['content'] ?? $article['excerpt'] ?? '');
        $this->thumbnail = (string) ($article['thumbnail'] ?? '/sandiman/perkuat.jpg');
        $this->custom_thumbnail = '';
        $this->thumbnailFile = null;
        $this->showMediaPicker = false;
        $this->showForm = true;
    }

    public function cancelForm(): void
    {
        $this->showForm = false;
        $this->reset(['title', 'content', 'custom_thumbnail', 'thumbnailFile', 'editingId', 'showMediaPicker']);
    }

    public function openMediaPicker(): void
    {
        $this->mediaPickerSearch = '';
        $this->showMediaPicker = true;
    }

    public function closeMediaPicker(): void
    {
        $this->showMediaPicker = false;
    }

    public function selectMediaFromLibrary(string $path): void
    {
        $this->thumbnail = $path;
        $this->custom_thumbnail = '';
        $this->thumbnailFile = null;
        $this->showMediaPicker = false;
    }

    public function selectThumbnail(string $path): void
    {
        $this->thumbnail = $path;
        $this->custom_thumbnail = '';
        $this->thumbnailFile = null;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'min:5', 'max:200'],
            'category' => ['required', 'string', 'min:2', 'max:80'],
            'status' => ['required', 'in:published,draft,selesai,baru,diproses'],
            'published_at' => ['required', 'date'],
            'author' => ['required', 'string', 'min:3', 'max:100'],
            'thumbnail' => ['required', 'string', 'max:255'],
            'thumbnailFile' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'custom_thumbnail' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:20', 'max:20000'],
        ], [
            'title.required' => 'Judul publikasi/berita wajib diisi.',
            'title.min' => 'Judul publikasi minimal berisi 5 karakter.',
            'title.max' => 'Judul publikasi maksimal 200 karakter.',
            'category.required' => 'Kategori artikel wajib dipilih atau diisi.',
            'status.required' => 'Status editorial wajib dipilih.',
            'published_at.required' => 'Tanggal publikasi wajib diisi.',
            'author.required' => 'Nama instansi atau penulis wajib diisi.',
            'thumbnailFile.image' => 'Berkas thumbnail harus berupa gambar yang valid (JPG, PNG, WEBP, SVG).',
            'thumbnailFile.max' => 'Ukuran berkas thumbnail maksimal 5 MB.',
            'content.required' => 'Isi materi artikel / advisori keamanan wajib diisi.',
            'content.min' => 'Isi artikel minimal berisi 20 karakter agar informatif.',
        ]);

        $finalThumbnail = $this->thumbnail;

        // Jika pengguna mengunggah gambar langsung dari form artikel
        if ($this->thumbnailFile) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $this->thumbnailFile;
            $uploaded = app(CsirtDataService::class)->uploadMedia($uploadedFile, $validated['title']);
            $finalThumbnail = $uploaded['path'];
        } elseif (trim($this->custom_thumbnail) !== '') {
            $finalThumbnail = trim($this->custom_thumbnail);
        }

        $normalizedStatus = in_array($validated['status'], ['published', 'selesai'], true) ? 'published' : 'draft';

        $payload = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'status' => $normalizedStatus,
            'published_at' => $validated['published_at'],
            'author' => $validated['author'],
            'thumbnail' => $finalThumbnail,
            'content' => $validated['content'],
        ];

        $isEdit = $this->editingId !== null;

        app(CsirtDataService::class)->saveArticle($payload, $this->editingId);

        unset($this->articles, $this->counts);

        $this->showForm = false;
        $this->reset(['title', 'content', 'custom_thumbnail', 'thumbnailFile', 'editingId', 'showMediaPicker']);

        Flux::toast(
            text: $isEdit
                ? 'Artikel berita/advisori berhasil diperbarui.'
                : 'Artikel berita/advisori baru berhasil ditambahkan.',
            heading: $isEdit ? 'Publikasi Diperbarui' : 'Publikasi Diterbitkan',
            variant: 'success',
        );
    }

    public function confirmDelete(int $id, string $title): void
    {
        $this->pendingDeleteId = $id;
        $this->pendingDeleteTitle = $title;
    }

    public function cancelDelete(): void
    {
        $this->pendingDeleteId = null;
        $this->pendingDeleteTitle = '';
    }

    public function deleteConfirmed(): void
    {
        if ($this->pendingDeleteId !== null) {
            $deleted = app(CsirtDataService::class)->deleteArticle($this->pendingDeleteId);

            if ($deleted) {
                unset($this->articles, $this->counts);

                Flux::toast(
                    text: 'Artikel "'.$this->pendingDeleteTitle.'" berhasil dihapus.',
                    heading: 'Artikel Dihapus',
                    variant: 'success',
                );
            }

            $this->pendingDeleteId = null;
            $this->pendingDeleteTitle = '';
        }
    }

    public function setCategoryFilter(string $category): void
    {
        $this->categoryFilter = $category;
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function articles(): array
    {
        return app(CsirtDataService::class)->articles(
            category: $this->categoryFilter !== '' ? $this->categoryFilter : null,
            status: $this->statusFilter !== '' ? $this->statusFilter : null,
            search: $this->search !== '' ? $this->search : null,
        );
    }

    /**
     * @return array{total: int, published: int, draft: int}
     */
    #[Computed]
    public function counts(): array
    {
        $all = app(CsirtDataService::class)->articles();

        return [
            'total' => count($all),
            'published' => count(array_filter($all, static fn (array $a): bool => in_array((string) ($a['status'] ?? 'published'), ['published', 'selesai'], true))),
            'draft' => count(array_filter($all, static fn (array $a): bool => ! in_array((string) ($a['status'] ?? 'published'), ['published', 'selesai'], true))),
        ];
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function categories(): array
    {
        return app(CsirtDataService::class)->articleCategories();
    }

    /**
     * @return array<int, array{path: string, label: string}>
     */
    #[Computed]
    public function availableThumbnails(): array
    {
        return app(CsirtDataService::class)->availableThumbnails();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function libraryMediaItems(): array
    {
        return app(CsirtDataService::class)->mediaItems(
            search: $this->mediaPickerSearch !== '' ? $this->mediaPickerSearch : null,
        );
    }
};
