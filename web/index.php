<?php

require_once implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'Infrastructure', 'boot.php']);

$app = container()->get(\Slim\App::class);
$app->run();
