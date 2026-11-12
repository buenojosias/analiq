<div>
    <x-page-header title="Detalhes da referência" :subtitle="$reference->title" />
    <div class="sm:grid grid-cols-5 gap-4">
        <div class="col-span-2">
            <x-ts-card header="Detalhes">
                <div class="detail">
                    <x-detail label="Título" :value="$reference->title" />
                    <x-detail label="Tipo" :value="$reference->type" />
                    <x-detail label="Autor(es) abreviado(s)" :value="$reference->short_author" />
                    <x-detail label="Nome(s) completo(s)" :value="$reference->long_author" />
                    <x-detail label="Ano" :value="$reference->year" />
                    <x-detail label="Referência completa" :value="$reference->full" />
                </div>
                <div class="grid grid-cols-2 mt-4 gap-4">
                    <x-detail label="Número de produções" :value="$productions->count()" />
                    <x-detail label="Número de citações" :value="$productions->sum('citations_count')" />
                </div>
            </x-ts-card>
        </div>
        <div class="col-span-3">
            <x-ts-table :$headers :rows="$productions">
                <x-slot:header>Produções com a referência</x-slot:header>
            </x-ts-table>
        </div>
    </div>
</div>
