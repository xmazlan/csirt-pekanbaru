<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::public')]
#[Title('Dokumen Standar IETF RFC-2350 — PKU-CSIRT Kota Pekanbaru')]
class extends Component
{
    public int $activeSectionIndex = 0;

    public function selectSection(int $index): void
    {
        $this->activeSectionIndex = $index;
    }

    /**
     * @return array<string, mixed>
     */
    #[Computed]
    public function profile(): array
    {
        return app(CsirtDataService::class)->siteProfile();
    }

    /**
     * @return array<int, array{section: string, title: string, summary: string, details: array<int, string>}>
     */
    #[Computed]
    public function sections(): array
    {
        return app(CsirtDataService::class)->rfc2350Sections();
    }
};
