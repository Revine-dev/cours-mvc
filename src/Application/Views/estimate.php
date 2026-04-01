<?php include "layout/header.php"; ?>

<main class="pt-32 pb-24 px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="max-w-3xl mx-auto text-center mb-16">
        <h1 class="text-4xl md:text-6xl font-semibold tracking-tight text-primary mb-6">
            Estimez la valeur de votre bien en <span class="text-accent">quelques clics.</span>
        </h1>
        <p class="text-lg text-secondary leading-relaxed">
            Obtenez une première estimation basée sur les données réelles du marché. Pour une précision absolue, nos experts locaux sont à votre disposition.
        </p>
    </div>

    <div class="max-w-4xl mx-auto bg-white border border-secondary/20 rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">
        <!-- Sidebar Info -->
        <div class="md:w-1/3 bg-primary p-10 text-background">
            <h2 class="text-2xl font-semibold mb-8">Pourquoi Agence Prestige ?</h2>
            <ul class="space-y-6">
                <li class="flex gap-4">
                    <i class="fa-solid fa-bolt text-accent mt-1"></i>
                    <p class="text-sm text-background/80"><span class="font-bold text-background">Rapide.</span> Moins de 2 minutes pour remplir le formulaire.</p>
                </li>
                <li class="flex gap-4">
                    <i class="fa-solid fa-chart-simple text-accent mt-1"></i>
                    <p class="text-sm text-background/80"><span class="font-bold text-background">Précis.</span> Analyse comparative de milliers de biens.</p>
                </li>
                <li class="flex gap-4">
                    <i class="fa-solid fa-user-shield text-accent mt-1"></i>
                    <p class="text-sm text-background/80"><span class="font-bold text-background">Confidentialité.</span> Vos données sont protégées et non revendues.</p>
                </li>
            </ul>
            <div class="mt-12 pt-12 border-t border-background/10 italic text-sm text-background/60">
                "Estimation fiable et service très professionnel." - Client satisfait
            </div>
        </div>

        <!-- Formulaire -->
        <form class="md:w-2/3 p-10 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-bold text-primary uppercase tracking-wider mb-3">Type de bien</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="apartment" class="peer hidden" checked>
                            <div class="flex flex-col items-center justify-center p-4 border border-secondary/20 rounded-2xl peer-checked:border-accent peer-checked:bg-accent/5 transition-all hover:bg-background">
                                <i class="fa-solid fa-building text-2xl mb-2 text-secondary peer-checked:text-accent"></i>
                                <span class="text-sm font-medium">Appartement</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="house" class="peer hidden">
                            <div class="flex flex-col items-center justify-center p-4 border border-secondary/20 rounded-2xl peer-checked:border-accent peer-checked:bg-accent/5 transition-all hover:bg-background">
                                <i class="fa-solid fa-house text-2xl mb-2 text-secondary"></i>
                                <span class="text-sm font-medium">Maison</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-bold text-primary uppercase tracking-wider mb-3">Localisation</label>
                    <input type="text" placeholder="Adresse complète ou Code Postal"
                        class="w-full px-5 py-4 bg-background border border-secondary/20 rounded-2xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-primary uppercase tracking-wider mb-3">Surface (m²)</label>
                    <input type="number" placeholder="Ex: 85"
                        class="w-full px-5 py-4 bg-background border border-secondary/20 rounded-2xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-primary uppercase tracking-wider mb-3">Nombre de pièces</label>
                    <input type="number" placeholder="Ex: 4"
                        class="w-full px-5 py-4 bg-background border border-secondary/20 rounded-2xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-bold text-primary uppercase tracking-wider mb-3">Vos coordonnées</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" placeholder="Prénom Nom" class="px-5 py-4 bg-background border border-secondary/20 rounded-2xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                        <input type="email" placeholder="Email" class="px-5 py-4 bg-background border border-secondary/20 rounded-2xl text-primary focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-accent text-background rounded-2xl font-bold text-lg hover:bg-primary transition-all shadow-lg hover:shadow-2xl active:scale-[0.98]">
                Lancer l'estimation gratuite
            </button>
            <p class="text-[10px] text-secondary/60 text-center px-8 leading-relaxed">
                En cliquant sur "Lancer l'estimation", vous acceptez que vos données soient traitées par Agence Prestige pour vous fournir une estimation et vous recontacter.
            </p>
        </form>
    </div>
</main>

<?php include "layout/footer.php"; ?>