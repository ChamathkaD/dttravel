export default [
  {
    title: 'Dashboard',
    icon: { icon: 'tabler-dashboard' },
    path: route('dashboard'),
    href: route('dashboard'),
  },
  {
    title: 'Booking',
    icon: { icon: 'tabler-backpack' },
    children: [
      {
        title: 'Upcoming Bookings',
        path: route('bookings.index'),
        href: route('bookings.index'),
      },
      {
        title: 'Completed Bookings',
        path: route('bookings.index', { 'status': 'completed' }),
        href: route('bookings.index', { 'status': 'completed' }),
      },
      {
        title: 'Cancel Bookings',
        path: route('bookings.index', { 'status': 'cancel' }),
        href: route('bookings.index', { 'status': 'cancel' }),
      },
    ],
  },
  {
    title: 'Package',
    icon: { icon: 'tabler-discount-2' },
    children: [
      {
        title: 'Package List',
        path: route('travel-packages.index'),
        href: route('travel-packages.index'),
      },
      {
        title: 'Category',
        path: route('travel-categories.index'),
        href: route('travel-categories.index'),

      },
      {
        title: 'Destination',
        path: route('travel-destinations.index'),
        href: route('travel-destinations.index'),
      },
    ],
  },
  {
    title: 'Agent',
    icon: { icon: 'tabler-user-circle' },
    children: [
      {
        title: 'Agent List',
        path: route('agents.index'),
        href: route('agents.index'),
      },
      {
        title: 'Agent Payment',
        path: route('agents.payment.history'),
        href: route('agents.payment.history'),
      },
    ],
  },
  {
    title: 'Agent Payment',
    icon: { icon: 'tabler-user-circle' },
    path: route('agents.payment.history'),
    href: route('agents.payment.history'),
  },
  {
    title: 'Traveler',
    icon: { icon: 'tabler-truck' },
    children: [
      {
        title: 'Traveler List',
        path: route('travelers.index'),
        href: route('travelers.index'),
      },
      {
        title: 'Agent Traveler',
        path: route('travelers.index', { 'withAgent': true }),
        href: route('travelers.index', { 'withAgent': true }),
      },
    ],
  },
  {
    title: 'Traveler List',
    icon: { icon: 'tabler-truck' },
    path: route('travelers.index'),
    href: route('travelers.index'),
  },
  {
    title: 'Manage Users',
    icon: { icon: 'tabler-users' },
    children: [
      {
        title: 'Agent List',
        path: route('users.index', { 'roleName': 'agent' }),
        href: route('users.index', { 'roleName': 'agent' }),
      },
      {
        title: 'Staff List',
        path: route('users.index', { 'roleName': 'staff' }),
        href: route('users.index', { 'roleName': 'staff' }),
      },

      // {
      //   title: 'User',
      //   path: route('users.index'),
      //   href: route('users.index'),
      // },
      {
        title: 'Trashed Users',
        path: route('users.index', { 'trashed': true }),
        href: route('users.index', { 'trashed': true }),
      },
    ],
  },
  {
    title: 'Contact',
    icon: { icon: 'tabler-message-heart' },
    path: route('contacts.index'),
    href: route('contacts.index'),
  },

  // {
  //   title: 'Account Settings',
  //   icon: { icon: 'tabler-settings' },
  //   children: [
  //     {
  //       title: 'Edit Profile',
  //       to: 'demo-page',
  //     },
  //     {
  //       title: 'Business Profile',
  //       to: 'demo-page',
  //     },
  //     {
  //       title: 'Password Change',
  //       to: 'demo-page',
  //     },
  //   ],
  // },
]
