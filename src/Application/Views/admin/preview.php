<?php include VIEWS . "layout/header.php"; ?>

<div class="bg-accent py-2 text-center text-background text-sm font-medium sticky top-0 z-50">
    <i class="fa-solid fa-eye mr-2"></i> Mode Prévisualisation Administrateur
    <a href="javascript:window.close()" class="ml-4 underline hover:text-primary">Fermer</a>
</div>

<main class="pt-32 pb-24 px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex mb-4 text-sm font-medium text-secondary/60 dark:text-background/40">
                <span class="hover:text-accent transition-colors">Accueil</span>
                <span class="mx-2">/</span>
                <span class="hover:text-accent transition-colors">Propriétés</span>
                <span class="mx-2">/</span>
                <span class="text-secondary dark:text-background/60"><?= $ad->title; ?></span>
            </nav>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-2">
                <h1 class="text-4xl md:text-5xl font-semibold tracking-tight text-primary dark:text-background transition-colors">
                    <?= $ad->title; ?>
                </h1>
                <?php if ((string) $ad->status == 'compromise') : ?>
                    <span class="px-3 py-1 bg-success text-background text-xs font-bold uppercase tracking-widest rounded-full shadow-sm">
                        Sous compromis
                    </span>
                <?php endif; ?>
            </div>
            <p class="text-lg text-secondary dark:text-background/60 flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-location-dot text-accent"></i>
                <?= $ad->location->address; ?>, <?= $ad->location->city; ?> (<?= $ad->location->postal_code; ?>)
            </p>
        </div>
        <div class="text-left md:text-right">
            <p class="text-sm font-medium text-secondary dark:text-background/40 uppercase tracking-wider mb-1 transition-colors">Prix de vente</p>
            <p class="text-4xl font-bold text-accent">
                <?= $ad->price->format_price($ad->currency) ?>
            </p>
        </div>
    </div>

    <!-- Gallery Placeholder -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12 rounded-3xl overflow-hidden shadow-sm transition-all">
        <div class="aspect-video md:aspect-square bg-secondary/10 dark:bg-white/5 flex items-center justify-center text-secondary dark:text-background/20 transition-colors">
            <?php if (!empty($ad->images[0])) : ?>
                <img src="<?= $ad->images[0]; ?>" alt="<?= $ad->title; ?>" class="w-full h-full object-cover">
            <?php else : ?>
                <i class="fa-solid fa-image text-6xl"></i>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-2 gap-4 bg-secondary/5 dark:bg-white/5 items-center justify-center text-secondary dark:text-background/20 italic text-sm transition-colors">
            Aucune autre image disponible en prévisualisation
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-primary dark:text-background mb-6 pb-2 border-b border-secondary/10 dark:border-background/10 transition-all">Description</h2>
                <p class="text-secondary dark:text-background/80 leading-relaxed text-lg whitespace-pre-line transition-colors">
                    <?= $ad->description; ?>
                </p>
            </section>

            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-primary dark:text-background mb-6 pb-2 border-b border-secondary/10 dark:border-background/10 transition-all">Caractéristiques</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-8">
                    <?php if ($ad->features->area) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 dark:text-background/40 mb-1 transition-colors">Surface</span>
                            <span class="font-semibold text-primary dark:text-background transition-colors"><?= $ad->features->area; ?> m²</span>
                        </div>
                    <?php endif; ?>
                    <?php if ($ad->features->bedrooms) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 dark:text-background/40 mb-1 transition-colors">Chambres</span>
                            <span class="font-semibold text-primary dark:text-background transition-colors"><?= $ad->features->bedrooms; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($ad->features->year_built) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 dark:text-background/40 mb-1 transition-colors">Année constr.</span>
                            <span class="font-semibold text-primary dark:text-background transition-colors"><?= $ad->features->year_built; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <!-- Sidebar / Contact (Mockup) -->
        <div class="lg:col-span-1">
            <div class="sticky top-32 p-8 bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-3xl shadow-sm transition-all opacity-50 pointer-events-none">
                <h3 class="text-xl font-semibold text-primary dark:text-background mb-6 text-center transition-colors">Contact (Désactivé)</h3>
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 rounded-full bg-secondary/10 dark:bg-background/10 flex items-center justify-center text-secondary dark:text-background/40 text-2xl font-bold transition-colors">
                        ?
                    </div>
                    <div>
                        <p class="font-semibold text-primary dark:text-background text-lg transition-colors">Agent Immobilier</p>
                        <p class="text-sm text-secondary dark:text-background/40 transition-colors">Aperçu uniquement</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-center gap-3 w-full py-4 bg-secondary/20 dark:bg-background/10 text-secondary dark:text-background/60 rounded-xl font-semibold transition-colors">
                        <i class="fa-solid fa-phone"></i> +33 0 00 00 00 00
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include VIEWS . "layout/footer.php"; ?>