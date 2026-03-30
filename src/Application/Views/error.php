<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $code; ?> Error - <?= $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="m-0 text-black bg-white font-sans h-screen flex items-center justify-center text-center dark:text-white dark:bg-black">
    <div class="flex items-center leading-[49px]">
        <h1 class="inline-block mr-5 pr-6 text-2xl font-medium align-top border-r border-black/30 dark:border-white/30">
            <?= $code; ?>
        </h1>
        <div class="inline-block text-left">
            <h2 class="text-sm font-normal m-0 p-0">
                <?= $message; ?>
            </h2>
        </div>
    </div>
</body>

</html>