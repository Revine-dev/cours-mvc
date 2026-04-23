<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agence | Immobilier d'Exception</title>
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

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .dark ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0f172a;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #f8fafc;
        }
    </style>
    <?php include VIEWS . "layout/tailwind.html"; ?>
</head>

<body class="bg-background text-primary dark:bg-primary dark:text-background antialiased selection:bg-accent selection:text-background transition-colors duration-300">

    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 dark:bg-primary/80 backdrop-blur-lg border-b border-secondary/10 dark:border-background/10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center">
                    <a href="<?= $this->route("home"); ?>" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-primary dark:bg-accent rounded-xl flex items-center justify-center group-hover:bg-accent dark:group-hover:bg-accent/80 transition-colors shadow-lg shadow-primary/10">
                            <span class="text-background font-bold text-xl">A</span>
                        </div>
                        <div class="flex flex-col leading-none">
                            <span class="font-bold text-xl tracking-tighter text-primary dark:text-background uppercase transition-colors">Agence</span>
                            <span class="text-[10px] text-accent font-semibold tracking-[0.2em] uppercase">Prestige</span>
                        </div>
                    </a>
                </div>

                <div class="hidden lg:flex items-center space-x-10 text-sm font-semibold text-primary/80 dark:text-background/80 uppercase tracking-widest transition-colors">
                    <a href="<?= $this->route("properties"); ?>" class="hover:text-accent dark:hover:text-accent transition-colors py-2 border-b-2 border-transparent hover:border-accent">Acheter</a>
                    <a href="<?= $this->route("estimate"); ?>" class="hover:text-accent dark:hover:text-accent transition-colors py-2 border-b-2 border-transparent hover:border-accent">Vendre</a>
                    <a href="<?= $this->route("home"); ?>" class="hover:text-accent dark:hover:text-accent transition-colors py-2 border-b-2 border-transparent hover:border-accent">L'Agence</a>
                </div>

                <div class="flex items-center gap-6">
                    <a href="<?= $this->route("estimate"); ?>" class="bg-primary dark:bg-accent hover:bg-accent dark:hover:bg-accent/80 text-background px-6 py-3 rounded-full text-sm font-bold transition-all shadow-md hover:shadow-xl active:scale-95">
                        Estimer mon bien
                    </a>
                    <button class="lg:hidden text-primary dark:text-background text-2xl transition-colors">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>