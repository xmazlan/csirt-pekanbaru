<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new
#[Layout('layouts::public')]
#[Title('Hall of Fame Security Researcher — PKU-CSIRT Kota Pekanbaru')]
class extends Component
{
    #[Url(as: 'tahun', except: '')]
    public string $yearFilter = '';

    #[Url(as: 'tingkat', except: '')]
    public string $severityFilter = '';

    #[Url(as: 'cari', except: '')]
    public string $search = '';

    public function resetFilters(): void
    {
        $this->yearFilter = '';
        $this->severityFilter = '';
        $this->search = '';
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
     * @return array{
     *     entries: array<int, array<string, mixed>>,
     *     leaderboard: array<int, array<string, mixed>>,
     *     years: array<int, string>,
     *     stats: array{total_entries: int, total_researchers: int, critical_high_count: int}
     * }
     */
    #[Computed]
    public function hofData(): array
    {
        return app(CsirtDataService::class)->hallOfFameData(
            yearFilter: $this->yearFilter !== '' ? $this->yearFilter : null,
            severityFilter: $this->severityFilter !== '' ? $this->severityFilter : null,
            search: $this->search !== '' ? $this->search : null,
        );
    }
};
