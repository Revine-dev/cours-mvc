<?php include "layout/header.php"; ?>

<main class="pt-32 pb-24 px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex-1 min-w-0">
            <nav class="flex mb-4 text-sm font-medium text-secondary/60">
                <a href="<?= $this->route('home') ?>" class="hover:text-accent transition-colors">Accueil</a>
                <span class="mx-2">/</span>
                <a href="<?= $this->route('properties') ?>" class="hover:text-accent transition-colors">Propriétés</a>
                <span class="mx-2">/</span>
                <span class="text-secondary"><?= $property->title; ?></span>
            </nav>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-2">
                <h1 class="text-4xl md:text-5xl font-semibold tracking-tight text-primary break-words">
                    <?= $property->title; ?>
                </h1>
                <?php if ((string) $property->status === 'compromise') : ?>
                    <span class="px-3 py-1 bg-success text-background text-xs font-bold uppercase tracking-widest rounded-full shadow-sm">
                        Sous compromis
                    </span>
                <?php endif; ?>
            </div>
            <p class="text-lg text-secondary flex items-center gap-2">
                <i class="fa-solid fa-location-dot text-accent"></i>
                <?= $property->location->address; ?>, <?= $property->location->city; ?> (<?= $property->location->postal_code; ?>)
            </p>
        </div>
        <div class="text-left md:text-right shrink-0">
            <p class="text-sm font-medium text-secondary uppercase tracking-wider mb-1">Prix de vente</p>
            <p class="text-3xl md:text-4xl font-bold text-accent whitespace-nowrap">
                <?= $property->price->format_price($property->currency) ?>
            </p>
        </div>
    </div>

    <!-- Gallery -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12 rounded-3xl overflow-hidden">
        <div class="aspect-video md:aspect-square bg-secondary/10">
            <img src="<?= $property->images[0]; ?>" alt="<?= $property->title; ?>" class="w-full h-full object-cover">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <?php foreach ($property->images->slice(1) as $image) : ?>
                <div class="aspect-square bg-secondary/10">
                    <img src="<?= $image; ?>" alt="Detail" class="w-full h-full object-cover">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-primary mb-6 pb-2 border-b border-secondary/10">Description</h2>
                <p class="text-secondary leading-relaxed text-lg whitespace-pre-line">
                    <?= $property->description; ?>
                </p>
            </section>

            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-primary mb-6 pb-2 border-b border-secondary/10">Caractéristiques</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-8">
                    <?php if ($property->features->area) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 mb-1">Surface</span>
                            <span class="font-semibold text-primary"><?= $property->features->area; ?> m²</span>
                        </div>
                    <?php endif; ?>
                    <?php if ($property->features->bedrooms) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 mb-1">Chambres</span>
                            <span class="font-semibold text-primary"><?= $property->features->bedrooms; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($property->features->bathrooms) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 mb-1">Salles de bain</span>
                            <span class="font-semibold text-primary"><?= $property->features->bathrooms; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($property->features->year_built) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 mb-1">Année constr.</span>
                            <span class="font-semibold text-primary"><?= $property->features->year_built; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($property->features->floor) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 mb-1">Étage</span>
                            <span class="font-semibold text-primary"><?= $property->features->floor; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($property->features->garage) : ?>
                        <div class="flex flex-col">
                            <span class="text-sm text-secondary/60 mb-1">Garage</span>
                            <span class="font-semibold text-primary"><?= $property->features->garage ? 'Oui' : 'Non'; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-semibold text-primary mb-6 pb-2 border-b border-secondary/10">Prestations</h2>
                <div class="flex flex-wrap gap-3">
                    <?php foreach ($property->amenities as $amenity) : ?>
                        <span class="px-4 py-2 bg-background border border-secondary/20 rounded-full text-secondary text-sm font-medium">
                            <?= $amenity->name; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Sidebar / Contact -->
        <div class="lg:col-span-1">
            <div class="sticky top-32 p-8 bg-background border border-secondary/20 rounded-3xl shadow-sm">
                <h3 class="text-xl font-semibold text-primary mb-6 text-center">Contacter l'agent</h3>
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 rounded-full bg-accent/10 flex items-center justify-center text-accent text-2xl font-bold">
                        <?= $property->agent->name->substr(0, 1); ?>
                    </div>
                    <div>
                        <p class="font-semibold text-primary text-lg"><?= $property->agent->name; ?></p>
                        <p class="text-sm text-secondary">Agent Immobilier Senior</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <a href="tel:<?= $property->agent->phone; ?>" class="flex items-center justify-center gap-3 w-full py-4 bg-primary text-background rounded-xl font-semibold hover:bg-primary/90 transition-all">
                        <i class="fa-solid fa-phone"></i> <?= $property->agent->phone; ?>
                    </a>
                    <a href="mailto:<?= $property->agent->email; ?>" class="flex items-center justify-center gap-3 w-full py-4 bg-background border border-secondary/30 text-primary rounded-xl font-semibold hover:border-accent hover:text-accent transition-all">
                        <i class="fa-solid fa-envelope"></i> Envoyer un email
                    </a>
                </div>
                <p class="mt-6 text-xs text-secondary/60 text-center">
                    Réponse moyenne sous 2 heures.
                </p>
            </div>
        </div>
    </div>
</main>

<?php include "layout/footer.php"; ?>