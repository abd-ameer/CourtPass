/**
 * CourtPass Mock Data Engine
 * Authentic Sri Lankan sports venue booking, coach and community data
 */

const CourtPassData = {
    sports: [
        { id: 'badminton', name: 'Badminton', icon: '🏸', venuesCount: 14 },
        { id: 'futsal', name: 'Futsal', icon: '⚽', venuesCount: 9 },
        { id: 'pickleball', name: 'Pickleball', icon: '🏓', venuesCount: 6 },
        { id: 'squash', name: 'Squash', icon: '🎾', venuesCount: 5 },
        { id: 'billiards', name: 'Billiards', icon: '🎱', venuesCount: 7 },
        { id: 'carrom', name: 'Carrom', icon: '🎯', venuesCount: 8 },
        { id: 'table-tennis', name: 'Table Tennis', icon: '🏓', venuesCount: 11 }
    ],

    venues: [
        {
            id: 'venue-1',
            name: 'Colombo Futsal Club',
            slug: 'colombo-futsal-club',
            tagline: 'Premier FIFA-standard synthetic turf by the coast',
            address: 'No. 42 Marine Drive, Dehiwala, Colombo',
            city: 'Dehiwala',
            sports: ['futsal', 'badminton'],
            rating: 4.8,
            reviewCount: 124,
            startingPrice: 4500,
            image: 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80',
            owner: 'Nuwan Senanayake',
            phone: '+94 11 273 8910',
            email: 'bookings@colombofutsal.lk',
            status: 'active',
            operatingHours: '06:00 AM - 11:00 PM',
            amenities: ['Floodlights', 'Changing Rooms', 'Showers', 'Parking', 'Café', 'Drinking Water', 'First Aid'],
            courts: [
                { id: 'c1', name: 'Turf Court 1 (Floodlit)', sport: 'futsal', rate: 5000, type: 'Synthetic Grass' },
                { id: 'c2', name: 'Turf Court 2 (Indoor)', sport: 'futsal', rate: 4500, type: 'Synthetic Turf' },
                { id: 'c3', name: 'Wooden Badminton Court A', sport: 'badminton', rate: 2500, type: 'Teak Wood Floor' }
            ],
            mostActiveCustomers: [
                { name: 'Kasun Jayawardena', hours: 26, avatar: 'KJ' },
                { name: 'Shenal Gunaratne', hours: 19, avatar: 'SG' },
                { name: 'Akila Samarasinghe', hours: 16, avatar: 'AS' },
                { name: 'Tharindu Fernando', hours: 14, avatar: 'TF' },
                { name: 'Pradeep Bandara', hours: 12, avatar: 'PB' }
            ]
        },
        {
            id: 'venue-2',
            name: 'CR&FC Badminton Complex',
            slug: 'crfc-badminton-complex',
            tagline: 'Tournament grade Yonex synthetic rubber mat courts',
            address: 'Longdon Place, Colombo 07',
            city: 'Colombo 07',
            sports: ['badminton', 'squash'],
            rating: 4.9,
            reviewCount: 210,
            startingPrice: 2800,
            image: 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=800&q=80',
            owner: 'Rohan Wickramasinghe',
            phone: '+94 11 269 4118',
            email: 'badminton@crfc.lk',
            status: 'active',
            operatingHours: '06:00 AM - 10:00 PM',
            amenities: ['Air Conditioning', 'Pro Shop', 'Locker Rooms', 'Café', 'Physio On Site', 'Ample Parking'],
            courts: [
                { id: 'c4', name: 'Court 1 - Yonex Mat', sport: 'badminton', rate: 2800, type: 'BWF Certified Rubber' },
                { id: 'c5', name: 'Court 2 - Yonex Mat', sport: 'badminton', rate: 2800, type: 'BWF Certified Rubber' },
                { id: 'c6', name: 'Squash Glass Back Court', sport: 'squash', rate: 3200, type: 'Maple Wood' }
            ],
            mostActiveCustomers: [
                { name: 'Dilshan Perera', hours: 32, avatar: 'DP' },
                { name: 'Anushka Wickramaratne', hours: 22, avatar: 'AW' },
                { name: 'Kasun Jayawardena', hours: 18, avatar: 'KJ' },
                { name: 'Naveen De Silva', hours: 15, avatar: 'ND' },
                { name: 'Buddhika Hettiarachchi', hours: 13, avatar: 'BH' }
            ]
        },
        {
            id: 'venue-3',
            name: 'ProPickle Arena Colombo',
            slug: 'propickle-arena',
            tagline: 'Sri Lanka’s premier dedicated pickleball facility',
            address: 'Pelawatte Road, Battaramulla',
            city: 'Battaramulla',
            sports: ['pickleball'],
            rating: 4.7,
            reviewCount: 88,
            startingPrice: 3000,
            image: 'https://images.unsplash.com/photo-1599474924187-334a4ae5bd3c?auto=format&fit=crop&w=800&q=80',
            owner: 'Chinthaka Rathnayake',
            phone: '+94 77 344 5512',
            email: 'info@propickle.lk',
            status: 'active',
            operatingHours: '06:00 AM - 10:00 PM',
            amenities: ['Paddle Rental', 'Ball Machine', 'Lounge', 'Pro Shop', 'Free Wi-Fi', 'Parking'],
            courts: [
                { id: 'c7', name: 'Pickleball Court 1', sport: 'pickleball', rate: 3000, type: 'Cushion Acrylic' },
                { id: 'c8', name: 'Pickleball Court 2', sport: 'pickleball', rate: 3000, type: 'Cushion Acrylic' }
            ],
            mostActiveCustomers: [
                { name: 'Amanda Wijesinghe', hours: 28, avatar: 'AW' },
                { name: 'Mahesh Senarath', hours: 21, avatar: 'MS' },
                { name: 'Dulip Silva', hours: 17, avatar: 'DS' },
                { name: 'Nipuni Alwis', hours: 14, avatar: 'NA' },
                { name: 'Harsha Kumara', hours: 10, avatar: 'HK' }
            ]
        },
        {
            id: 'venue-4',
            name: 'Otters Club Squash & Billiards',
            slug: 'otters-club',
            tagline: 'Historic sporting institution in Cinnamon Gardens',
            address: 'Bauddhaloka Mawatha, Colombo 07',
            city: 'Colombo 07',
            sports: ['squash', 'billiards', 'table-tennis'],
            rating: 4.85,
            reviewCount: 165,
            startingPrice: 2200,
            image: 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=800&q=80',
            owner: 'Priyantha De Mel',
            phone: '+94 11 258 1923',
            email: 'sports@ottersclub.lk',
            status: 'active',
            operatingHours: '07:00 AM - 10:30 PM',
            amenities: ['Member Dining', 'Billiards Lounge', 'Squash Viewing Gallery', 'Locker Rooms', 'Bar'],
            courts: [
                { id: 'c9', name: 'Squash Court A', sport: 'squash', rate: 2600, type: 'Hardwood' },
                { id: 'c10', name: 'Riley Match Billiard Table', sport: 'billiards', rate: 2200, type: 'English Slate' },
                { id: 'c11', name: 'Stag International TT Table', sport: 'table-tennis', rate: 1800, type: 'Anti-Glare Wood' }
            ],
            mostActiveCustomers: [
                { name: 'Rohan Rodrigo', hours: 24, avatar: 'RR' },
                { name: 'Chaminda Silva', hours: 20, avatar: 'CS' },
                { name: 'Gihan Pieris', hours: 18, avatar: 'GP' },
                { name: 'Ashan Jayasuriya', hours: 15, avatar: 'AJ' },
                { name: 'Dilan Karunaratne', hours: 11, avatar: 'DK' }
            ]
        },
        {
            id: 'venue-5',
            name: 'SpinMaster Carrom & Table Tennis Club',
            slug: 'spinmaster-academy',
            tagline: 'Professional training center & community recreation',
            address: 'Nawala Road, Rajagiriya',
            city: 'Rajagiriya',
            sports: ['table-tennis', 'carrom', 'billiards'],
            rating: 4.6,
            reviewCount: 74,
            startingPrice: 1200,
            image: 'https://images.unsplash.com/photo-1544698310-74ea9d1c8258?auto=format&fit=crop&w=800&q=80',
            owner: 'Sunil Weerakkody',
            phone: '+94 11 288 7765',
            email: 'info@spinmaster.lk',
            status: 'active',
            operatingHours: '08:00 AM - 10:00 PM',
            amenities: ['Air-Conditioned Hall', 'Pro Carrom Boards', 'Robot Ball Feeder', 'Refreshment Bar'],
            courts: [
                { id: 'c12', name: 'Butterfly Championship Table 1', sport: 'table-tennis', rate: 1600, type: 'Tournament Table' },
                { id: 'c13', name: 'Synco Championship Carrom Board 1', sport: 'carrom', rate: 1200, type: 'English Birch' },
                { id: 'c14', name: 'Synco Championship Carrom Board 2', sport: 'carrom', rate: 1200, type: 'English Birch' }
            ],
            mostActiveCustomers: [
                { name: 'Kasun Jayawardena', hours: 22, avatar: 'KJ' },
                { name: 'Damith Alahakoon', hours: 18, avatar: 'DA' },
                { name: 'Sajith Pathirana', hours: 14, avatar: 'SP' },
                { name: 'Indika Ranatunga', hours: 12, avatar: 'IR' },
                { name: 'Nalin Perera', hours: 9, avatar: 'NP' }
            ]
        }
    ],

    coaches: [
        {
            id: 'coach-1',
            name: 'Dilshan Perera',
            sport: 'Badminton',
            specialty: 'Singles Footwork & Deceptive Smashing',
            rating: 4.95,
            reviewsCount: 48,
            verified: true,
            experienceYears: 9,
            hourlyRate: 3500,
            avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
            bio: 'Former Sri Lanka National Squad member and BWF Level 2 accredited coach. Mentored over 150 players from beginners to tournament contenders.',
            certifications: 'BWF Coach Level 2 Certification (2020), Diploma in Sports Science (UCSC)',
            approvedVenues: ['CR&FC Badminton Complex', 'Colombo Futsal Club']
        },
        {
            id: 'coach-2',
            name: 'Shanilka Fernando',
            sport: 'Futsal',
            specialty: 'Tactical Positioning & 1v1 Dribbling',
            rating: 4.88,
            reviewsCount: 36,
            verified: true,
            experienceYears: 6,
            hourlyRate: 4000,
            avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
            bio: 'AFC "C" License certified coach. Passionate about rapid tempo futsal transitions and defensive stability for amateur and semi-pro teams.',
            certifications: 'AFC "C" Coaching Certificate, Sri Lanka Football Federation Youth License',
            approvedVenues: ['Colombo Futsal Club']
        },
        {
            id: 'coach-3',
            name: 'Amanda Wijesinghe',
            sport: 'Pickleball',
            specialty: 'Third Shot Drops, Dinking & Kitchen Strategy',
            rating: 4.92,
            reviewsCount: 29,
            verified: true,
            experienceYears: 4,
            hourlyRate: 3000,
            avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80',
            bio: 'Pioneer of the Colombo pickleball scene. Certified IPTPA coach helping racket sport converts quickly develop pickleball soft game.',
            certifications: 'IPTPA Level 2 Instructor, First Aid Certified',
            approvedVenues: ['ProPickle Arena Colombo']
        },
        {
            id: 'coach-4',
            name: 'Chaminda Silva',
            sport: 'Squash',
            specialty: 'Length Control & Court Movement',
            rating: 4.79,
            reviewsCount: 19,
            verified: false,
            experienceYears: 7,
            hourlyRate: 3800,
            avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
            bio: 'WSF Level 1 Coach. Specializes in building match stamina, explosive lateral movement and nick shot precision for squash players.',
            certifications: 'World Squash Federation (WSF) Level 1 Certificate',
            approvedVenues: ['Otters Club Squash & Billiards', 'CR&FC Badminton Complex']
        }
    ],

    coachingSessions: [
        {
            id: 'session-101',
            title: 'Badminton Jump Smash & Forecourt Mastery',
            coachId: 'coach-1',
            coachName: 'Dilshan Perera',
            sport: 'Badminton',
            venueId: 'venue-2',
            venueName: 'CR&FC Badminton Complex',
            courtName: 'Court 1 - Yonex Mat',
            date: '2026-09-24',
            timeSlot: '07:00 PM - 08:00 PM',
            duration: '1 Hour',
            capacity: 6,
            registeredCount: 4,
            fee: 3500,
            type: 'public',
            status: 'open',
            description: 'Intensive drills focusing on dynamic footwork recovery, deception at net and backcourt jump smash execution.'
        },
        {
            id: 'session-102',
            title: 'Futsal High-Press Tactical Clinic',
            coachId: 'coach-2',
            coachName: 'Shanilka Fernando',
            sport: 'Futsal',
            venueId: 'venue-1',
            venueName: 'Colombo Futsal Club',
            courtName: 'Turf Court 1 (Floodlit)',
            date: '2026-09-25',
            timeSlot: '08:00 PM - 09:00 PM',
            duration: '1 Hour',
            capacity: 8,
            registeredCount: 8,
            fee: 4000,
            type: 'public',
            status: 'full',
            description: 'Learn modern 2-2 diamond formation transitions, targeted ball trapping with sole, and defensive overload traps.'
        },
        {
            id: 'session-103',
            title: 'Pickleball Fundamentals & Match Drills',
            coachId: 'coach-3',
            coachName: 'Amanda Wijesinghe',
            sport: 'Pickleball',
            venueId: 'venue-3',
            venueName: 'ProPickle Arena Colombo',
            courtName: 'Pickleball Court 1',
            date: '2026-09-26',
            timeSlot: '05:00 PM - 06:00 PM',
            duration: '1 Hour',
            capacity: 4,
            registeredCount: 2,
            fee: 3000,
            type: 'public',
            status: 'open',
            description: 'Master the kitchen line rules, third-shot drop trajectory and unforced error minimization.'
        }
    ],

    sampleBookings: [
        {
            id: 'BK-9021',
            bookingCode: 'CP-78190',
            venueId: 'venue-1',
            venueName: 'Colombo Futsal Club',
            courtName: 'Turf Court 1',
            sport: 'Futsal',
            date: '2026-09-24',
            timeSlot: '08:00 PM - 09:00 PM',
            price: 5000,
            paymentMethod: 'Online (PayHere Sandbox)',
            paymentStatus: 'paid',
            status: 'confirmed',
            customerName: 'Kasun Jayawardena',
            customerPhone: '+94 77 123 4567',
            canResell: true,
            canCancel: true,
            hoursUntilSlot: 32
        },
        {
            id: 'BK-8942',
            bookingCode: 'CP-65201',
            venueId: 'venue-2',
            venueName: 'CR&FC Badminton Complex',
            courtName: 'Court 1 - Yonex Mat',
            sport: 'Badminton',
            date: '2026-09-23',
            timeSlot: '06:00 PM - 07:00 PM',
            price: 2800,
            paymentMethod: 'Cash on Arrival',
            paymentStatus: 'unpaid',
            status: 'confirmed',
            customerName: 'Kasun Jayawardena',
            customerPhone: '+94 77 123 4567',
            canResell: false,
            canCancel: true,
            hoursUntilSlot: 8
        },
        {
            id: 'BK-8810',
            bookingCode: 'CP-43098',
            venueId: 'venue-4',
            venueName: 'Otters Club Squash',
            courtName: 'Squash Court A',
            sport: 'Squash',
            date: '2026-09-18',
            timeSlot: '07:00 AM - 08:00 AM',
            price: 2600,
            paymentMethod: 'Online (PayHere Sandbox)',
            paymentStatus: 'paid',
            status: 'completed',
            customerName: 'Kasun Jayawardena',
            customerPhone: '+94 77 123 4567',
            canResell: false,
            canCancel: false,
            hasReviewed: true
        },
        {
            id: 'BK-8604',
            bookingCode: 'CP-21980',
            venueId: 'venue-5',
            venueName: 'SpinMaster TT Club',
            courtName: 'Butterfly Table 1',
            sport: 'Table Tennis',
            date: '2026-09-12',
            timeSlot: '05:00 PM - 06:00 PM',
            price: 1600,
            paymentMethod: 'Online (PayHere Sandbox)',
            paymentStatus: 'refunded',
            status: 'cancelled',
            customerName: 'Kasun Jayawardena',
            customerPhone: '+94 77 123 4567',
            canResell: false,
            canCancel: false
        }
    ],

    resaleRequests: [
        {
            id: 'RS-401',
            bookingId: 'BK-7782',
            venueName: 'Colombo Futsal Club',
            courtName: 'Turf Court 2',
            sport: 'Futsal',
            date: '2026-09-24',
            timeSlot: '09:00 PM - 10:00 PM',
            originalPrice: 4500,
            refundAmount: 4050, // 90%
            platformFee: 450,   // 10%
            sellerName: 'Shenal Gunaratne',
            status: 'live_in_booking_pool',
            statusLabel: 'Live in Standard Booking Pool',
            canRevoke: true
        },
        {
            id: 'RS-390',
            bookingId: 'BK-7501',
            venueName: 'CR&FC Badminton Complex',
            courtName: 'Court 1',
            sport: 'Badminton',
            date: '2026-09-20',
            timeSlot: '06:00 PM - 07:00 PM',
            originalPrice: 2800,
            refundAmount: 2520, // 90%
            platformFee: 280,   // 10%
            sellerName: 'Nuwan Perera',
            status: 'resold_refunded',
            statusLabel: 'Resold & 90% Refunded',
            canRevoke: false
        },
        {
            id: 'RS-382',
            bookingId: 'BK-7210',
            venueName: 'ProPickle Arena Colombo',
            courtName: 'Pickleball Court 2',
            sport: 'Pickleball',
            date: '2026-09-18',
            timeSlot: '05:00 PM - 06:00 PM',
            originalPrice: 3000,
            refundAmount: 0,
            platformFee: 0,
            sellerName: 'Akila Silva',
            status: 'expired_unsold',
            statusLabel: 'Expired Unsold (No Refund)',
            canRevoke: false
        }
    ],

    flashDeals: [
        {
            id: 'FD-1',
            venueName: 'Colombo Futsal Club',
            courtName: 'Turf Court 1',
            date: 'Today',
            timeSlot: '10:00 PM - 11:00 PM',
            originalRate: 5000,
            flashRate: 3500,
            discount: '30% OFF',
            expiresIn: '2 hrs 40 mins'
        },
        {
            id: 'FD-2',
            venueName: 'SpinMaster Academy',
            courtName: 'Butterfly TT Table 1',
            date: 'Today',
            timeSlot: '09:00 PM - 10:00 PM',
            originalRate: 1600,
            flashRate: 1000,
            discount: '37% OFF',
            expiresIn: '1 hr 45 mins'
        }
    ],

    announcements: [
        {
            id: 'ann-1',
            venueId: 'venue-1',
            venueName: 'Colombo Futsal Club',
            title: 'Monsoon Synthetic Turf Resurfacing Complete',
            type: 'operational',
            date: '2026-09-20',
            content: 'Turf Court 1 has been upgraded with FIFA-standard shock pads and high-drainage infill. Optimal play during light rain is now fully supported.'
        },
        {
            id: 'ann-2',
            venueId: 'venue-2',
            venueName: 'CR&FC Badminton Complex',
            title: 'Colombo Corporate Badminton League Registrations Open',
            type: 'promotional',
            date: '2026-09-19',
            content: 'Book 8 or more peak hours this month to qualify for the annual CR&FC Corporate Tournament invitation bracket.'
        }
    ],

    userRoles: {
        guest: { name: 'Guest Visitor', role: 'guest' },
        customer: {
            name: 'Kasun Jayawardena',
            role: 'customer',
            email: 'kasun.j@gmail.com',
            phone: '+94 77 123 4567',
            reliabilityScore: 88,
            reliabilityTier: 'Standard',
            completedBookings: 14,
            noShows: 0,
            cashOnArrivalEligible: true,
            sportsDiary: {
                totalSessions: 14,
                hoursPlayed: 18,
                venuesVisited: 4,
                mostActiveMonth: 'August 2026',
                memberSince: 'March 2026',
                sportBreakdown: {
                    'Futsal': 8,
                    'Badminton': 4,
                    'Squash': 2
                }
            }
        },
        owner: {
            name: 'Nuwan Senanayake',
            role: 'owner',
            venueName: 'Colombo Futsal Club',
            email: 'nuwan@colombofutsal.lk',
            phone: '+94 77 889 9123',
            venuesCount: 2,
            activeCourtsCount: 5
        },
        coach: {
            name: 'Coach Dilshan Perera',
            role: 'coach',
            sport: 'Badminton',
            email: 'coach.dilshan@courtpass.lk',
            phone: '+94 71 445 6789',
            verified: true,
            activeSessions: 3,
            studentsTrained: 64,
            monthlyEarnings: 87500
        },
        admin: {
            name: 'CourtPass Platform Admin',
            role: 'admin',
            email: 'admin@courtpass.lk',
            pendingVenues: 2,
            pendingCoaches: 3,
            openDisputes: 2
        }
    }
};

// LocalStorage Persistence Wrapper for Demo Interactivity
(function initStorage() {
    if (!localStorage.getItem('courtpass_active_role')) {
        localStorage.setItem('courtpass_active_role', 'guest');
    }
})();
