<footer class="border-t border-secondary/20 bg-background pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-16">
            <div class="col-span-2 lg:col-span-2 pr-8">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-5 h-5 bg-primary rounded-[4px] flex items-center justify-center">
                        <div class="w-1.5 h-1.5 bg-background rounded-full"></div>
                    </div>
                    <a href="<?= $this->route("home"); ?>" class="font-semibold text-base tracking-tight text-primary">Agence</a>
                </div>
                <p class="text-sm text-secondary leading-relaxed max-w-xs">
                    Le système d'exploitation moderne de l'immobilier de prestige en France. Découvrez, achetez et vendez avec une clarté absolue.
                </p>
            </div>

            <div>
                <h4 class="font-semibold text-sm text-primary mb-4">Plateforme</h4>
                <ul class="space-y-3 text-sm text-secondary">
                    <li><a href="<?= $this->route("properties"); ?>" class="hover:text-accent transition-colors">Recherche</a></li>
                    <li><a href="<?= $this->route("estimate"); ?>" class="hover:text-accent transition-colors">Estimer mon bien</a></li>
                    <li><a href="<?= $this->route("login"); ?>" class="hover:text-accent transition-colors">Connexion</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-sm text-primary mb-4">Entreprise</h4>
                <ul class="space-y-3 text-sm text-secondary">
                    <li><a href="#" class="hover:text-accent transition-colors">À propos</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Agences locales</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Carrières</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-sm text-primary mb-4">Légal</h4>
                <ul class="space-y-3 text-sm text-secondary">
                    <li><a href="#" class="hover:text-accent transition-colors">Confidentialité</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">CGV</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Cookies</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-secondary/20 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-secondary/70">
            <p>&copy; 2026 Agence Prestige SAS. Tous droits réservés.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-accent transition-colors"><i class="fa-brands fa-twitter text-sm"></i></a>
                <a href="#" class="hover:text-accent transition-colors"><i class="fa-brands fa-github text-sm"></i></a>
                <a href="#" class="hover:text-accent transition-colors"><i class="fa-brands fa-linkedin text-sm"></i></a>
            </div>
        </div>
    </div>
</footer>

</body>

</html>