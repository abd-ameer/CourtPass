<?php
// Sample rows matching the seed notifications until the notification list is built.
View::partial('notification-list', [
    'home'  => '/owner/dashboard',
    'items' => [
        ['title' => 'New review', 'message' => 'Colombo Sports Hub received a 5-star review.', 'date' => relative_date(0), 'link' => '/owner/reviews', 'link_label' => 'View Reviews'],
    ],
]);
