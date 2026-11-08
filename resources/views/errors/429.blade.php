<x-layouts::error :title="__('429 - Terlalu Banyak Permintaan')">
    <x-error-page
        code="429"
        :badge="__('Galat 429 (Too Many Requests)')"
        :title="__('Batas Permintaan Terlampaui')"
        :message="__('Server menerima terlalu banyak permintaan dari peramban Anda dalam waktu singkat. Mohon tunggu beberapa saat sebelum mencoba kembali.')"
        icon="bolt"
        :show-reload="true"
        :show-home="true"
        :show-dashboard="true"
    />
</x-layouts::error>

