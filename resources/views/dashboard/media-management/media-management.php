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
#[Title('Manajemen Media & Aset Digital — Dashboard PKU-CSIRT')]
class extends Component
{
    use WithFileUploads;

    public string $search = '';

    public string $sourceFilter = '';

    /**
     * @var mixed
     */
    public $file = null;

    public string $label = '';

    public bool $showUploadModal = false;

    public ?string $pendingDeletePath = null;

    public string $pendingDeleteName = '';

    public ?string $previewUrl = null;

    public string $previewTitle = '';

    public function openUpload(): void
    {
        $this->reset(['file', 'label']);
        $this->showUploadModal = true;
    }

    public function closeUpload(): void
    {
        $this->reset(['file', 'label']);
        $this->showUploadModal = false;
    }

    public function upload(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'label' => ['nullable', 'string', 'max:120'],
        ], [
            'file.required' => 'Pilih berkas gambar yang akan diunggah.',
            'file.image' => 'Berkas harus berupa gambar yang valid.',
            'file.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP, atau SVG.',
            'file.max' => 'Ukuran berkas maksimal adalah 5 MB.',
        ]);

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $this->file;

        app(CsirtDataService::class)->uploadMedia($uploadedFile, $this->label !== '' ? $this->label : null);

        unset($this->mediaItems, $this->counts);

        $this->showUploadModal = false;
        $this->reset(['file', 'label']);

        Flux::toast(
            text: 'Berkas media berhasil diunggah ke storage lokal PKU-CSIRT.',
            heading: 'Media Berhasil Diunggah',
            variant: 'success',
        );
    }

    public function confirmDelete(string $path, string $name): void
    {
        $this->pendingDeletePath = $path;
        $this->pendingDeleteName = $name;
    }

    public function cancelDelete(): void
    {
        $this->pendingDeletePath = null;
        $this->pendingDeleteName = '';
    }

    public function deleteConfirmed(): void
    {
        if ($this->pendingDeletePath !== null) {
            $deleted = app(CsirtDataService::class)->deleteMedia($this->pendingDeletePath);

            if ($deleted) {
                unset($this->mediaItems, $this->counts);

                Flux::toast(
                    text: 'Berkas media "'.$this->pendingDeleteName.'" berhasil dihapus.',
                    heading: 'Media Dihapus',
                    variant: 'success',
                );
            } else {
                Flux::toast(
                    text: 'Berkas bawaan sistem tidak dapat dihapus.',
                    heading: 'Aksi Ditolak',
                    variant: 'danger',
                );
            }

            $this->pendingDeletePath = null;
            $this->pendingDeleteName = '';
        }
    }

    public function preview(string $url, string $title): void
    {
        $this->previewUrl = $url;
        $this->previewTitle = $title;
    }

    public function closePreview(): void
    {
        $this->previewUrl = null;
        $this->previewTitle = '';
    }

    public function setSourceFilter(string $source): void
    {
        $this->sourceFilter = $source;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function mediaItems(): array
    {
        return app(CsirtDataService::class)->mediaItems(
            search: $this->search !== '' ? $this->search : null,
            source: $this->sourceFilter !== '' ? $this->sourceFilter : null,
        );
    }

    /**
     * @return array{total: int, upload: int, system: int}
     */
    #[Computed]
    public function counts(): array
    {
        $all = app(CsirtDataService::class)->mediaItems();

        return [
            'total' => count($all),
            'upload' => count(array_filter($all, static fn (array $i): bool => ($i['source'] ?? '') === 'upload')),
            'system' => count(array_filter($all, static fn (array $i): bool => ($i['source'] ?? '') === 'system')),
        ];
    }
};
