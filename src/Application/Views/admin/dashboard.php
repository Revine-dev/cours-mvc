<?php include "layout/bo.header.php"; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-semibold text-primary dark:text-background transition-colors">Bonjour, <?= $this->auth("name"); ?> 👋</h1>
        <p class="text-sm text-secondary dark:text-background/60 mt-1 transition-colors">Voici ce qui se passe dans votre agence aujourd'hui.</p>
    </div>
    <div class="flex gap-3 text-sm">
        <button class="px-4 py-2 bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 text-secondary dark:text-background/60 rounded-xl hover:bg-secondary/5 dark:hover:bg-background/10 transition-all font-medium shadow-sm">
            <i class="fa-regular fa-calendar mr-2"></i> <?php
                $monthsFr = ['January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'];
                echo $monthsFr[date('F')] . ' ' . date('Y');
            ?>
        </button>
        <a href="<?= $this->route('create-ad'); ?>" class="bg-primary dark:bg-accent hover:bg-accent dark:hover:bg-accent/80 text-background px-5 py-2 rounded-xl font-medium transition-all shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Nouveau mandat
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 p-5 rounded-2xl shadow-sm hover:border-secondary/40 dark:hover:border-background/20 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-background transition-colors">
                <i class="fa-solid fa-euro-sign"></i>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2 py-1 rounded-lg">
                <i class="fa-solid fa-arrow-trend-up"></i> +12%
            </span>
        </div>
        <p class="text-sm text-secondary dark:text-background/60 font-medium mb-1 transition-colors">Volume de ventes (Total)</p>
        <h3 class="text-2xl font-semibold text-primary dark:text-background transition-colors"><?= $this->format_price($stats['totalValue']); ?></h3>
    </div>

    <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 p-5 rounded-2xl shadow-sm hover:border-secondary/40 dark:hover:border-background/20 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-primary/10 dark:bg-background/10 flex items-center justify-center text-primary dark:text-background group-hover:bg-primary dark:group-hover:bg-accent group-hover:text-background transition-colors">
                <i class="fa-solid fa-key"></i>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2 py-1 rounded-lg">
                <i class="fa-solid fa-arrow-trend-up"></i> +<?= $stats['totalAds']; ?>
            </span>
        </div>
        <p class="text-sm text-secondary dark:text-background/60 font-medium mb-1 transition-colors">Mandats actifs</p>
        <h3 class="text-2xl font-semibold text-primary dark:text-background transition-colors"><?= $stats['activeAds']; ?></h3>
    </div>

    <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 p-5 rounded-2xl shadow-sm hover:border-secondary/40 dark:hover:border-background/20 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center text-success group-hover:bg-success group-hover:text-background transition-colors">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <p class="text-sm text-secondary dark:text-background/60 font-medium mb-1 transition-colors">Agents immobiliers</p>
        <h3 class="text-2xl font-semibold text-primary dark:text-background transition-colors"><?= $stats['totalAgents']; ?> <span class="text-sm font-normal text-secondary dark:text-background/40">actifs</span></h3>
    </div>

    <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 p-5 rounded-2xl shadow-sm hover:border-secondary/40 dark:hover:border-background/20 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-secondary/10 dark:bg-background/10 flex items-center justify-center text-secondary dark:text-background group-hover:bg-secondary dark:group-hover:bg-background/20 group-hover:text-background transition-colors">
                <i class="fa-solid fa-house-circle-check"></i>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-secondary dark:text-background/60 bg-secondary/10 dark:bg-background/10 px-2 py-1 rounded-lg transition-colors">
                Total
            </span>
        </div>
        <p class="text-sm text-secondary dark:text-background/60 font-medium mb-1 transition-colors">Total des annonces</p>
        <h3 class="text-2xl font-semibold text-primary dark:text-background transition-colors"><?= $stats['totalAds']; ?></h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl shadow-sm p-6 flex flex-col transition-all">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-primary dark:text-background transition-colors">Nouveaux mandats par mois</h3>
            <select class="text-sm bg-transparent border-none text-secondary dark:text-background/60 focus:ring-0 cursor-pointer outline-none transition-colors">
                <option class="bg-background dark:bg-primary">Ces 6 derniers mois</option>
            </select>
        </div>

        <?php
        $maxCount = 0;
        foreach ($chartData as $data) {
            $val = is_object($data['count']) ? $data['count']->dangerousRaw() : $data['count'];
            $maxCount = max($maxCount, (int)$val);
        }
        $maxCount = max($maxCount, 1); // Avoid division by zero
        ?>

        <div class="flex-1 min-h-[250px] flex items-end gap-2 sm:gap-4 relative pt-6">
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                <div class="border-t border-secondary/10 dark:border-background/10 w-full"></div>
                <div class="border-t border-secondary/10 dark:border-background/10 w-full"></div>
                <div class="border-t border-secondary/10 dark:border-background/10 w-full"></div>
                <div class="border-t border-secondary/10 dark:border-background/10 w-full"></div>
                <div class="border-t border-secondary/10 dark:border-background/10 w-full"></div>
            </div>

            <div class="w-full relative z-10 flex items-end justify-between h-full px-2">
                <?php foreach ($chartData as $data) : ?>
                    <?php
                        $countVal = is_object($data['count']) ? $data['count']->dangerousRaw() : $data['count'];
                        $height = ($countVal / $maxCount) * 100;

                        $isCurrentVal = is_object($data['isCurrent']) ? $data['isCurrent']->dangerousRaw() : $data['isCurrent'];

                        $colorClass = $isCurrentVal ? 'bg-accent shadow-[0_0_15px_rgba(0,126,167,0.3)]' : 'bg-secondary/20 dark:bg-background/20 hover:bg-accent/40';
                        $labelClass = $isCurrentVal ? 'font-medium text-primary dark:text-background' : 'text-secondary dark:text-background/60';
                    ?>
                    <div class="w-1/12 <?= $colorClass ?> rounded-t-sm transition-colors group relative" style="height: <?= max((float)$height, 5.0) ?>%;">
                        <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-medium text-primary dark:text-background opacity-0 group-hover:opacity-100 transition-all">
                            <?= $data['count'] ?>
                        </span>
                        <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs <?= $labelClass ?> transition-colors">
                            <?= $data['label'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl shadow-sm p-6 flex flex-col transition-all">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-primary dark:text-background transition-colors">Annonces récentes</h3>
            <a href="<?= $this->route('ads'); ?>" class="text-xs text-accent hover:underline">Voir tout</a>
        </div>

        <?php if (empty($latestAds)) : ?>
            <div class="flex-1 flex flex-col items-center justify-center text-center py-8">
                <div class="w-16 h-16 rounded-full bg-secondary/5 dark:bg-background/5 flex items-center justify-center mb-4 border border-secondary/10 dark:border-background/10 transition-colors">
                    <i class="fa-solid fa-inbox text-2xl text-secondary/40 dark:text-background/20"></i>
                </div>
                <p class="text-sm font-medium text-primary dark:text-background mb-1 transition-colors">Aucune annonce</p>
                <p class="text-xs text-secondary dark:text-background/40 max-w-[220px] leading-relaxed transition-colors">
                    Les dernières annonces publiées apparaîtront ici.
                </p>
            </div>
        <?php else : ?>
            <div class="space-y-4">
                <?php foreach ($latestAds as $ad) : ?>
                    <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-secondary/5 dark:hover:bg-background/5 transition-all group">
                        <div class="w-12 h-12 rounded-lg bg-secondary/10 dark:bg-background/10 overflow-hidden flex-shrink-0 transition-colors">
                            <?php $images = $ad->images; ?>
                            <?php if (!empty($images)) : ?>
                                <img src="<?= $images[0]; ?>" alt="" class="w-full h-full object-cover">
                            <?php else : ?>
                                <div class="w-full h-full flex items-center justify-center text-secondary/30 dark:text-background/20 transition-colors">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-primary dark:text-background truncate transition-colors"><?= $ad->title; ?></h4>
                            <p class="text-xs text-secondary dark:text-background/60 truncate transition-colors"><?= $this->translatePropertyType($ad->type); ?> • <?= $ad->location->city; ?> • <?= $this->format_price($ad->price); ?></p>
                        </div>
                        <a href="<?= $this->route('edit-ad', ['id' => $ad->id]); ?>" class="opacity-0 group-hover:opacity-100 p-2 text-secondary dark:text-background/40 hover:text-accent dark:hover:text-accent transition-all">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "layout/bo.footer.php"; ?>