<?php
// Sample rows matching the seed notifications until the notification list is built.
View::partial('notification-list', [
    'home'  => '/admin/dashboard',
    'items' => [],
]);
