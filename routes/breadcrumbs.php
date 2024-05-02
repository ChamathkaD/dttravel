<?php

// routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.

// Users
Breadcrumbs::for('users.index', function (BreadcrumbTrail $trail) {
    $trail->push('User Manage', '');
    $trail->push(request()->trashed ? 'Trashed Users' : 'User List', route('users.index'));
});

// Profile
Breadcrumbs::for('profile.show', function (BreadcrumbTrail $trail) {
    $trail->push('Setting', '');
    $trail->push('Profile', route('profile.show'));
});

// Agent List
Breadcrumbs::for('agents.index', function (BreadcrumbTrail $trail) {
    $trail->push('Agent', '');
    $trail->push('Agent List', route('agents.index'));
});

Breadcrumbs::for('agents.show', function (BreadcrumbTrail $trail, $agent) {
    $trail->push('Agent', '');
    $trail->push('Agent Details', route('agents.show', $agent));
});

Breadcrumbs::for('agents.payment.history', function (BreadcrumbTrail $trail) {
    $trail->push('Payment', '');
    $trail->push('Payment History', route('agents.payment.history'));
});

Breadcrumbs::for('agents.payment.details', function (BreadcrumbTrail $trail, $agentPayment) {
    $trail->push('Payment', '');
    $trail->push('Payment Details', route('agents.payment.details', $agentPayment));
});

// Traveler List
Breadcrumbs::for('travelers.index', function (BreadcrumbTrail $trail) {
    $trail->push('Traveler', '');
    $trail->push(request()->withAgent ? 'Agent Travelers' : 'Traveler List', route('travelers.index'));
});

Breadcrumbs::for('travelers.show', function (BreadcrumbTrail $trail, $traveler) {
    $trail->push('Traveler', '');
    $trail->push('Traveler Details', route('travelers.show', $traveler));
});

// Bookings
Breadcrumbs::for('bookings.index', function (BreadcrumbTrail $trail) {
    $trail->push('Booking', '');
    $trail->push(request()->status ? ucfirst(request()->status).' Booking' : 'New Booking', route('bookings.index'));
});

Breadcrumbs::for('bookings.show', function (BreadcrumbTrail $trail, $booking) {
    $trail->push('Booking', '');
    $trail->push('Booking Details', route('bookings.show', $booking));
});

// Travel Category List
Breadcrumbs::for('travel-categories.index', function (BreadcrumbTrail $trail) {
    $trail->push('Package', '');
    $trail->push('Category List', route('travel-categories.index'));
});

// Travel Destination List
Breadcrumbs::for('travel-destinations.index', function (BreadcrumbTrail $trail) {
    $trail->push('Package', '');
    $trail->push('Destination List', route('travel-destinations.index'));
});

// Travel Packages
Breadcrumbs::for('travel-packages.index', function (BreadcrumbTrail $trail) {
    $trail->push('Package', '');
    $trail->push('Package List', route('travel-packages.index'));
});

Breadcrumbs::for('travel-packages.create', function (BreadcrumbTrail $trail) {
    $trail->push('Package', '');
    $trail->push('Add Package', route('travel-packages.create'));
});

Breadcrumbs::for('travel-packages.edit', function (BreadcrumbTrail $trail, $travelPackage) {
    $trail->push('Package', '');
    $trail->push('Edit Package', route('travel-packages.edit', $travelPackage));
});

// Contacts
Breadcrumbs::for('contacts.index', function (BreadcrumbTrail $trail) {
    $trail->push('Contacts', '');
    $trail->push('Contact List', route('contacts.index'));
});

Breadcrumbs::for('contacts.show', function (BreadcrumbTrail $trail, $contact) {
    $trail->push('Contact', '');
    $trail->push('Contact Details', route('contacts.show', $contact));
});
