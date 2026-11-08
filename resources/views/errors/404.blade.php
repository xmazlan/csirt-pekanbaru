<x-layouts::error :title="__('404 - Halaman Tidak Ditemukan')">
    <x-error-page
        code="404"
        :badge="__('Galat 404 (Not Found)')"
        :title="__('Halaman Tidak Ditemukan')"
        :message="__('Mohon maaf, halaman yang Anda tuju tidak tersedia, telah dipindahkan, atau alamat URL yang Anda masukkan salah.')"
        icon="magnifying-glass"
        :show-home="true"
        :show-dashboard="true"
    />
</x-layouts::error>

