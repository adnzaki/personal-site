<div class="h-2 w-full bg-gradient-to-r from-blue-900 via-blue-700 to-indigo-900"></div>
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <a href="<?= base_url() ?>" class="flex items-center group focus:outline-none">
            <img src="<?= base_url('img/core-img/small-logo.png') ?>" alt="Bit & Bait Logo" class="h-11 w-auto object-contain group-hover:scale-[1.03] transition-transform duration-300">
        </a>

        <?php $active = $activeMenu ?? ''; ?>

        <nav class="hidden md:flex items-center space-x-8 font-medium text-sm text-slate-600">
            <a href="<?= base_url(); ?>"
                class="<?= $active == '' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600 transition-colors' ?> pb-1 px-1">
                Home
            </a>

            <a href="<?= base_url('posts/category/nurani') ?>"
                class="<?= $active == 'nurani' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600 transition-colors' ?> pb-1 px-1">
                Nurani
            </a>

            <a href="<?= base_url('posts/category/coding') ?>"
                class="<?= $active == 'coding' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600 transition-colors' ?> pb-1 px-1">
                Coding
            </a>

            <a href="<?= base_url('posts/category/tekno') ?>"
                class="<?= $active == 'tekno' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600 transition-colors' ?> pb-1 px-1">
                Tekno
            </a>

            <a href="<?= base_url('posts/category/puisi') ?>"
                class="<?= $active == 'puisi' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600 transition-colors' ?> pb-1 px-1">
                Puisi
            </a>

            <a href="mailto:vuedevo@gmail.com" class="hover:text-blue-600 transition-colors pb-1 px-1">Contact</a>
        </nav>

        <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-100 rounded-xl transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <div id="mobile-menu" class="hidden md:hidden w-full bg-white border-t border-slate-50 border-b border-slate-100 shadow-sm px-6 pt-3 pb-6 space-y-1">

        <a href="<?= base_url() ?>"
            class="block px-4 py-2.5 rounded-xl font-medium text-sm transition-all <?= $active == '' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' ?>">
            Home
        </a>

        <a href="<?= base_url('posts/category/coding') ?>"
            class="block px-4 py-2.5 rounded-xl font-medium text-sm transition-all <?= $active == 'coding' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' ?>">
            Coding
        </a>

        <a href="<?= base_url('posts/category/tekno') ?>"
            class="block px-4 py-2.5 rounded-xl font-medium text-sm transition-all <?= $active == 'tekno' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' ?>">
            Tekno
        </a>

        <a href="<?= base_url('posts/category/nurani') ?>"
            class="block px-4 py-2.5 rounded-xl font-medium text-sm transition-all <?= $active == 'nurani' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' ?>">
            Nurani
        </a>

        <a href="<?= base_url('posts/category/puisi') ?>"
            class="block px-4 py-2.5 rounded-xl font-medium text-sm transition-all <?= $active == 'puisi' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' ?>">
            Puisi
        </a>

        <a href="mailto:vuedevo@gmail.com"
            class="block px-4 py-2.5 rounded-xl font-medium text-sm transition-all <?= $active == 'contact' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' ?>">
            Contact
        </a>
    </div>

</header>