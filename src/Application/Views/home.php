<?php include "layout/header.php"; ?>

<section class="pt-40 pb-24 px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="max-w-4xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary/10 border border-secondary/20 text-xs font-medium text-secondary mb-8">
            <span class="flex h-2 w-2 rounded-full bg-success"></span>
            Présent à Paris, Lyon, Bordeaux et sur la Côte d'Azur
        </div>
        <h1 class="text-6xl md:text-8xl font-semibold tracking-tighter text-primary leading-[1.05] mb-6">
            L'immobilier,<br>
            <span class="text-accent">réinventé.</span>
        </h1>
        <p class="text-lg md:text-xl text-secondary mb-10 max-w-2xl leading-relaxed font-light">
            Une plateforme sélective pour des biens exceptionnels. Nous éliminons le superflu pour que vous trouviez exactement ce que vous cherchez, avec une précision absolue.
        </p>

        <form action="<?= $this->route('properties') ?>" method="GET" class="flex flex-col sm:flex-row items-center gap-3 p-2 bg-background border border-secondary/30 rounded-2xl shadow-sm max-w-3xl focus-within:ring-2 focus-within:ring-accent focus-within:border-accent transition-all">
            <div class="flex-1 flex items-center px-4 w-full">
                <i class="fa-solid fa-magnifying-glass text-secondary mr-3"></i>
                <input type="text" name="city" placeholder="Rechercher une ville, un quartier..." class="w-full py-3 bg-transparent text-primary placeholder-secondary/70 focus:outline-none text-base">
            </div>
            <div class="hidden sm:block w-px h-8 bg-secondary/20"></div>
            <select name="type" class="w-full sm:w-auto px-4 py-3 bg-transparent text-secondary focus:outline-none cursor-pointer appearance-none font-medium">
                <option value="">Type de bien</option>
                <option value="apartment">Appartement</option>
                <option value="house">Maison</option>
                <option value="loft">Loft</option>
                <option value="building">Immeuble</option>
            </select>
            <button type="submit" class="w-full sm:w-auto bg-primary text-background px-6 py-3 rounded-xl font-medium hover:bg-accent transition-colors">
                Rechercher
            </button>
        </form>
        <div class="mt-4 text-sm text-secondary flex gap-4">
            <span>Tendances :</span>
            <a href="<?= $this->route('properties', [], ['city' => 'Paris']) ?>" class="text-accent hover:text-primary transition-colors underline decoration-secondary/30 underline-offset-4">Paris</a>
            <a href="<?= $this->route('properties', [], ['city' => 'Lyon']) ?>" class="text-accent hover:text-primary transition-colors underline decoration-secondary/30 underline-offset-4">Lyon</a>
            <a href="<?= $this->route('properties', [], ['city' => 'Bordeaux']) ?>" class="text-accent hover:text-primary transition-colors underline decoration-secondary/30 underline-offset-4">Bordeaux</a>
        </div>
    </div>
</section>

<section id="properties" class="py-24 bg-background border-y border-secondary/20">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-semibold tracking-tight text-primary mb-2">Annonces Récentes</h2>
                <p class="text-secondary">Mises à jour en temps réel depuis notre réseau national vérifié.</p>
            </div>
            <a href="<?= $this->route("properties"); ?>" class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-accent hover:text-primary transition-colors">
                Voir le catalogue <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($properties as $property) : ?>
                <a href="<?= $this->route('view-property', ['city' => $this->slugify($property->location->city), 'slug' => $property->slug]); ?>" class="bg-background rounded-2xl border border-secondary/20 overflow-hidden hover:border-accent hover:shadow-md transition-all group cursor-pointer flex flex-col">
                    <div class="aspect-[4/3] relative overflow-hidden bg-secondary/10">
                        <img src="<?= $property->images[0]; ?>" alt="<?= $property->title; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out">
                        <?php if ((string) $property->status === 'compromise') : ?>
                            <div class="absolute top-3 right-3 bg-success px-2.5 py-1 rounded-md text-[10px] font-bold text-background uppercase tracking-widest shadow-sm">
                                Sous compromis
                            </div>
                        <?php endif; ?>
                        <?php if ($property->id === 1) : ?>
                            <div class="absolute top-3 left-3 bg-success px-2.5 py-1 rounded-md text-xs font-semibold text-background shadow-sm">
                                Nouveau
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold text-lg text-primary">
                                    <?= $property->price->format_price($property->currency) ?>
                                </h3>
                                <p class="text-secondary text-sm mt-1"><?= $property->location->address; ?>, <?= $property->location->city; ?></p>
                            </div>
                        </div>
                        <div class="mt-auto pt-4 border-t border-secondary/20 flex gap-4 text-sm text-secondary">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-bed text-accent text-xs"></i> <?= $property->features->bedrooms; ?> Ch.</span>
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-bath text-accent text-xs"></i> <?= $property->features->bathrooms; ?> S.d.B</span>
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-vector-square text-accent text-xs"></i> <?= $property->features->area; ?> m²</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="mt-8 text-center sm:hidden">
            <a href="<?= $this->route("properties"); ?>" class="inline-block w-full py-3 bg-background border border-secondary/30 rounded-xl text-sm font-medium text-primary">Voir toutes les annonces</a>
        </div>
    </div>
