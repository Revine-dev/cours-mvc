<?php include "layout/bo.header.php"; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-semibold text-primary">Bonjour, <?= $this->auth("name"); ?> 👋</h1>
        <p class="text-sm text-secondary mt-1">Voici ce qui se passe dans votre agence aujourd'hui.</p>
    </div>
    <div class="flex gap-3 text-sm">
        <button class="px-4 py-2 bg-background border border-secondary/20 text-secondary rounded-xl hover:bg-secondary/5 transition-colors font-medium shadow-sm">
            <i class="fa-regular fa-calendar mr-2"></i> <?php
                $monthsFr = ['January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'];
                echo $monthsFr[date('F')] . ' ' . date('Y');
            ?>
        </button>
        <a href="<?= $this->route('create-ad'); ?>" class="bg-primary hover:bg-accent text-background px-5 py-2 rounded-xl font-medium transition-colors shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Nouveau mandat
        </a>
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
        <p class="text-sm text-secondary font-medium mb-1">Volume de ventes (Total)</p>
        <h3 class="text-2xl font-semibold text-primary"><?= $this->format_price($stats['totalValue']); ?></h3>
    </div>

    <div class="bg-background border border-secondary/20 p-5 rounded-2xl shadow-sm hover:border-secondary/40 transition-colors group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-background transition-colors">
                <i class="fa-solid fa-key"></i>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2 py-1 rounded-lg">
                <i class="fa-solid fa-arrow-trend-up"></i> +<?= $stats['totalAds']; ?>
            </span>
        </div>
        <p class="text-sm text-secondary font-medium mb-1">Mandats actifs</p>
        <h3 class="text-2xl font-semibold text-primary"><?= $stats['activeAds']; ?></h3>
    </div>

    <div class="bg-background border border-secondary/20 p-5 rounded-2xl shadow-sm hover:border-secondary/40 transition-colors group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center text-success group-hover:bg-success group-hover:text-background transition-colors">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <p class="text-sm text-secondary font-medium mb-1">Agents immobiliers</p>
        <h3 class="text-2xl font-semibold text-primary"><?= $stats['totalAgents']; ?> <span class="text-sm font-normal text-secondary">actifs</span></h3>
    </div>

    <div class="bg-background border border-secondary/20 p-5 rounded-2xl shadow-sm hover:border-secondary/40 transition-colors group">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-background transition-colors">
                <i class="fa-solid fa-house-circle-check"></i>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-secondary bg-secondary/10 px-2 py-1 rounded-lg">
                Total
            </span>
        </div>
        <p class="text-sm text-secondary font-medium mb-1">Total des annonces</p>
        <h3 class="text-2xl font-semibold text-primary"><?= $stats['totalAds']; ?></h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-background border border-secondary/20 rounded-2xl shadow-sm p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-primary">Nouveaux mandats par mois</h3>
            <select class="text-sm bg-transparent border-none text-secondary focus:ring-0 cursor-pointer outline-none">
                <option>Ces 6 derniers mois</option>
            </select>
        </div>

        <?php
        $maxCount = 0;
        foreach ($chartData as $data) {
            $val = $data['count']->value;
            $maxCount = max($maxCount, (int)$val);
        }
        $maxCount = max($maxCount, 1); // Avoid division by zero
        ?>

        <div class="flex-1 min-h-[250px] flex items-end gap-2 sm:gap-4 relative pt-6">
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                <div class="border-t border-secondary/10 w-full"></div>
                <div class="border-t border-secondary/10 w-full"></div>
                <div class="border-t border-secondary/10 w-full"></div>
                <div class="border-t border-secondary/10 w-full"></div>
                <div class="border-t border-secondary/10 w-full"></div>
            </div>

            <div class="w-full relative z-10 flex items-end justify-between h-full px-2">
                <?php foreach ($chartData as $data) : ?>
                    <?php
                        $countVal = $data['count']->value;
                        $height = ($countVal / $maxCount) * 100;

                        $isCurrentVal = $data['isCurrent']->value;

                        $colorClass = $isCurrentVal ? 'bg-accent shadow-[0_0_15px_rgba(0,126,167,0.3)]' : 'bg-secondary/20 hover:bg-accent/40';
                        $labelClass = $isCurrentVal ? 'font-medium text-primary' : 'text-secondary';
                    ?>
                    <div class="w-1/12 <?= $colorClass ?> rounded-t-sm transition-colors group relative" style="height: <?= max((float)$height, 5.0) ?>%;">
                        <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                            <?= $data['count'] ?>
                        </span>
                        <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs <?= $labelClass ?>">
                            <?= $data['label'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="bg-background border border-secondary/20 rounded-2xl shadow-sm p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-primary">Annonces récentes</h3>
            <a href="<?= $this->route('ads'); ?>" class="text-xs text-accent hover:underline">Voir tout</a>
        </div>

        <?php if (empty($latestAds)) : ?>
            <div class="flex-1 flex flex-col items-center justify-center text-center py-8">
                <div class="w-16 h-16 rounded-full bg-secondary/5 flex items-center justify-center mb-4 border border-secondary/10">
                    <i class="fa-solid fa-inbox text-2xl text-secondary/40"></i>
                </div>
                <p class="text-sm font-medium text-primary mb-1">Aucune annonce</p>
                <p class="text-xs text-secondary max-w-[220px] leading-relaxed">
                    Les dernières annonces publiées apparaîtront ici.
                </p>
            </div>
        <?php else : ?>
            <div class="space-y-4">
                <?php foreach ($latestAds as $ad) : ?>
                    <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-secondary/5 transition-colors group">
                        <div class="w-12 h-12 rounded-lg bg-secondary/10 overflow-hidden flex-shrink-0">
                            <?php $images = $ad->images; ?>
                            <?php if (!empty($images)) : ?>
                                <img src="<?= $images[0]; ?>" alt="" class="w-full h-full object-cover">
                            <?php else : ?>
                                <div class="w-full h-full flex items-center justify-center text-secondary/30">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-primary truncate"><?= $ad->title; ?></h4>
                            <p class="text-xs text-secondary truncate"><?= $this->translatePropertyType($ad->type); ?> • <?= $ad->location->city; ?> • <?= $this->format_price($ad->price); ?></p>
                        </div>
                        <a href="<?= $this->route('edit-ad', ['id' => $ad->id]); ?>" class="opacity-0 group-hover:opacity-100 p-2 text-secondary hover:text-accent transition-all">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "layout/bo.footer.php"; ?>