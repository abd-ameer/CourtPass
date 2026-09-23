<!-- Hero Section with Search -->
<section style="background: linear-gradient(180deg, #ffffff 0%, var(--color-bg-page) 100%); padding: var(--space-12) 0 var(--space-8); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <div style="max-width: 780px; margin: 0 auto; text-align: center;">
            <div class="inline-flex items-center gap-2" style="background: var(--color-primary-light); color: var(--color-navy); padding: 6px 16px; border-radius: var(--radius-full); font-size: 13px; font-weight: 700; margin-bottom: var(--space-4); border: 1px solid var(--color-primary-border);">
                <span>Sri Lanka's Sports Venue &amp; Coaching Platform</span>
            </div>
            <h1 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; line-height: 1.15; margin-bottom: var(--space-3); color: var(--color-navy);">
                Book Courts, Join Coaches, <br><span style="color: var(--color-primary);">Play Without Friction.</span>
            </h1>
            <p class="lead" style="margin-bottom: var(--space-6); max-width: 620px; margin-left: auto; margin-right: auto; color: var(--color-text-muted);">
                Instant slot reservations for Badminton, Futsal, Pickleball, Squash, Table Tennis, Carrom and Billiards across Colombo.
            </p>

            <!-- Search & Availability Box -->
            <div class="card" style="padding: 16px 20px; border-radius: var(--radius-xl); box-shadow: var(--shadow-md); text-align: left; background: var(--color-white); border: 1px solid var(--color-border);">
                <form action="<?= url('/venues') ?>" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) 120px; gap: 12px; align-items: flex-end;">
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--color-navy);">Sport</label>
                        <select name="sport" class="form-select">
                            <option value="">All Sports</option>
                            <option value="badminton" selected>Badminton</option>
                            <option value="futsal">Futsal</option>
                            <option value="pickleball">Pickleball</option>
                            <option value="squash">Squash</option>
                            <option value="table-tennis">Table Tennis</option>
                            <option value="carrom">Carrom</option>
                            <option value="billiards">Billiards</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--color-navy);">Location / Area</label>
                        <select name="city" class="form-select">
                            <option value="">All Colombo Areas</option>
                            <option value="Colombo 07" selected>Colombo 07 (Cinnamon Gardens)</option>
                            <option value="Dehiwala">Dehiwala (Marine Drive)</option>
                            <option value="Rajagiriya">Rajagiriya / Nawala</option>
                            <option value="Battaramulla">Battaramulla / Pelawatte</option>
                            <option value="Colombo 03">Colombo 03 (Kollupitiya)</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--color-navy);">Date</label>
                        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="height: 42px;">
                        Find Courts
                    </button>
                </form>
            </div>

            <!-- Quick Trust Indicators -->
            <div style="display: flex; align-items: center; justify-content: center; gap: 24px; margin-top: var(--space-4); font-size: 13px; color: var(--color-text-muted); flex-wrap: wrap;">
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Real-time slot lock
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Reliability Trust System
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    PayHere &amp; Cash on Arrival
                </span>
            </div>

        </div>
    </div>
</section>

