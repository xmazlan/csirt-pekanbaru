<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::public')]
#[Title('Berita & Advisori Keamanan Siber — PKU-CSIRT Pemerintah Kota Pekanbaru')]
class extends Component
{
    public string $category = '';

    public string $search = '';

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function resetFilter(): void
    {
        $this->category = '';
        $this->search = '';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function articles(): array
    {
        return app(CsirtDataService::class)->articles(
            category: $this->category !== '' ? $this->category : null,
            search: $this->search !== '' ? $this->search : null,
        );
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function categories(): array
    {
        return app(CsirtDataService::class)->articleCategories();
    }
};
