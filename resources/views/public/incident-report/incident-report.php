<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::public')]
#[Title('Melaporkan Insiden & Kerentanan Siber — PKU-CSIRT Kota Pekanbaru')]
class extends Component
{
    public string $reporter_name = '';

    public string $reporter_email = '';

    public string $reporter_org = '';

    public string $category = 'Kerentanan Aplikasi (Responsible Disclosure)';

    public string $affected_system = '';

    public string $severity = 'Tinggi';

    public string $description = '';

    public bool $agreed_disclosure = false;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $submittedTicket = null;

    /**
     * @return array<string, mixed>
     */
    #[Computed]
    public function profile(): array
    {
        return app(CsirtDataService::class)->siteProfile();
    }

    public function submitReport(): void
    {
        /** @var array{
         *     reporter_name: string,
         *     reporter_email: string,
         *     reporter_org: string,
         *     category: string,
         *     affected_system: string,
         *     severity: string,
         *     description: string,
         *     agreed_disclosure: bool
         * } $validated
         */
        $validated = $this->validate([
            'reporter_name' => ['required', 'string', 'min:2', 'max:120'],
            'reporter_email' => ['required', 'email', 'max:150'],
            'reporter_org' => ['nullable', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:100'],
            'affected_system' => ['required', 'string', 'min:4', 'max:255'],
            'severity' => ['required', 'in:Kritis,Tinggi,Sedang,Rendah'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'agreed_disclosure' => ['accepted'],
        ], [
            'reporter_name.required' => 'Nama pelapor atau alias peneliti wajib diisi.',
            'reporter_name.min' => 'Nama pelapor minimal 2 karakter.',
            'reporter_email.required' => 'Alamat pos-el aktif wajib diisi untuk koordinasi tindak lanjut.',
            'reporter_email.email' => 'Format alamat pos-el tidak valid.',
            'category.required' => 'Pilih salah satu kategori insiden atau kerentanan.',
            'affected_system.required' => 'URL subdomain *.pekanbaru.go.id atau sistem terdampak wajib diisi.',
            'severity.required' => 'Pilih perkiraan tingkat keparahan insiden.',
            'description.required' => 'Deskripsi teknis atau kronologi kejadian wajib diisi.',
            'description.min' => 'Jelaskan kronologi atau langkah reproduksi minimal 20 karakter.',
            'agreed_disclosure.accepted' => 'Anda wajib menyetujui komitmen pelaporan beretika (Responsible Disclosure).',
        ]);

        $ticket = app(CsirtDataService::class)->submitIncidentReport([
            'reporter_name' => $validated['reporter_name'],
            'reporter_email' => $validated['reporter_email'],
            'reporter_org' => $validated['reporter_org'] !== '' ? $validated['reporter_org'] : 'Pelapor Publik / Independen',
            'category' => $validated['category'],
            'affected_system' => $validated['affected_system'],
            'severity' => $validated['severity'],
            'description' => $validated['description'],
        ]);

        $this->submittedTicket = $ticket;

        Flux::toast(
            text: 'Laporan berhasil diregistrasi dengan nomor tiket '.(string) $ticket['ticket_number'].'.',
            heading: 'Tiket Insiden Diterbitkan',
            variant: 'success',
        );
    }

    public function resetForm(): void
    {
        $this->reset([
            'reporter_name',
            'reporter_email',
            'reporter_org',
            'affected_system',
            'description',
            'agreed_disclosure',
            'submittedTicket',
        ]);
        $this->category = 'Kerentanan Aplikasi (Responsible Disclosure)';
        $this->severity = 'Tinggi';
    }
};
