<?php include "layout/bo.header.php"; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-semibold text-primary">Annonces Immobilières</h1>
        <p class="text-sm text-secondary mt-1">Gérez vos biens, modifiez les prix et suivez les statuts.</p>
    </div>
    <a href="<?= $this->route('create-ad') ?>" class="bg-accent hover:bg-primary text-background px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nouvelle Annonce
    </a>
</div>

<div class="bg-background border border-secondary/20 rounded-2xl p-2 mb-6 flex flex-col sm:flex-row gap-2 shadow-sm">
    <div class="flex-1 flex items-center px-3">
        <i class="fa-solid fa-magnifying-glass text-secondary"></i>
        <input type="text" placeholder="Rechercher par adresse, référence..." class="w-full py-2 px-3 bg-transparent text-primary placeholder-secondary/60 focus:outline-none text-sm">
    </div>
    <div class="hidden sm:block w-px h-8 bg-secondary/20 self-center"></div>
    <select class="py-2 px-4 bg-transparent text-secondary focus:outline-none text-sm font-medium cursor-pointer appearance-none">
        <option>Tous les statuts</option>
        <option>Actif</option>
        <option>En compromis</option>
        <option>Vendu</option>
        <option>Brouillon</option>
    </select>
    <div class="hidden sm:block w-px h-8 bg-secondary/20 self-center"></div>
    <select class="py-2 px-4 bg-transparent text-secondary focus:outline-none text-sm font-medium cursor-pointer appearance-none">
        <option>Toutes les villes</option>
        <option>Paris</option>
        <option>Lyon</option>
        <option>Bordeaux</option>
    </select>
</div>

<div class="bg-background border border-secondary/20 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-secondary/20 bg-secondary/5">
                    <th class="py-4 px-6 text-xs font-semibold text-secondary uppercase tracking-wider">Bien & Localisation</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary uppercase tracking-wider">Prix</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary uppercase tracking-wider">Caractéristiques</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary uppercase tracking-wider">Statut</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary/10">

                <?php if (count($ads) > 0) : ?>
                    <?php foreach ($ads as $ad) : ?>
                    <tr class="hover:bg-secondary/5 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-12 rounded-lg bg-secondary/20 overflow-hidden flex-shrink-0">
                                    <img src="<?= $ad->images[0] ?>" alt="Thumb" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-medium text-primary text-sm"><?= $ad->title ?></div>
                                    <div class="text-xs text-secondary mt-0.5"><?= $ad->location['address'] ?>, <?= $ad->location['city'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-primary text-sm"><?= $ad->price->format_price() ?></div>
                            <div class="text-xs text-secondary mt-0.5">Réf: <?= strtoupper(substr($ad->location['city']->dangerousRaw(), 0, 3)) ?>-<?= $ad->id ?></div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-secondary"><?= $ad->features['area'] ?> m² • <?= $ad->features['bedrooms'] ?> Ch.</div>
                        </td>
                        <td class="py-4 px-6">
                            <?php
                            $statusClass = 'bg-secondary/10 text-secondary border-secondary/20';
                            $statusLabel = 'Brouillon';
                            $statusValue = $ad->status->dangerousRaw();
                            if ($statusValue == 'for_sale') {
                                $statusClass = 'bg-success/10 text-success border-success/20';
                                $statusLabel = 'Actif';
                            } elseif ($statusValue == 'compromise') {
                                $statusClass = 'bg-accent/10 text-accent border-accent/20';
                                $statusLabel = 'En compromis';
                            } elseif ($statusValue == 'sold') {
                                $statusClass = 'bg-secondary/10 text-secondary border-secondary/20';
                                $statusLabel = 'Vendu';
                            }
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                <?= $statusLabel ?>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= $this->route('edit-ad', ['id' => $ad->id]) ?>" class="w-8 h-8 rounded-lg flex items-center justify-center text-secondary hover:text-accent hover:bg-accent/10 transition-colors" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-secondary hover:text-primary hover:bg-secondary/20 transition-colors" title="Archiver">
                                    <i class="fa-solid fa-box-archive"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="py-8 px-6 text-center text-secondary italic">Aucune annonce trouvée.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-secondary/20 flex items-center justify-between">
        <span class="text-sm text-secondary">Affichage de <?= $pagination['from'] ?> à <?= $pagination['to'] ?> sur <?= $pagination['count'] ?> annonces</span>
        <div class="flex gap-1">
            <a href="<?= $this->route('ads', [], ['page' => $pagination['current']->dangerousRaw() - 1]) ?>" 
               class="w-8 h-8 rounded-lg border border-secondary/20 flex items-center justify-center text-secondary hover:bg-secondary/10 <?= $pagination['current']->dangerousRaw() <= 1 ? 'opacity-50 pointer-events-none' : '' ?>">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
            
            <?php for ($i = 1; $i <= $pagination['total']->dangerousRaw(); $i++) : ?>
                <a href="<?= $this->route('ads', [], ['page' => $i]) ?>" 
                   class="w-8 h-8 rounded-lg border <?= $i == $pagination['current']->dangerousRaw() ? 'border-accent bg-accent text-background' : 'border-secondary/20 text-secondary hover:bg-secondary/10' ?> flex items-center justify-center text-sm font-medium">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <a href="<?= $this->route('ads', [], ['page' => $pagination['current']->dangerousRaw() + 1]) ?>" 
               class="w-8 h-8 rounded-lg border border-secondary/20 flex items-center justify-center text-secondary hover:bg-secondary/10 <?= $pagination['current']->dangerousRaw() >= $pagination['total']->dangerousRaw() ? 'opacity-50 pointer-events-none' : '' ?>">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        </div>
    </div>
</div>

<?php include "layout/bo.footer.php"; ?>