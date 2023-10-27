<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/public/css/bundle.min.css?v=<?= filemtime(BASE_PATH.'/public/css/bundle.min.css') ?>" />
    <script src="/public/js/bundle.min.js?v=<?= filemtime(BASE_PATH.'/public/js/bundle.min.js') ?>"></script>
    <title>Deployer</title>
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check" viewBox="0 0 16 16">
            <title>Check</title>
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
        </symbol>
    </svg>

    <div class="container-fluid py-3 px-5">
        <?php include 'header.php'; ?>
        <main>
            <?php include VIEWS_PATH . '/servers/servers.php'; ?>
        </main>
        <div id="loader-wrapper">
            <div class="loader-inner">
                <span class="loader"></span>
            </div>
        </div>
    </div>
</body>

</html>