</section>

<section id="about" class="py-24 max-w-7xl mx-auto px-6 lg:px-8">
    <div class="mb-12 max-w-2xl">
        <h2 class="text-3xl font-semibold tracking-tight text-primary mb-4">Conçu pour l'acquéreur moderne.</h2>
        <p class="text-secondary text-lg">Nous avons repensé l'expérience immobilière de A à Z, en offrant des données, de la transparence et une rapidité jusqu'ici inaccessibles.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-primary rounded-3xl p-8 md:p-12 flex flex-col justify-between overflow-hidden relative group">
            <div class="relative z-10 max-w-md">
                <div class="w-12 h-12 bg-background/10 rounded-xl flex items-center justify-center mb-6 backdrop-blur-md border border-background/20">
                    <i class="fa-solid fa-chart-line text-accent"></i>
                </div>
                <h3 class="text-2xl font-semibold text-background mb-3">Données de niveau institutionnel</h3>
                <p class="text-background/70">Accédez aux historiques de prix, aux métriques de valorisation des quartiers et aux biens off-market directement depuis votre tableau de bord.</p>
            </div>
            <div class="absolute right-0 bottom-0 w-64 h-64 bg-gradient-to-br from-background/5 to-transparent rounded-tl-full opacity-50 group-hover:scale-110 transition-transform duration-700"></div>
        </div>

        <div class="bg-background border border-secondary/20 rounded-3xl p-8 flex flex-col justify-between">
            <div>
                <h3 class="text-4xl font-semibold tracking-tighter text-accent mb-2">24/7</h3>
                <p class="text-secondary font-medium">Service Conciergerie</p>
            </div>
            <p class="text-sm text-secondary mt-8">Planification instantanée, chat direct avec l'agent local et signature électronique de vos offres.</p>
        </div>

        <div class="bg-background border border-secondary/20 rounded-3xl p-8 flex flex-col justify-between shadow-sm">
            <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center mb-6">
                <i class="fa-solid fa-shield-halved text-success"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-primary mb-2">Biens Vérifiés</h3>
                <p class="text-sm text-secondary">Chaque propriété est inspectée physiquement par nos équipes régionales avant d'être publiée.</p>
            </div>
        </div>

        <div class="md:col-span-2 bg-background border border-secondary/20 rounded-3xl p-8 flex flex-col sm:flex-row items-center justify-between gap-8">
            <div>
                <h3 class="text-xl font-semibold text-primary mb-2">Prêt à vendre votre bien ?</h3>
                <p class="text-secondary">Touchez des milliers d'acquéreurs qualifiés dans toute la France grâce à notre algorithme.</p>
            </div>
            <button class="w-full sm:w-auto px-6 py-3 bg-accent border border-accent rounded-xl font-medium text-background hover:bg-primary hover:border-primary transition-colors whitespace-nowrap shadow-sm">
                Voir les options vendeur
            </button>
        </div>
    </div>
</section>

<?php include "layout/footer.php"; ?>