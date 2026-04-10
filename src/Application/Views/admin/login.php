<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agence Prestige Admin | Accès Sécurisé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <?php include VIEWS . "layout/tailwind.html"; ?>
</head>

<body class="bg-background text-primary antialiased selection:bg-accent selection:text-background min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">

    <div class="absolute -top-24 -left-24 w-96 h-96 bg-accent/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <div class="flex justify-center items-center gap-2 mb-8">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-sm">
                <div class="w-2.5 h-2.5 bg-background rounded-full"></div>
            </div>
            <a href="<?= $this->route("home"); ?>" class="font-semibold text-2xl tracking-tight text-primary">Agence Prestige</a>
        </div>

        <div class="bg-background border border-secondary/20 py-8 px-4 shadow-sm sm:rounded-3xl sm:px-10 relative overflow-hidden">

            <div class="absolute top-0 left-0 w-full h-1 bg-accent"></div>

            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-secondary/10 mb-4">
                    <i class="fa-solid fa-shield-halved text-primary text-xl"></i>
                </div>
                <h2 class="text-2xl font-semibold tracking-tight text-primary">Portail d'Administration</h2>
                <p class="text-sm text-secondary mt-2">Accès restreint. Veuillez vous identifier.</p>

                <?php if ($error) : ?>
                    <div class="mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm font-medium flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>
            </div>

            <form class="space-y-6" action="" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium text-primary mb-2">
                        Adresse professionnel
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-id-badge text-secondary/50"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full pl-11 pr-4 py-3 bg-transparent border border-secondary/30 rounded-xl text-primary placeholder-secondary/50 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm"
                            placeholder="prenom.nom@agence.fr">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-primary">
                            Mot de passe
                        </label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-secondary/50 text-sm"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full pl-11 pr-4 py-3 bg-transparent border border-secondary/30 rounded-xl text-primary placeholder-secondary/50 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                        class="h-4 w-4 rounded border-secondary/30 text-accent focus:ring-accent bg-transparent cursor-pointer">
                    <label for="remember-me" class="ml-2 block text-sm text-secondary cursor-pointer">
                        Mémoriser cet appareil
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-background bg-primary hover:bg-accent transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background gap-2 items-center">
                        <i class="fa-solid fa-right-to-bracket"></i> Connexion sécurisée
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>

</html>