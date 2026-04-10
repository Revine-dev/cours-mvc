<?php include "layout/bo.header.php"; ?>

<?php $isNewVal = is_object($isNew) ? $isNew->dangerousRaw() : $isNew; ?>

<div class="mb-8">
    <a href="<?= $this->route('agents') ?>" class="text-secondary hover:text-accent transition-colors flex items-center gap-2 text-sm font-medium mb-4">
        <i class="fa-solid fa-arrow-left"></i> Retour à la liste
    </a>
    <h1 class="text-2xl font-semibold text-primary"><?= $isNewVal ? 'Nouvel Agent' : 'Modifier l\'Agent' ?></h1>
    <p class="text-sm text-secondary mt-1"><?= $isNewVal ? 'Ajoutez un nouvel agent à votre équipe.' : 'Éditez les détails de l\'agent : ' . $agent->name ?></p>
</div>

<div class="max-w-2xl">
    <form action="<?= $isNewVal ? $this->route('store-agent') : $this->route('update-agent', ['id' => $agent->id]) ?>" method="POST" class="bg-background border border-secondary/20 rounded-2xl p-6 shadow-sm space-y-6">
        <h2 class="text-lg font-semibold text-primary mb-6">Informations de l'agent</h2>

        <div class="space-y-4">
            <?php if ($isNewVal) : ?>
            <div>
                <label class="block text-sm font-medium text-secondary mb-1.5">ID (Numérique)</label>
                <input type="number" name="id" value="<?= $agent->id ?>" required class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
            </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-secondary mb-1.5">Nom complet</label>
                <input type="text" name="name" value="<?= $agent->name ?>" required class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary mb-1.5">Adresse Email</label>
                <input type="email" name="email" value="<?= $agent->email ?>" required class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium text-secondary mb-1.5">Numéro de téléphone</label>
                <input type="text" name="phone" value="<?= $agent->phone ?>" class="w-full px-4 py-2.5 bg-background border border-secondary/20 rounded-xl text-primary focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none transition-all">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-accent hover:bg-primary text-background px-5 py-3 rounded-xl font-medium transition-colors shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-check"></i> <?= $isNewVal ? 'Créer l\'agent' : 'Enregistrer les modifications' ?>
            </button>
        </div>
    </form>
</div>

<?php include "layout/bo.footer.php"; ?>
