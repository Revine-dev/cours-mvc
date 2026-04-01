<?php include "layout/bo.header.php"; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-semibold text-primary">Bonjour, <?= $this->auth("name"); ?> 👋</h1>
        <p class="text-sm text-secondary mt-1">Voici ce qui se passe dans votre agence aujourd'hui.</p>
    </div>
    <div class="flex gap-3 text-sm">
        <button class="px-4 py-2 bg-background border border-secondary/20 text-secondary rounded-xl hover:bg-secondary/5 transition-colors font-medium shadow-sm">
            <i class="fa-regular fa-calendar mr-2"></i> Avril 2026
        </button>
        <button class="bg-primary hover:bg-accent text-background px-5 py-2 rounded-xl font-medium transition-colors shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Nouveau mandat
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-background border border-secondary/20 p-5 rounded-2xl shadow-sm hover:border-secondary/40 transition-colors group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-background transition-colors">
                <i class="fa-solid fa-euro-sign"></i>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2 py-1 rounded-lg">
                <i class="fa-solid fa-arrow-trend-up"></i> +12%
            </span>
        </div>
        <p class="text-sm text-secondary font-medium mb-1">Volume de ventes (Mensuel)</p>
        <h3 class="text-2xl font-semibold text-primary">4.2M €</h3>
    </div>

    <div class="bg-background border border-secondary/20 p-5 rounded-2xl shadow-sm hover:border-secondary/40 transition-colors group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-background transition-colors">
                <i class="fa-solid fa-key"></i>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2 py-1 rounded-lg">
                <i class="fa-solid fa-arrow-trend-up"></i> +3
            </span>
        </div>
        <p class="text-sm text-secondary font-medium mb-1">Mandats actifs</p>
        <h3 class="text-2xl font-semibold text-primary">45</h3>
    </div>

    <div class="bg-background border border-secondary/20 p-5 rounded-2xl shadow-sm hover:border-secondary/40 transition-colors group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center text-success group-hover:bg-success group-hover:text-background transition-colors">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
        </div>
        <p class="text-sm text-secondary font-medium mb-1">Visites programmées</p>
        <h3 class="text-2xl font-semibold text-primary">12 <span class="text-sm font-normal text-secondary">cette semaine</span></h3>
    </div>

    <div class="bg-background border border-secondary/20 p-5 rounded-2xl shadow-sm hover:border-secondary/40 transition-colors group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-background transition-colors">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-secondary bg-secondary/10 px-2 py-1 rounded-lg">
                Stable
            </span>
        </div>
        <p class="text-sm text-secondary font-medium mb-1">Nouveaux prospects</p>
        <h3 class="text-2xl font-semibold text-primary">28</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-background border border-secondary/20 rounded-2xl shadow-sm p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-primary">Évolution des signatures</h3>
            <select class="text-sm bg-transparent border-none text-secondary focus:ring-0 cursor-pointer outline-none">
                <option>Ces 6 derniers mois</option>
                <option>Cette année</option>
            </select>
        </div>

        <div class="flex-1 min-h-[250px] flex items-end gap-2 sm:gap-4 relative pt-6">
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                <div class="border-t border-secondary/10 w-full"></div>
                <div class="border-t border-secondary/10 w-full"></div>
                <div class="border-t border-secondary/10 w-full"></div>
                <div class="border-t border-secondary/10 w-full"></div>
                <div class="border-t border-secondary/10 w-full"></div>
            </div>

            <div class="w-full relative z-10 flex items-end justify-between h-full px-2">
                <div class="w-1/12 bg-secondary/20 hover:bg-accent/40 rounded-t-sm transition-colors h-[40%] group relative"><span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-secondary">Nov</span></div>
                <div class="w-1/12 bg-secondary/20 hover:bg-accent/40 rounded-t-sm transition-colors h-[65%] group relative"><span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-secondary">Déc</span></div>
                <div class="w-1/12 bg-secondary/20 hover:bg-accent/40 rounded-t-sm transition-colors h-[45%] group relative"><span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-secondary">Jan</span></div>
                <div class="w-1/12 bg-secondary/20 hover:bg-accent/40 rounded-t-sm transition-colors h-[80%] group relative"><span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-secondary">Fév</span></div>
                <div class="w-1/12 bg-accent rounded-t-sm transition-colors h-[95%] shadow-[0_0_15px_rgba(0,126,167,0.3)] group relative"><span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs font-medium text-primary">Mar</span></div>
                <div class="w-1/12 bg-success rounded-t-sm transition-colors h-[30%] group relative"><span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs font-medium text-success">Avr</span></div>
            </div>
        </div>
    </div>

    <div class="bg-background border border-secondary/20 rounded-2xl shadow-sm p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-primary">Activité récente</h3>
        </div>

        <div class="flex-1 flex flex-col items-center justify-center text-center py-8">
            <div class="w-16 h-16 rounded-full bg-secondary/5 flex items-center justify-center mb-4 border border-secondary/10">
                <i class="fa-solid fa-inbox text-2xl text-secondary/40"></i>
            </div>
            <p class="text-sm font-medium text-primary mb-1">Aucune activité récente</p>
            <p class="text-xs text-secondary max-w-[220px] leading-relaxed">
                Les notifications concernant vos mandats, offres et visites programmées apparaîtront ici.
            </p>
        </div>
    </div>

</div>

<?php include "layout/bo.footer.php"; ?>