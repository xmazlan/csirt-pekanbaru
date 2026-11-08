<?php

declare(strict_types=1);

use App\Services\CsirtDataService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::public')]
#[Title('Publikasi Berita & Advisori — PKU-CSIRT Pekanbaru')]
class extends Component
{
    public int $id = 0;

    public function mount(int $id): void
    {
        $this->id = $id;

        if (! app(CsirtDataService::class)->findArticle($id)) {
            abort(404, 'Artikel berita atau advisori tidak ditemukan.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    #[Computed]
    public function article(): array
    {
        return app(CsirtDataService::class)->findArticle($this->id) ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    #[Computed]
    public function relatedArticles(): array
    {
        $current = $this->article;
        $category = (string) ($current['category'] ?? '');

        $all = app(CsirtDataService::class)->articles();

        $filtered = array_values(array_filter(
            $all,
            fn (array $a): bool => (int) ($a['id'] ?? 0) !== $this->id
        ));

        // Prioritaskan kategori yang sama
        usort($filtered, static function (array $a, array $b) use ($category): int {
            $aMatches = (string) ($a['category'] ?? '') === $category;
            $bMatches = (string) ($b['category'] ?? '') === $category;

            if ($aMatches === $bMatches) {
                return 0;
            }

            return $aMatches ? -1 : 1;
        });

        return array_slice($filtered, 0, 3);
    }
};
