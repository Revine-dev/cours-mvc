<?php include "layout/bo.header.php"; ?>

<div class="mb-8">
    <a href="<?= $this->route('agents') ?>" class="text-secondary dark:text-background/60 hover:text-accent dark:hover:text-accent transition-colors flex items-center gap-2 text-sm font-medium mb-4">
        <i class="fa-solid fa-arrow-left"></i> Retour à la liste
    </a>
    <h1 class="text-2xl font-semibold text-primary dark:text-background transition-colors"><?= $isNew ? 'Nouvel Agent' : 'Modifier l\'Agent' ?></h1>
    <p class="text-sm text-secondary dark:text-background/60 mt-1 transition-colors"><?= $isNew ? 'Ajoutez un nouvel agent à votre équipe.' : 'Éditez les détails de l\'agent : ' . $agent->name ?></p>
</div>

<div class="max-w-2xl">
    <form action="<?= $isNew ? $this->route('store-agent') : $this->route('update-agent', ['id' => $agent->id]) ?>" method="POST" class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 rounded-2xl p-6 shadow-sm space-y-6 transition-all">
        <h2 class="text-lg font-semibold text-primary dark:text-background mb-6 transition-colors">Informations de l'agent</h2>

        <div class="space-y-4">
            <?php if ($isNew) : ?>
            <div>
                <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">ID (Numérique)</label>
                <input type="number" name="id" value="<?= $agent->id ?>" required class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
            </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Nom complet</label>
                <input type="text" name="name" value="<?= $agent->name ?>" required class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Adresse Email</label>
                <input type="email" name="email" value="<?= $agent->email ?>" required class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary dark:text-background/60 mb-1.5 transition-colors">Numéro de téléphone</label>
                <input type="text" name="phone" value="<?= $agent->phone ?>" class="w-full px-4 py-2.5 bg-background dark:bg-primary/50 border border-secondary/20 dark:border-background/10 rounded-xl text-primary dark:text-background focus:border-accent dark:focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-accent hover:bg-primary dark:hover:bg-accent/80 text-background px-5 py-3 rounded-xl font-medium transition-all shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i> <?= $isNew ? 'Créer l\'agent' : 'Enregistrer les modifications' ?>
            </button>
        </div>
    </form>
</div>

<?php include "layout/bo.footer.php"; ?>
