<!DOCTYPE html>
<html lang="pt-br" class="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Layout Analiq</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite('resources/css/app.css')
</head>

<body class="h-screen flex flex-col overflow-hidden bg-lightBg dark:bg-darkBg text-gray-800 dark:text-gray-100"
    x-data="{
        showHeader: true,
        showSidebarA: true,
        collapseSidebarA: false,
        showSidebarB: true,
        showSidebarR: true,
        showToolbar: true
    }">

    <header class="dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <!-- Top Bar -->
        <div
            class="h-10 flex items-center justify-between px-4 text-sm bg-white dark:bg-gray-900/60 shadow">
            <div><x-application-logo /></div>
            <div class="font-semibold truncate">Título do projeto</div>
            <div class="w-24 h-6 bg-gray-600 rounded" x-on:click="showHeader = !showHeader"></div>
        </div>

        <!-- Menu horizontal -->
        <div x-show="showHeader" x-collapse
            class="h-9 relative overflow-x-auto whitespace-nowrap px-4 text-sm border-t border-lightBorder dark:border-darkBorder bg-gray-100 dark:bg-gray-800 shadow-sm">
            MENU VERTICAL
            <div class="absolute bottom-9 z-100 left-0 w-full h-9 px-4 flex items-center border-lightBorder dark:border-darkBorder bg-gray-100 dark:bg-gray-800"
                x-show="true">
                SUBMENU (oculto por padrão)
            </div>
        </div>

        <!-- Título da página -->
        <div x-show="showHeader" x-collapse
            class="z-0 h-9 px-4 text-sm font-bold flex items-center bg-gray-100 dark:bg-gray-800 shadow">
            Título da página
        </div>
    </header>

    <!-- Área principal -->
    <div class="flex-1 flex bg-lightBg dark:bg-darkBg">
        <!-- Sidebar A -->
        <div :class="collapseSidebarA ? 'w-6' : 'w-[180px]'"
            class="h-full overflow-y-auto transition-all duration-300 relative border-r border-lightBorder dark:border-darkBorder bg-lightSidebar dark:bg-darkSidebar">
            <button
                class="absolute top-2 right-2 text-xs bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded px-1 py-0.5 border dark:border-gray-600"
                @click="collapseSidebarA = !collapseSidebarA">
                <span x-text="collapseSidebarA ? '>' : '<'"></span>
            </button>
            <div x-show="!collapseSidebarA" class="p-2 text-xs">
                SIDEBAR A
            </div>
        </div>

        <!-- Sidebar B -->
        <template x-if="showSidebarB">
            <div
                class="w-[200px] h-full overflow-y-auto p-2 text-xs border-r border-lightBorder dark:border-darkBorder bg-gray-200 dark:bg-gray-700">
                SIDEBAR B
            </div>
        </template>

        <!-- Área principal de seções -->
        <div class="flex-1 flex flex-col m-2">
            <!-- Toolbar -->
            <template x-if="showToolbar">
                <div
                    class="min-h-9 px-2 py-2 mb-2 text-sm border border-lightBorder dark:border-darkBorder rounded bg-white dark:bg-gray-800 shadow">
                    TOOLBAR
                </div>
            </template>

            <!-- Seções -->
            <div class="flex flex-1 gap-0.5 overflow-hidden">
                <div
                    class="w-2/5 h-full overflow-y-auto border border-lightBorder dark:border-darkBorder p-2 text-sm bg-white dark:bg-gray-900 rounded">
                    SECTION B
                </div>
                <div
                    class="flex-1 h-full overflow-y-auto border border-lightBorder dark:border-darkBorder p-2 text-sm bg-white dark:bg-gray-900 rounded">
                    SECTION C
                </div>
            </div>
        </div>

        <!-- Sidebar RIGHT -->
        <template x-if="showSidebarR">
            <div
                class="w-[300px] h-full overflow-y-auto p-2 text-xs border-l border-lightBorder dark:border-darkBorder bg-white dark:bg-gray-900">
                SIDEBAR R
            </div>
        </template>
    </div>
</body>

</html>