<!-- Sports Categories Horizontal Bar -->
<section style="padding: var(--space-6) 0; background: var(--color-white); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-3);">
            <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted);">Popular Sports</span>
            <a href="<?= url('/venues') ?>" style="font-size: 13px; font-weight: 600; color: var(--color-primary-hover); text-decoration: none;">View All &rarr;</a>
        </div>
        
        <div class="sport-chips-bar">
            <?php foreach ($sports as $sport): ?>
                <a href="<?= url('/venues?sport=' . urlencode($sport['slug'])) ?>" class="sport-chip <?= !empty($sport['active']) ? 'active' : '' ?>">
                    <strong><?= e($sport['name']) ?></strong> (<?= e($sport['count']) ?>)
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Venues Section -->
<section style="padding: var(--space-10) 0;">
    <div class="container">
        <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: var(--space-6); flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 style="font-size: var(--font-size-xl); color: var(--color-navy);">Featured Colombo Sports Facilities</h2>
                <p class="text-sm" style="margin-bottom: 0; color: var(--color-text-muted);">Certified courts with high-spec flooring, lighting, and community leaderboards</p>
            </div>
            <a href="<?= url('/venues') ?>" class="btn btn-outline">Explore All Venues &rarr;</a>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <?php foreach ($featuredVenues as $venue): ?>
                <div class="card card-hover venue-card">
                    <div class="venue-card-img-wrapper">
                        <img src="<?= e($venue['image']) ?>" alt="<?= e($venue['name']) ?>" class="venue-card-img">
                        <div class="venue-badge-overlay">
                            <?php foreach ($venue['badges'] as $badge): ?>
                                <span class="badge <?= e($badge['type']) ?>"><?= e($badge['label']) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="venue-price-badge">From <?= e($venue['price']) ?> / hr</div>
                    </div>
                    <div class="card-body flex flex-col flex-1">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                            <span class="badge badge-confirmed"><?= e($venue['category']) ?></span>
                            <span style="font-size: 13px; font-weight: 700; color: #d97706;">Rating: <?= e($venue['rating']) ?></span>
                        </div>
                        <h3 style="font-size: 17px; margin-bottom: 4px; font-weight: 700;">
                            <a href="<?= url('/venue-details?id=' . urlencode($venue['id'])) ?>" style="color: var(--color-navy); text-decoration: none;"><?= e($venue['name']) ?></a>
                        </h3>
                        <p class="text-sm" style="margin-bottom: 12px; color: var(--color-text-muted);">
                            <?= e($venue['location']) ?>
                        </p>
                        <div style="font-size: 12px; color: var(--color-text-main); margin-bottom: 16px; background: var(--color-bg-subtle); padding: 8px 12px; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
                            <strong>Top Player This Month:</strong> <?= e($venue['top_player']) ?>
                        </div>
                        <div style="margin-top: auto; display: flex; gap: 8px;">
                            <a href="<?= url('/venue-details?id=' . urlencode($venue['id'])) ?>" class="btn btn-outline btn-sm flex-1">View Venue</a>
                            <a href="<?= url('/court-details?court=' . urlencode($venue['court_id'])) ?>" class="btn btn-primary btn-sm flex-1">Check Slots</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Upcoming Coaching Sessions Section -->
