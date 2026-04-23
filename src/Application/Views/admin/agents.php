<?php include "layout/bo.header.php"; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-semibold text-primary dark:text-background transition-colors">Agents Immobiliers</h1>
        <p class="text-sm text-secondary dark:text-background/60 mt-1 transition-colors">Gérez vos agents et leurs informations de contact.</p>
    </div>
    <a href="<?= $this->route('create-agent') ?>" class="bg-accent hover:bg-primary dark:hover:bg-accent/80 text-background px-5 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nouvel Agent
    </a>
</div>

<div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl shadow-sm overflow-hidden transition-all">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-secondary/20 dark:border-background/10 bg-secondary/5 dark:bg-background/5 transition-colors">
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider">ID</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider">Nom</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider">Email</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider">Téléphone</th>
                    <th class="py-4 px-6 text-xs font-semibold text-secondary dark:text-background/60 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary/10 dark:divide-background/5">

                <?php if (count($agents) > 0) : ?>
                    <?php foreach ($agents as $agent) : ?>
                    <tr class="hover:bg-secondary/5 dark:hover:bg-background/5 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="text-sm text-secondary dark:text-background/40 transition-colors">#<?= $agent->id ?></div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-primary dark:text-background text-sm transition-colors"><?= $agent->name ?></div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-secondary dark:text-background/60 transition-colors"><?= $agent->email ?></div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-sm text-secondary dark:text-background/60 transition-colors"><?= $agent->phone ?></div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= $this->route('edit-agent', ['id' => $agent->id]) ?>" class="w-8 h-8 rounded-lg flex items-center justify-center text-secondary dark:text-background/40 hover:text-accent dark:hover:text-accent hover:bg-accent/10 dark:hover:bg-accent/20 transition-all" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="<?= $this->route('delete-agent', ['id' => $agent->id]) ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?');" class="inline">
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-secondary dark:text-background/40 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all" title="Supprimer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="py-8 px-6 text-center text-secondary dark:text-background/40 italic transition-colors">Aucun agent trouvé.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>

<?php include "layout/bo.footer.php"; ?>
