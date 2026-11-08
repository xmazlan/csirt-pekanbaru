<x-layouts::error :title="__('500 - Kesalahan Server')">
    <x-error-page
        code="500"
        :badge="__('Galat 500 (Internal Server Error)')"
        :title="__('Terjadi Kesalahan Server')"
        :message="__('Terjadi kendala teknis internal pada sistem. Tim tanggap insiden dan pengembang telah mencatat kejadian ini untuk segera diselesaikan.')"
        icon="exclamation-triangle"
        :show-reload="true"
        :show-home="true"
        :show-dashboard="true"
    />
</x-layouts::error>