<section style="padding: var(--space-10) 0; background: var(--color-white); border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: var(--space-6); flex-wrap: wrap; gap: 12px;">
            <div>
                <div class="badge" style="background: var(--color-primary-light); color: var(--color-navy); border: 1px solid var(--color-primary-border); margin-bottom: 6px;">Coaching Module</div>
                <h2 style="font-size: var(--font-size-xl); color: var(--color-navy);">Train with Verified Independent Coaches</h2>
                <p class="text-sm" style="margin-bottom: 0; color: var(--color-text-muted);">1-hour targeted group clinics on real court slots with verified reviews</p>
            </div>
            <a href="<?= url('/coaching') ?>" class="btn btn-outline">All Coaching Clinics &rarr;</a>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <?php foreach ($coachingSessions as $session): ?>
                <div class="card card-hover" style="border-top: 4px solid <?= e($session['border_color']) ?>;">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span class="badge badge-confirmed"><?= e($session['badge']) ?></span>
                            <span class="badge <?= e($session['status_badge']['class']) ?>"><?= e($session['status_badge']['label']) ?></span>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px; color: var(--color-navy);"><?= e($session['title']) ?></h3>
                        <p class="text-sm" style="margin-bottom: 14px; color: var(--color-text-muted);">
                            <?= e($session['venue']) ?>
                        </p>
                        
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding: 10px; background: var(--color-bg-subtle); border-radius: var(--radius-md); border: 1px solid var(--color-border);">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-primary-light); color: var(--color-navy); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                <?= e($session['coach_initials']) ?>
                            </div>
                            <div>
                                <div style="font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 4px; color: var(--color-navy);">
                                    <?= e($session['coach_name']) ?>
                                    <?php if (!empty($session['coach_verified'])): ?>
                                        <span class="badge badge-confirmed" style="font-size: 10px; padding: 1px 6px;">Verified</span>
                                    <?php endif; ?>
                                </div>
                                <div style="font-size: 11px; color: var(--color-text-muted);"><?= e($session['coach_cred']) ?></div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 11px; color: var(--color-text-muted);">Date &amp; Time</div>
                                <div style="font-size: 13px; font-weight: 700;"><?= e($session['datetime']) ?></div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 11px; color: var(--color-text-muted);">Fee</div>
                                <div style="font-size: 15px; font-weight: 800; color: var(--color-primary-active);"><?= e($session['fee']) ?></div>
                            </div>
                        </div>

                        <div style="margin-top: 16px;">
                            <?php if (!empty($session['is_full'])): ?>
                                <button class="btn btn-secondary btn-block disabled" disabled>Capacity Full (8/8)</button>
                            <?php else: ?>
                                <a href="<?= url('/session-details?id=' . urlencode($session['id'])) ?>" class="btn btn-primary btn-block">
                                    Register with PayHere
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How CourtPass Works -->
<section id="how-it-works" style="padding: var(--space-12) 0;">
    <div class="container">
        <div style="text-align: center; max-width: 650px; margin: 0 auto var(--space-8);">
            <h2 style="font-size: var(--font-size-2xl); margin-bottom: 8px; color: var(--color-navy);">How CourtPass Works</h2>
            <p class="text-sm" style="color: var(--color-text-muted);">Eliminating informal phone calls, forgotten WhatsApp messages, and unrecorded no-shows.</p>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div class="card" style="text-align: center; padding: var(--space-6);">
                <div style="width: 50px; height: 50px; background: var(--color-primary-light); color: var(--color-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; margin: 0 auto var(--space-4);">
                    1
                </div>
                <h3 style="font-size: 17px; font-weight: 700; margin-bottom: 8px; color: var(--color-navy);">Select Venue &amp; 1-Hour Slot</h3>
                <p class="text-sm" style="color: var(--color-text-muted); line-height: 1.5;">
                    Live conflict-safe availability grid checks slots in real-time. Choose your preferred court, sport type, and time.
                </p>
            </div>

            <div class="card" style="text-align: center; padding: var(--space-6);">
                <div style="width: 50px; height: 50px; background: var(--color-primary-light); color: var(--color-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; margin: 0 auto var(--space-4);">
                    2
                </div>
                <h3 style="font-size: 17px; font-weight: 700; margin-bottom: 8px; color: var(--color-navy);">Pay Online or On Arrival</h3>
                <p class="text-sm" style="color: var(--color-text-muted); line-height: 1.5;">
                    Seamless PayHere Sandbox checkout. Standard tier members unlock flexible Cash-on-Arrival booking privileges.
                </p>
            </div>

            <div class="card" style="text-align: center; padding: var(--space-6);">
                <div style="width: 50px; height: 50px; background: var(--color-primary-light); color: var(--color-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; margin: 0 auto var(--space-4);">
                    3
                </div>
                <h3 style="font-size: 17px; font-weight: 700; margin-bottom: 8px; color: var(--color-navy);">Check-In &amp; Grow Reliability</h3>
                <p class="text-sm" style="color: var(--color-text-muted); line-height: 1.5;">
                    Venue verifies your arrival. Maintain clean attendance records, climb venue leaderboards, and leave verified reviews.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call-to-Action Panels -->
<section style="padding: var(--space-12) 0; background: var(--color-bg-subtle); border-top: 1px solid var(--color-border);">
    <div class="container">
        <div class="grid grid-cols-3 gap-6">
            
            <!-- CTA 1: Players -->
            <div class="card" style="padding: var(--space-6); display: flex; flex-direction: column;">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px; color: var(--color-navy);">For Sports Players</h3>
                <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.5;">
                    Find open courts, join coaching masterclasses, and play with friends across Colombo.
                </p>
                <div style="margin-top: auto;">
                    <a href="<?= url('/register-customer') ?>" class="btn btn-primary btn-block">Sign Up as Player</a>
                </div>
            </div>

            <!-- CTA 2: Venue Owners -->
            <div class="card" style="padding: var(--space-6); display: flex; flex-direction: column; border-color: var(--color-primary-border);">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px; color: var(--color-navy);">For Venue Owners</h3>
                <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.5;">
                    Digitize your courts, block walk-ins, publish flash deals, eliminate no-shows, and track real operational revenue.
                </p>
                <div style="margin-top: auto;">
                    <a href="<?= url('/register-owner') ?>" class="btn btn-outline btn-block">List Your Sports Venue</a>
                </div>
            </div>

            <!-- CTA 3: Coaches -->
            <div class="card" style="padding: var(--space-6); display: flex; flex-direction: column;">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px; color: var(--color-navy);">For Sports Coaches</h3>
                <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.5;">
                    Partner with approved venues, publish paid 1-hour sessions, mark student attendance, and grow your coaching reputation.
                </p>
                <div style="margin-top: auto;">
                    <a href="<?= url('/register-coach') ?>" class="btn btn-secondary btn-block">Register as Coach</a>
                </div>
            </div>

        </div>
    </div>
</section>
