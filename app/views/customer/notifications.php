<?php
// Sample rows matching the seed notifications until the notification list is built.
View::partial('notification-list', [
    'home'  => '/customer/dashboard',
    'items' => [
        ['title' => 'Booking confirmed', 'message' => 'Your Futsal Court A booking is confirmed.', 'date' => relative_date(0), 'link' => '/customer/bookings/3', 'link_label' => 'View Booking'],
    ],
]);
