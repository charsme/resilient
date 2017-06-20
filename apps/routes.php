<?php

namespace App\Controller;

$app->get('/[index.html]', Home::class);
$app->get('/{slug}-{id:\d+}.html', Detail::class);
