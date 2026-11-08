<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Title('Manajemen Insiden Siber — Dashboard PKU-CSIRT')]
class extends Component
{
    public string $statusFilter = '';

    public string $search = '';

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
    }

    public function updateStatus(int $id, string $status): void
    {
        $updated = app(CsirtDataService::class)->updateIncidentStatus($id, $status);

        if ($updated) {
            unset($this->reports);

            Flux::toast(
                text: 'Status penanganan tiket insiden berhasil diperbarui menjadi "'.strtoupper($status).'".',
                heading: 'Status Tiket Diperbarui',
                variant: 'success',
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function reports(): array
    {
        return app(CsirtDataService::class)->incidentReports(
            statusFilter: $this->statusFilter !== '' ? $this->statusFilter : null,
            search: $this->search !== '' ? $this->search : null,
        );
    }

    /**
     * @return array{total: int, baru: int, diproses: int, selesai: int}
     */
    #[Computed]
    public function counts(): array
    {
        $all = app(CsirtDataService::class)->incidentReports();

        return [
            'total' => count($all),
            'baru' => count(array_filter($all, static fn (array $r): bool => ($r['status'] ?? '') === 'baru')),
            'diproses' => count(array_filter($all, static fn (array $r): bool => ($r['status'] ?? '') === 'diproses')),
            'selesai' => count(array_filter($all, static fn (array $r): bool => ($r['status'] ?? '') === 'selesai')),
        ];
    }
};
