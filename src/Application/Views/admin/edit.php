<?php include "layout/bo.header.php"; ?>

<?php $isNewVal = is_object($isNew) ? $isNew->dangerousRaw() : $isNew; ?>

<div class="mb-8">
    <a href="<?= $this->route('ads') ?>" class="text-secondary hover:text-accent transition-colors flex items-center gap-2 text-sm font-medium mb-4">
        <i class="fa-solid fa-arrow-left"></i> Retour à la liste
    </a>
    <h1 class="text-2xl font-semibold text-primary"><?= $isNewVal ? 'Nouvelle annonce' : 'Modifier l\'annonce' ?></h1>
    <p class="text-sm text-secondary mt-1"><?= $isNewVal ? 'Créez un nouveau bien immobilier.' : 'Éditez les détails du bien : ' . $ad->title ?></p>
</div>

<form action="<?= $isNewVal ? $this->route('store-ad') : $this->route('update-ad', ['id' => $ad->id]) ?>" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-background border border-secondary/20 rounded-2xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-primary mb-6">Informations générales</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary mb-1.5">Titre de l'annonce</label>
                    <input type="text" name="title" value="<?= $ad->title ?>" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-1.5">Description</label>
                    <textarea name="description" rows="6" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all"><?= $ad->description ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-1.5">Prix (€)</label>
                        <input type="number" name="price" value="<?= $ad->price ?>" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-1.5">Type de bien</label>
                        <select name="type" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all appearance-none">
                            <option value="apartment" <?= $ad->type == 'apartment' ? 'selected' : '' ?>>Appartement</option>
                            <option value="house" <?= $ad->type == 'house' ? 'selected' : '' ?>>Maison</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-background border border-secondary/20 rounded-2xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-primary mb-6">Localisation</h2>
            <div class="space-y-4">
                <div class="relative" id="address-container">
                    <label class="block text-sm font-medium text-secondary mb-1.5">Adresse</label>
                    <input type="text" name="address" id="address-input" autocomplete="off" value="<?= $ad->location['address'] ?>" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
                    <div id="address-results" class="absolute z-50 w-full mt-1 bg-background border border-secondary/20 rounded-xl shadow-lg hidden overflow-hidden"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-1.5">Ville</label>
                        <input type="text" name="city" value="<?= $ad->location['city'] ?>" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-1.5">Code postal</label>
                        <input type="text" name="postal_code" value="<?= $ad->location['postal_code'] ?>" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-background border border-secondary/20 rounded-2xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-primary mb-6">Statut de l'annonce</h2>
            <div class="space-y-4">
                <select name="status" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all appearance-none">
                    <option value="for_sale" <?= $ad->status == 'for_sale' ? 'selected' : '' ?>>Actif (À vendre)</option>
                    <option value="compromise" <?= $ad->status == 'compromise' ? 'selected' : '' ?>>En compromis</option>
                    <option value="sold" <?= $ad->status == 'sold' ? 'selected' : '' ?>>Vendu</option>
                    <option value="draft" <?= $ad->status == 'draft' || empty($ad->status->dangerousRaw()) ? 'selected' : '' ?>>Brouillon</option>
                </select>

                <button type="submit" class="w-full bg-accent hover:bg-primary text-background px-5 py-3 rounded-xl font-medium transition-colors shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> <?= $isNewVal ? 'Créer l\'annonce' : 'Enregistrer les modifications' ?>
                </button>
                
                <?php if (!$isNewVal): ?>
                <a href="<?= $this->route('preview-ad', ['id' => $ad->id]) ?>" target="_blank" class="w-full bg-background border border-secondary/20 text-secondary hover:bg-secondary/5 px-5 py-3 rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-eye"></i> Aperçu de l'annonce
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-background border border-secondary/20 rounded-2xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-primary mb-4">Caractéristiques</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary mb-1.5">Surface (m²)</label>
                    <input type="number" name="features[area]" value="<?= $ad->features['area'] ?>" class="w-full px-4 py-2 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary mb-1.5">Chambres</label>
                    <input type="number" name="features[bedrooms]" value="<?= $ad->features['bedrooms'] ?>" class="w-full px-4 py-2 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary mb-1.5">Année de construction</label>
                    <input type="number" name="features[year_built]" value="<?= $ad->features['year_built'] ?>" class="w-full px-4 py-2 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent outline-none">
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('address-input');
        const results = document.getElementById('address-results');
        const cityInput = document.querySelector('input[name="city"]');
        const zipInput = document.querySelector('input[name="postal_code"]');

        input.addEventListener('input', async (e) => {
            const query = e.target.value;
            if (query.length < 3) {
                results.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&limit=5`);
                const data = await response.json();

                results.innerHTML = '';
                if (data.features.length > 0) {
                    data.features.forEach(feature => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 hover:bg-secondary/5 cursor-pointer text-sm text-primary border-b border-secondary/5 last:border-none';
                        div.innerHTML = `<i class="fa-solid fa-location-dot text-accent mr-2"></i> ${feature.properties.label}`;
                        div.onclick = () => {
                            input.value = feature.properties.name;
                            cityInput.value = feature.properties.city;
                            zipInput.value = feature.properties.postcode;
                            results.classList.add('hidden');
                        };
                        results.appendChild(div);
                    });
                    results.classList.remove('hidden');
                } else {
                    results.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error fetching addresses:', error);
            }
        });

        document.addEventListener('click', (e) => {
            if (!document.getElementById('address-container').contains(e.target)) {
                results.classList.add('hidden');
            }
        });
    });
</script>

<?php include "layout/bo.footer.php"; ?>