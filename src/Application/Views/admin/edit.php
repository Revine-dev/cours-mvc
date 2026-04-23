<?php include "layout/bo.header.php"; ?>

<div class="mb-8">
    <a href="<?= $this->route('ads') ?>" class="text-secondary dark:text-background/60 hover:text-accent dark:hover:text-accent transition-colors flex items-center gap-2 text-sm font-medium mb-4">
        <i class="fa-solid fa-arrow-left"></i> Retour à la liste
    </a>
    <h1 class="text-2xl font-semibold text-primary dark:text-background transition-colors"><?= $isNew ? 'Nouvelle annonce' : 'Modifier l\'annonce' ?></h1>
    <p class="text-sm text-secondary dark:text-background/60 mt-1 transition-colors"><?= $isNew ? 'Créez un nouveau bien immobilier.' : 'Éditez les détails du bien : ' . $ad->title ?></p>
</div>

<form action="<?= $isNew ? $this->route('store-ad') : $this->route('update-ad', ['id' => $ad->id]) ?>" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl p-6 shadow-sm transition-all">
            <h2 class="text-lg font-semibold text-primary dark:text-background mb-6 transition-colors">Informations générales</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Titre de l'annonce</label>
                    <input type="text" id="title-input" name="title" value="<?= $ad->title ?>" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
                </div>

                <div>
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Lien de l'annonce (Slug)</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-xl bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 focus-within:ring-2 focus-within:ring-accent/10 focus-within:border-accent transition-all overflow-hidden">
                                <div class="flex shrink-0 items-center bg-secondary/5 dark:bg-background/5 px-3 py-2.5 text-sm text-secondary dark:text-background/40 border-r border-secondary/20 dark:border-background/10 transition-colors" id="city-slug-prefix">/vente/<?= !empty((string) $ad->location->city) ? strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', (string) $ad->location->city))) : '...' ?>/</div>
                                <input id="slug-input" name="slug" value="<?= $ad->slug ?>" type="text" placeholder="maison-centre-ville" class="block w-full grow bg-transparent px-3 py-2.5 text-sm text-primary dark:text-background placeholder:text-secondary/40 dark:placeholder:text-background/20 outline-none transition-colors" />
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Description</label>
                    <textarea name="description" rows="6" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all"><?= $ad->description ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Prix (€)</label>
                        <input type="text" id="price-display" value="<?= $ad->price ?>" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all" placeholder="0">
                        <input type="hidden" name="price" id="price-real" value="<?= $ad->price ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Type de bien</label>
                        <select name="type" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all appearance-none">
                            <option value="apartment" <?= $ad->type == 'apartment' ? 'selected' : '' ?> class="bg-background dark:bg-primary">Appartement</option>
                            <option value="house" <?= $ad->type == 'house' ? 'selected' : '' ?> class="bg-background dark:bg-primary">Maison</option>
                            <option value="loft" <?= $ad->type == 'loft' ? 'selected' : '' ?> class="bg-background dark:bg-primary">Loft</option>
                            <option value="building" <?= $ad->type == 'building' ? 'selected' : '' ?> class="bg-background dark:bg-primary">Immeuble</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl p-6 shadow-sm transition-all">
            <h2 class="text-lg font-semibold text-primary dark:text-background mb-6 transition-colors">Localisation</h2>
            <div class="space-y-4">
                <div class="relative" id="address-container">
                    <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Adresse</label>
                    <input type="text" name="address" id="address-input" autocomplete="off" value="<?= $ad->location->address ?>" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
                    <div id="address-results" class="absolute z-50 w-full mt-1 bg-background dark:bg-primary border border-secondary/20 dark:border-background/10 rounded-xl shadow-lg hidden overflow-hidden transition-all"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Ville</label>
                        <input type="text" name="city" readonly value="<?= $ad->location->city ?>" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background/40 focus:border-accent outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Code postal</label>
                        <input type="text" name="postal_code" readonly value="<?= $ad->location->postal_code ?>" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background/40 focus:border-accent outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl p-6 shadow-sm transition-all">
            <h2 class="text-lg font-semibold text-primary dark:text-background mb-6 transition-colors">Images de l'annonce</h2>
            <div id="images-container" class="space-y-3 mb-4">
                <?php foreach ($ad->images as $img) : ?>
                    <div class="flex gap-2 image-row">
                        <input type="text" name="images[]" value="<?= $img ?>" class="flex-1 px-4 py-2 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent outline-none text-sm transition-all" placeholder="URL de l'image">
                        <button type="button" onclick="this.parentElement.remove()" class="w-10 h-10 flex items-center justify-center text-secondary dark:text-background/40 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all">
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-image" class="w-full py-2.5 border-2 border-dashed border-secondary/20 dark:border-background/10 rounded-xl text-secondary dark:text-background/40 hover:text-accent hover:border-accent/50 hover:bg-accent/5 dark:hover:bg-background/5 transition-all text-sm font-medium flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus"></i> Ajouter une image (URL)
            </button>
        </div>

        <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl p-6 shadow-sm transition-all">
            <h2 class="text-lg font-semibold text-primary dark:text-background mb-6 transition-colors">Statut de l'annonce</h2>
            <div class="space-y-4">
                <select name="status" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all appearance-none">
                    <option value="for_sale" <?= $ad->status == 'for_sale' ? 'selected' : '' ?> class="bg-background dark:bg-primary">Actif (À vendre)</option>
                    <option value="compromise" <?= $ad->status == 'compromise' ? 'selected' : '' ?> class="bg-background dark:bg-primary">En compromis</option>
                    <option value="sold" <?= $ad->status == 'sold' ? 'selected' : '' ?> class="bg-background dark:bg-primary">Vendu</option>
                    <option value="draft" <?= (string)$ad->status == 'draft' || !(string)$ad->status ? 'selected' : '' ?> class="bg-background dark:bg-primary">Brouillon</option>
                </select>

                <button type="submit" class="w-full bg-accent hover:bg-primary dark:hover:bg-accent/80 text-background px-5 py-3 rounded-xl font-medium transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> <?= $isNew ? 'Créer l\'annonce' : 'Enregistrer les modifications' ?>
                </button>

                <button type="submit" formaction="<?= $this->route('preview-ad', ['id' => $ad->id]) ?>" formtarget="_blank" class="w-full bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 text-secondary dark:text-background/60 hover:bg-secondary/5 dark:hover:bg-background/10 px-5 py-3 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-eye"></i> Aperçu de l'annonce
                </button>
            </div>
        </div>

        <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl p-6 shadow-sm transition-all">
            <h2 class="text-lg font-semibold text-primary dark:text-background mb-6 transition-colors">Agent immobilier</h2>
            <div class="space-y-4">
                <select name="agent_id" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all appearance-none">
                    <option value="" class="bg-background dark:bg-primary">-- Sélectionner un agent --</option>
                    <?php foreach ($agents as $agent) : ?>
                        <option value="<?= $agent->id ?>" <?= $ad->agent && $ad->agent->id == $agent->id ? 'selected' : '' ?> class="bg-background dark:bg-primary">
                            <?= $agent->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl p-6 shadow-sm transition-all">
            <h2 class="text-lg font-semibold text-primary dark:text-background mb-4 transition-colors">Caractéristiques</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Surface (m²)</label>
                    <input type="number" name="features[area]" value="<?= $ad->features->area ?>" class="w-full px-4 py-2 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Chambres</label>
                    <input type="number" name="features[bedrooms]" value="<?= $ad->features->bedrooms ?>" class="w-full px-4 py-2 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Année de construction</label>
                    <input type="number" name="features[year_built]" value="<?= $ad->features->year_built ?>" class="w-full px-4 py-2 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent outline-none transition-all">
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
        const cityPrefix = document.getElementById('city-slug-prefix');

        const slugify = (text) => {
            return text.toString().toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        };

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
                        div.className = 'px-4 py-3 hover:bg-secondary/5 dark:hover:bg-background/5 cursor-pointer text-sm text-primary dark:text-background border-b border-secondary/5 dark:border-background/5 last:border-none transition-colors';
                        div.innerHTML = `<i class="fa-solid fa-location-dot text-accent mr-2"></i> ${feature.properties.label}`;
                        div.onclick = () => {
                            input.value = feature.properties.name;
                            cityInput.value = feature.properties.city;
                            zipInput.value = feature.properties.postcode;
                            if (cityPrefix) {
                                cityPrefix.textContent = `/vente/${slugify(feature.properties.city)}/`;
                            }
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

        // Price formatting logic
        const priceDisplay = document.getElementById('price-display');
        const priceReal = document.getElementById('price-real');

        if (priceDisplay && priceReal) {
            const formatPrice = (val) => {
                return val.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            };

            if (priceDisplay.value) {
                priceDisplay.value = formatPrice(priceDisplay.value);
            }

            priceDisplay.addEventListener('input', (e) => {
                const rawValue = e.target.value.replace(/\D/g, '');
                priceReal.value = rawValue;
                e.target.value = formatPrice(rawValue);
            });
        }

        // Auto-slug logic
        const titleInput = document.getElementById('title-input');
        const slugInput = document.getElementById('slug-input');

        if (titleInput && slugInput) {
            titleInput.addEventListener('input', (e) => {
                // Only update slug automatically if it was empty or matches the old title's slug
                slugInput.value = slugify(e.target.value);
            });
        }

        // Image adding logic
        const addImageBtn = document.getElementById('add-image');
        const imagesContainer = document.getElementById('images-container');

        if (addImageBtn && imagesContainer) {
            addImageBtn.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'flex gap-2 image-row';
                div.innerHTML = `
                    <input type="text" name="images[]" class="flex-1 px-4 py-2 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent outline-none text-sm transition-all" placeholder="URL de l'image">
                    <button type="button" onclick="this.parentElement.remove()" class="w-10 h-10 flex items-center justify-center text-secondary dark:text-background/40 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                `;
                imagesContainer.appendChild(div);
                div.querySelector('input').focus();
            });
        }

        document.addEventListener('click', (e) => {
            if (!document.getElementById('address-container').contains(e.target)) {
                results.classList.add('hidden');
            }
        });
    });
</script>

<?php include "layout/bo.footer.php"; ?>