<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::public')]
#[Title('PKU-CSIRT — Computer Security Incident Response Team Pemerintah Kota Pekanbaru')]
class extends Component
{
    public string $categoryFilter = '';

    public function setCategory(string $category): void
    {
        $this->categoryFilter = $category;
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
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function services(): array
    {
        return app(CsirtDataService::class)->servicesCatalog();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function articles(): array
    {
        return app(CsirtDataService::class)->articles();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function filteredArticles(): array
    {
        $articles = $this->articles;

        if ($this->categoryFilter === '') {
            return $articles;
        }

        return array_values(array_filter(
            $articles,
            fn (array $article): bool => mb_strtolower((string) ($article['category'] ?? '')) === mb_strtolower($this->categoryFilter)
        ));
    }

    /**
     * @return array<int, string>
     */
    #[Computed]
    public function articleCategories(): array
    {
        $categories = [];

        foreach ($this->articles as $article) {
            $category = (string) ($article['category'] ?? '');
            if ($category !== '' && ! in_array($category, $categories, true)) {
                $categories[] = $category;
            }
        }

        return $categories;
    }

    /**
     * @return array<string, mixed>
     */
    #[Computed]
    public function hofSummary(): array
    {
        return app(CsirtDataService::class)->hallOfFameData();
    }

    /**
     * @return array<int, array{section: string, title: string, summary: string, details: array<int, string>}>
     */
    #[Computed]
    public function rfcSections(): array
    {
        return app(CsirtDataService::class)->rfc2350Sections();
    }
};
