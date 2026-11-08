<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Title('Manajemen Hall of Fame — Dashboard PKU-CSIRT')]
class extends Component
{
    public string $name = '';

    public string $url = 'https://pekanbaru.go.id';

    public string $date = '';

    public string $vuln_name = '';

    public string $vuln_web = '';

    public string $cvss = '7.5';

    public string $description = '';

    public ?int $pendingDeleteId = null;

    public string $pendingDeleteName = '';

    public function mount(): void
    {
        $this->date = now()->toDateString();
    }

    public function addEntry(): void
    {
        /** @var array{
         *     name: string,
         *     url: string,
         *     date: string,
         *     vuln_name: string,
         *     vuln_web: string,
         *     cvss: string|float,
         *     description: string
         * } $validated
         */
        $validated = $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'url' => ['required', 'url', 'max:255'],
            'date' => ['required', 'date'],
            'vuln_name' => ['required', 'string', 'min:4', 'max:255'],
            'vuln_web' => ['required', 'url', 'max:255'],
            'cvss' => ['required', 'numeric', 'min:0.1', 'max:10.0'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.required' => 'Nama peneliti keamanan wajib diisi.',
            'url.required' => 'Tautan profil atau apresiasi wajib diisi.',
            'url.url' => 'Format tautan URL tidak valid.',
            'date.required' => 'Tanggal pelaporan wajib diisi.',
            'vuln_name.required' => 'Nama temuan kerentanan wajib diisi.',
            'vuln_web.required' => 'URL subdomain yang terdampak wajib diisi.',
            'vuln_web.url' => 'Format URL subdomain tidak valid.',
            'cvss.required' => 'Skor CVSS v3 wajib diisi (0.1 - 10.0).',
            'description.required' => 'Deskripsi singkat temuan dan remediasi wajib diisi.',
        ]);

        app(CsirtDataService::class)->addHallOfFameEntry($validated);

        unset($this->hofData);

        $this->reset(['name', 'vuln_name', 'vuln_web', 'description']);
        $this->url = 'https://pekanbaru.go.id';
        $this->cvss = '7.5';
        $this->date = now()->toDateString();

        Flux::toast(
            text: 'Entri peneliti keamanan berhasil ditambahkan ke Hall of Fame PKU-CSIRT.',
            heading: 'Hall of Fame Diperbarui',
            variant: 'success',
        );
    }

    public function confirmDelete(int $id, string $name): void
    {
        $this->pendingDeleteId = $id;
        $this->pendingDeleteName = $name;

        Flux::modal('confirm-delete-hof')->show();
    }

    public function deleteConfirmed(): void
    {
        if ($this->pendingDeleteId !== null) {
            app(CsirtDataService::class)->deleteHallOfFameEntry($this->pendingDeleteId);
            unset($this->hofData);

            Flux::toast(
                text: 'Data apresiasi Hall of Fame telah dihapus.',
                heading: 'Entri Dihapus',
                variant: 'warning',
            );
        }

        $this->pendingDeleteId = null;
        $this->pendingDeleteName = '';

        Flux::modal('confirm-delete-hof')->close();
    }

    /**
     * @return array<string, mixed>
     */
    #[Computed]
    public function hofData(): array
    {
        return app(CsirtDataService::class)->hallOfFameData();
    }
};
