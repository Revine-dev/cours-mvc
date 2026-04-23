<?php include "layout/bo.header.php"; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-semibold text-primary dark:text-background transition-colors">Annonces Immobilières</h1>
        <p class="text-sm text-secondary dark:text-background/60 mt-1 transition-colors">Gérez vos biens, modifiez les prix et suivez les statuts.</p>
    </div>
    <a href="<?= $this->route('create-ad') ?>" class="bg-accent hover:bg-primary dark:hover:bg-accent/80 text-background px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nouvelle Annonce
    </a>
</div>

<div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl p-2 mb-6 flex flex-col sm:flex-row gap-2 shadow-sm transition-all">
    <div class="flex-1 flex items-center px-3">
        <i class="fa-solid fa-magnifying-glass text-secondary dark:text-background/40"></i>
        <input type="text" placeholder="Rechercher par adresse, référence..." class="w-full py-2 px-3 bg-transparent text-primary dark:text-background placeholder-secondary/60 dark:placeholder-background/30 focus:outline-none text-sm transition-colors">
    </div>
    <div class="hidden sm:block w-px h-8 bg-secondary/20 dark:bg-background/10 self-center"></div>
    <select class="py-2 px-4 bg-transparent text-secondary dark:text-background/60 focus:outline-none text-sm font-medium cursor-pointer appearance-none transition-colors">
        <option class="bg-background dark:bg-primary">Tous les statuts</option>
        <option class="bg-background dark:bg-primary">Actif</option>
        <option class="bg-background dark:bg-primary">En compromis</option>
        <option class="bg-background dark:bg-primary">Vendu</option>
        <option class="bg-background dark:bg-primary">Brouillon</option>
    </select>
    <div class="hidden sm:block w-px h-8 bg-secondary/20 dark:bg-background/10 self-center"></div>
    <select class="py-2 px-4 bg-transparent text-secondary dark:text-background/60 focus:outline-none text-sm font-medium cursor-pointer appearance-none transition-colors">
        <option class="bg-background dark:bg-primary">Toutes les villes</option>
        <option class="bg-background dark:bg-primary">Paris</option>
        <option class="bg-background dark:bg-primary">Lyon</option>
        <option class="bg-background dark:bg-primary">Bordeaux</option>
    </select>
</div>

<div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl shadow-sm overflow-hidden transition-all">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-secondary/20 dark:border-background/10 bg-secondary/5 dark:bg-background/5 transition-colors">
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider">Bien & Localisation</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider">Prix</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider">Caractéristiques</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider">Statut</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary/10 dark:divide-background/5">

                <?php if (count($ads) > 0) : ?>
                    <?php foreach ($ads as $ad) : ?>
                    <tr class="hover:bg-secondary/5 dark:hover:bg-background/5 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-12 rounded-lg bg-secondary/20 dark:bg-background/10 overflow-hidden flex-shrink-0 transition-colors">
                                    <img src="<?= $ad->images[0] ?>" alt="Thumb" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-medium text-primary dark:text-background text-sm transition-colors"><?= $ad->title ?></div>
                                    <div class="text-[10px] text-accent font-semibold uppercase tracking-wider mb-0.5"><?= $this->translatePropertyType($ad->type) ?></div>
                                    <div class="text-xs text-secondary dark:text-background/40 mt-0.5 transition-colors"><?= $ad->location['address'] ?>, <?= $ad->location['city'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-primary dark:text-background text-sm transition-colors"><?= $ad->price->format_price() ?></div>
                            <div class="text-xs text-secondary dark:text-background/40 mt-0.5 transition-colors">Réf: <?= strtoupper(substr(is_object($ad->location['city']) ? $ad->location['city']->dangerousRaw() : $ad->location['city'], 0, 3)) ?>-<?= $ad->id ?></div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-secondary dark:text-background/60 transition-colors"><?= $ad->features['area'] ?> m² • <?= $ad->features['bedrooms'] ?> Ch.</div>
                        </td>
                        <td class="py-4 px-6">
                            <?php
                            $statusClass = 'bg-secondary/10 text-secondary border-secondary/20 dark:bg-background/10 dark:text-background/60 dark:border-background/10';
                            $statusLabel = 'Brouillon';
                            $statusValue = is_object($ad->status) ? $ad->status->dangerousRaw() : $ad->status;
                            if ($statusValue == 'for_sale') {
                                $statusClass = 'bg-success/10 text-success border-success/20 dark:bg-success/20 dark:text-success dark:border-success/30';
                                $statusLabel = 'Actif';
                            } elseif ($statusValue == 'compromise') {
                                $statusClass = 'bg-accent/10 text-accent border-accent/20 dark:bg-accent/20 dark:text-accent dark:border-accent/30';
                                $statusLabel = 'En compromis';
                            } elseif ($statusValue == 'sold') {
                                $statusClass = 'bg-secondary/10 text-secondary border-secondary/20 dark:bg-background/10 dark:text-background/60 dark:border-background/10';
                                $statusLabel = 'Vendu';
                            }
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $statusClass ?> transition-colors">
                                <?= $statusLabel ?>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= $this->route('edit-ad', ['id' => $ad->id]) ?>" class="w-8 h-8 rounded-lg flex items-center justify-center text-secondary dark:text-background/40 hover:text-accent dark:hover:text-accent hover:bg-accent/10 dark:hover:bg-accent/20 transition-all" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-secondary dark:text-background/40 hover:text-primary dark:hover:text-background hover:bg-secondary/20 dark:hover:bg-background/10 transition-all" title="Archiver">
                                    <i class="fa-solid fa-box-archive"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="py-8 px-6 text-center text-secondary dark:text-background/40 italic transition-colors">Aucune annonce trouvée.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-secondary/20 dark:border-background/10 flex items-center justify-between transition-colors">
        <span class="text-sm text-secondary dark:text-background/60">Affichage de <?= $pagination['from'] ?> à <?= $pagination['to'] ?> sur <?= $pagination['count'] ?> annonces</span>
        <div class="flex gap-1">
            <?php
                $currentPage = is_object($pagination['current']) ? $pagination['current']->dangerousRaw() : $pagination['current'];
                $totalPages = is_object($pagination['total']) ? $pagination['total']->dangerousRaw() : $pagination['total'];
            ?>
            <a href="<?= $this->route('ads', [], ['page' => $currentPage - 1]) ?>" 
               class="w-8 h-8 rounded-lg border border-secondary/20 dark:border-background/10 flex items-center justify-center text-secondary dark:text-background/60 hover:bg-secondary/10 dark:hover:bg-background/10 transition-all <?= $currentPage <= 1 ? 'opacity-50 pointer-events-none' : '' ?>">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
            
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="<?= $this->route('ads', [], ['page' => $i]) ?>" 
                   class="w-8 h-8 rounded-lg border <?= $i == $currentPage ? 'border-accent bg-accent text-background dark:border-accent dark:bg-accent dark:text-background' : 'border-secondary/20 dark:border-background/10 text-secondary dark:text-background/60 hover:bg-secondary/10 dark:hover:bg-background/10' ?> flex items-center justify-center text-sm font-medium transition-all">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <a href="<?= $this->route('ads', [], ['page' => $currentPage + 1]) ?>" 
               class="w-8 h-8 rounded-lg border border-secondary/20 dark:border-background/10 flex items-center justify-center text-secondary dark:text-background/60 hover:bg-secondary/10 dark:hover:bg-background/10 transition-all <?= $currentPage >= $totalPages ? 'opacity-50 pointer-events-none' : '' ?>">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        </div>
    </div>
</div>

<?php include "layout/bo.footer.php"; ?>