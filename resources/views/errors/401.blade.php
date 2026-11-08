<x-layouts::error :title="__('401 - Tidak Terotorisasi')">
    <x-error-page
        code="401"
        :badge="__('Galat 401 (Unauthorized)')"
        :title="__('Autentikasi Diperlukan')"
        :message="__('Anda perlu masuk dengan akun terdaftar untuk mengakses halaman atau sumber daya ini.')"
        icon="lock-closed"
        :show-login="true"
        :show-home="true"
        :show-dashboard="true"
    />
</x-layouts::error>

