<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agence Prestige Admin | Accès Sécurisé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
    </style>
    <?php include VIEWS . "layout/tailwind.html"; ?>
</head>

<body class="bg-background text-primary dark:bg-primary dark:text-background antialiased selection:bg-accent selection:text-background min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden transition-colors duration-300">

    <div class="absolute -top-24 -left-24 w-96 h-96 bg-accent/5 dark:bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-primary/5 dark:bg-background/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="flex justify-center items-center gap-2 mb-8">
            <div class="w-8 h-8 bg-primary dark:bg-background rounded-lg flex items-center justify-center shadow-sm">
                <div class="w-2.5 h-2.5 bg-background dark:bg-primary rounded-full"></div>
            </div>
            <a href="<?= $this->route("home"); ?>" class="font-semibold text-2xl tracking-tight text-primary dark:text-background">Agence Prestige</a>
        </div>

        <div class="bg-background dark:bg-white/5 border border-secondary/20 dark:border-background/10 py-8 px-4 shadow-sm sm:rounded-3xl sm:px-10 relative overflow-hidden transition-all">

            <div class="absolute top-0 left-0 w-full h-1 bg-accent"></div>

            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-secondary/10 dark:bg-background/10 mb-4 transition-colors">
                    <i class="fa-solid fa-shield-halved text-primary dark:text-background text-xl"></i>
                </div>
                <h2 class="text-2xl font-semibold tracking-tight text-primary dark:text-background transition-colors">Portail d'Administration</h2>
                <p class="text-sm text-secondary dark:text-background/60 mt-2 transition-colors">Accès restreint. Veuillez vous identifier.</p>

                <?php if ($error) : ?>
                    <div class="mt-4 p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium flex items-center justify-center gap-2 transition-all">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>
            </div>

            <form class="space-y-6" action="" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium text-primary dark:text-background/80 mb-2 transition-colors">
                        Adresse professionnel
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-id-badge text-secondary/50 dark:text-background/30"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full pl-11 pr-4 py-3 bg-transparent border border-secondary/30 dark:border-background/20 rounded-xl text-primary dark:text-background placeholder-secondary/50 dark:placeholder-background/20 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm"
                            placeholder="prenom.nom@agence.fr">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-primary dark:text-background/80 transition-colors">
                            Mot de passe
                        </label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-secondary/50 dark:text-background/30 text-sm"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full pl-11 pr-4 py-3 bg-transparent border border-secondary/30 dark:border-background/20 rounded-xl text-primary dark:text-background placeholder-secondary/50 dark:placeholder-background/20 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                        class="h-4 w-4 rounded border-secondary/30 dark:border-background/20 text-accent focus:ring-accent bg-transparent cursor-pointer transition-all">
                    <label for="remember-me" class="ml-2 block text-sm text-secondary dark:text-background/60 cursor-pointer transition-colors">
                        Mémoriser cet appareil
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-background dark:text-background bg-primary dark:bg-accent hover:bg-accent dark:hover:bg-accent/80 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-accent focus:ring-offset-background dark:focus:ring-offset-primary gap-2 items-center">
                        <i class="fa-solid fa-right-to-bracket"></i> Connexion sécurisée
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>

</html>