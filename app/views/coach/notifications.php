<?php
// Sample rows matching the seed notifications until the notification list is built.
View::partial('notification-list', [
    'home'  => '/coach/dashboard',
    'items' => [
        ['title' => 'New review', 'message' => 'You received a new 4-star review.', 'date' => relative_date(0), 'link' => '/coach/reviews', 'link_label' => 'View Reviews'],
    ],
]);
