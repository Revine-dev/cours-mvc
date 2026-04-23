<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agence Prestige Admin | Gestion des Annonces</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom scrollbar using the palette */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .dark ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        /* Bright Snow */
        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        /* Blue Slate */
        ::-webkit-scrollbar-thumb:hover {
            background: #0f172a;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #f8fafc;
        }

        /* Prussian Blue */
    </style>
    <?php include VIEWS . "layout/tailwind.html"; ?>
</head>

<body class="bg-background text-primary dark:bg-primary dark:text-background antialiased selection:bg-accent selection:text-background flex h-screen overflow-hidden transition-colors duration-300">

    <aside class="w-64 bg-primary text-background flex flex-col transition-all duration-300 z-20 border-r border-background/10">
        <div class="h-16 flex items-center px-6 border-b border-background/10">
            <div class="flex items-center gap-2 cursor-pointer">
                <div class="w-6 h-6 bg-background rounded-md flex items-center justify-center">
                    <div class="w-2 h-2 bg-primary rounded-full"></div>
                </div>
                <span class="font-semibold text-lg tracking-tight">BO Agence Prestige</span>
            </div>
        </div>

        <?php
        $classActiveNav = fn(string ...$names) => (is_array($names)
            ? in_array($this->getCurrentRoute(), $names)
            : $this->getCurrentRoute() === $names
        )
            ? "bg-accent/20 text-accent hover:bg-accent/30"
            : "text-background/70 hover:bg-background/10 hover:text-background";
        ?>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-3">
                <li>
                    <a href="<?= $this->route("admin"); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= $classActiveNav("admin"); ?> transition-colors text-sm font-medium">
                        <i class="fa-solid fa-chart-pie w-5"></i> Vue d'ensemble
                    </a>
                </li>
                <li>
                    <a href="<?= $this->route("ads"); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= $classActiveNav('ads', 'create-ad', 'edit-ad'); ?> transition-colors text-sm font-medium">
                        <i class="fa-solid fa-building w-5"></i> Annonces
                    </a>
                </li>
                <li>
                    <a href="<?= $this->route("agents"); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= $classActiveNav('agents', 'create-agent', 'edit-agent'); ?> transition-colors text-sm font-medium">
                        <i class="fa-solid fa-users w-5"></i> Agents
                    </a>
                </li>
            </ul>

            <div class="mt-8 px-6 text-xs font-semibold text-background/40 uppercase tracking-wider mb-2">Configuration</div>
            <ul class="space-y-1 px-3">
                <li>
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-background/70 opacity-20 hover:cursor-not-allowed text-sm font-medium">
                        <i class="fa-solid fa-sliders w-5"></i> Paramètres (indisponible)
                    </div>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-background/10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center text-sm font-bold">
                    <?= $this->auth("initials"); ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate"><?= $this->auth("name"); ?></p>
                    <p class="text-xs text-background/50 truncate"><?= $this->auth("role"); ?></p>
                </div>
                <a href="<?= $this->route("logout"); ?>" class="text-background/50 hover:text-background transition-colors">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-background dark:bg-primary transition-colors duration-300">

        <header class="h-16 flex items-center justify-between px-8 border-b border-secondary/20 dark:border-background/10 bg-background dark:bg-primary z-10 transition-colors duration-300">
            <div class="flex items-center text-sm">
                <a href="<?= $this->route("admin"); ?>" class="text-secondary dark:text-background/60 hover:text-accent transition-colors">Dashboard</a>
                <?php if (!(string) $this->isActiveRoute("admin")) : ?>
                    <i class="fa-solid fa-chevron-right text-secondary/50 dark:text-background/30 mx-2 text-xs"></i>

                    <?php if (in_array($this->getCurrentRoute(), ['ads', 'create-ad', 'edit-ad'])) : ?>
                        <?php if ((string) $this->isActiveRoute("ads")) : ?>
                            <span class="font-medium text-primary dark:text-background">Gestion des Annonces</span>
                        <?php else : ?>
                            <a href="<?= $this->route("ads"); ?>" class="text-secondary dark:text-background/60 hover:text-accent transition-colors">Gestion des Annonces</a>
                        <?php endif; ?>
                        <?php if (in_array($this->getCurrentRoute(), ['create-ad', 'edit-ad'])) : ?>
                            <i class="fa-solid fa-chevron-right text-secondary/50 dark:text-background/30 mx-2 text-xs"></i>
                            <span class="font-medium text-primary dark:text-background"><?= $this->getCurrentRoute() === "create-ad" ? "Création d'une annonce" : "Modification d'une annonce"; ?></span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (in_array($this->getCurrentRoute(), ['agents', 'create-agent', 'edit-agent'])) : ?>
                        <?php if ((string) $this->isActiveRoute("agents")) : ?>
                            <span class="font-medium text-primary dark:text-background">Gestion des Agents</span>
                        <?php else : ?>
                            <a href="<?= $this->route("agents"); ?>" class="text-secondary dark:text-background/60 hover:text-accent transition-colors">Gestion des Agents</a>
                        <?php endif; ?>
                        <?php if (in_array($this->getCurrentRoute(), ['create-agent', 'edit-agent'])) : ?>
                            <i class="fa-solid fa-chevron-right text-secondary/50 dark:text-background/30 mx-2 text-xs"></i>
                            <span class="font-medium text-primary dark:text-background"><?= $this->getCurrentRoute() === "create-agent" ? "Ajouter un agent" : "Modifier un agent"; ?></span>
                        <?php endif; ?>
                    <?php endif; ?>

                <?php endif; ?>
            </div>

            <div class="flex items-center gap-4">
                <button id="theme-toggle" type="button" class="text-secondary dark:text-background/60 hover:bg-secondary/10 dark:hover:bg-background/10 focus:outline-none rounded-xl text-sm p-2.5 transition-all">
                    <i id="theme-toggle-dark-icon" class="hidden fa-solid fa-moon text-lg"></i>
                    <i id="theme-toggle-light-icon" class="hidden fa-solid fa-sun text-lg"></i>
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-auto p-8">