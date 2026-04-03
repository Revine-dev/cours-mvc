<?php include "layout/header.php"; ?>

<main class="pt-32 pb-24 px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row gap-12">
        <!-- Sidebar Filtres -->
        <aside class="w-full md:w-80 flex-shrink-0">
            <div class="sticky top-32 bg-background border border-secondary/20 rounded-3xl p-8 shadow-sm">
                <h2 class="text-xl font-semibold text-primary mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-accent text-sm"></i> Filtres
                </h2>

                <form action="<?= $this->route('properties') ?>" method="GET" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Ville</label>
                        <input type="text" name="city" value="<?= $filters->city ?>" placeholder="Ex: Paris"
                            class="w-full px-4 py-3 bg-white border border-secondary/20 rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Type de bien</label>
                        <select name="type" class="w-full px-4 py-3 bg-white border border-secondary/20 rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent appearance-none cursor-pointer">
                            <option value="">Tous les types</option>
                            <option value="apartment" <?= $filters->type === 'apartment' ? 'selected' : '' ?>>Appartement</option>
                            <option value="house" <?= $filters->type === 'house' ? 'selected' : '' ?>>Maison</option>
                            <option value="loft" <?= $filters->type === 'loft' ? 'selected' : '' ?>>Loft</option>
                            <option value="building" <?= $filters->type === 'building' ? 'selected' : '' ?>>Immeuble</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Budget Max (€)</label>
                        <input type="number" name="max_price" value="<?= $filters->max_price ?>" placeholder="Ex: 2000000"
                            class="w-full px-4 py-3 bg-white border border-secondary/20 rounded-xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                    </div>

                    <div class="pt-4 space-y-3">
                        <button type="submit" class="w-full py-4 bg-primary text-background rounded-xl font-semibold hover:bg-accent transition-all shadow-md">
                            Appliquer les filtres
                        </button>
                        <a href="<?= $this->route('properties') ?>" class="block w-full py-3 text-center text-sm font-medium text-secondary hover:text-primary transition-colors">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Liste des Résultats -->
        <div class="flex-1">
            <div class="mb-8 flex items-baseline justify-between">
                <h1 class="text-3xl font-semibold text-primary">
                    <?= count($properties) ?> bien<?= count($properties) > 1 ? 's' : '' ?> trouvé<?= count($properties) > 1 ? 's' : '' ?>
                </h1>
                <p class="text-secondary text-sm italic">Triés par les plus récents</p>
            </div>

            <?php if (count($properties) > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <?php foreach ($properties as $property): ?>
                        <a href="<?= $this->route('view-property', ['city' => $this->slugify($property->location->city), 'slug' => $property->slug]); ?>" class="bg-background rounded-2xl border border-secondary/20 overflow-hidden hover:border-accent hover:shadow-md transition-all group flex flex-col">
                            <div class="aspect-[4/3] relative overflow-hidden bg-secondary/10">
                                <img src="<?= $property->images[0]; ?>" alt="<?= $property->title; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out">
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-md text-[10px] font-bold text-primary uppercase tracking-widest shadow-sm">
                                    <?= $property->type; ?>
                                </div>
                                <?php if ($property->status == 'compromise'): ?>
                                    <div class="absolute top-3 right-3 bg-success text-background px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest shadow-sm">
                                        Sous compromis
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold text-accent mb-1">
                                        <?= $property->price->format_price($property->currency) ?>
                                    </h3>
                                    <p class="text-primary font-medium line-clamp-1"><?= $property->title; ?></p>
                                    <p class="text-secondary text-sm mt-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-dot text-xs"></i>
                                        <?= $property->location->city; ?> (<?= $property->location->postal_code; ?>)
                                    </p>
                                </div>
                                <div class="pt-4 border-t border-secondary/10 flex justify-between text-sm text-secondary">
                                    <?php if ($property->features->bedrooms): ?>
                                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-bed text-accent/60 text-xs"></i> <?= $property->features->bedrooms; ?> Ch.</span>
                                    <?php endif; ?>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-vector-square text-accent/60 text-xs"></i> <?= $property->features->area; ?> m²</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="py-24 text-center bg-secondary/5 rounded-3xl border-2 border-dashed border-secondary/20">
                    <i class="fa-solid fa-house-crack text-4xl text-secondary/30 mb-4"></i>
                    <h3 class="text-xl font-medium text-primary mb-2">Aucun bien ne correspond à vos critères</h3>
                    <p class="text-secondary">Essayez de modifier vos filtres ou de réinitialiser la recherche.</p>
                    <a href="<?= $this->route('properties') ?>" class="inline-block mt-6 text-accent font-semibold hover:underline">Voir tous les biens</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include "layout/footer.php"; ?>