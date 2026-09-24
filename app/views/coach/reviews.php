<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Reviews</span>
 </div>
 <h1 class="page-title">Reviews & Replies </h1>
 <div class="page-subtitle">Manage student feedback and post official verified responses to build trust.</div>
 </div>

 <div>
 <a href="<?= url('/coaches/7') ?>" target="_blank" class="btn btn-outline">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
 View Public Coach Profile
 </a>
 </div>
 </div>

 <!-- Rating Overview Row -->
 <div class="grid grid-cols-3 gap-6" style="margin-bottom: var(--space-6);">
 <!-- Summary Score Card -->
 <div class="card" style="text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: var(--space-6);">
 <div style="font-size: 48px; font-weight: 900; color: var(--color-text-heading); line-height: 1;">4.9</div>
 <div style="color: #f59e0b; font-size: 20px; margin: 8px 0;"></div>
 <div style="font-size: var(--font-size-sm); color: var(--color-text-muted);">Based on <strong>18 verified student reviews</strong></div>
 <span class="badge badge-confirmed" style="margin-top: 10px;">98% Positive Feedback</span>
 </div>

 <!-- Rating Distribution -->
 <div class="card" style="grid-column: span 2; padding: var(--space-6);">
 <h4 style="font-size: var(--font-size-sm); font-weight: 700; margin-bottom: var(--space-4); text-transform: uppercase; color: var(--color-text-subtle);">Rating Distribution</h4>
 
 <div style="display: flex; flex-direction: column; gap: 8px;">
 <div style="display: flex; align-items: center; gap: 12px; font-size: 12px;">
 <span style="width: 45px; font-weight: 600;">5 Stars</span>
 <div style="flex: 1; height: 8px; background: var(--color-bg); border-radius: 9999px; overflow: hidden;">
 <div style="width: 88%; height: 100%; background: var(--color-primary-active);"></div>
 </div>
 <span style="width: 40px; text-align: right; color: var(--color-text-subtle);">16 (88%)</span>
 </div>

 <div style="display: flex; align-items: center; gap: 12px; font-size: 12px;">
 <span style="width: 45px; font-weight: 600;">4 Stars</span>
 <div style="flex: 1; height: 8px; background: var(--color-bg); border-radius: 9999px; overflow: hidden;">
 <div style="width: 12%; height: 100%; background: #3b82f6;"></div>
 </div>
 <span style="width: 40px; text-align: right; color: var(--color-text-subtle);">2 (12%)</span>
 </div>

 <div style="display: flex; align-items: center; gap: 12px; font-size: 12px;">
 <span style="width: 45px; font-weight: 600;">3 Stars</span>
 <div style="flex: 1; height: 8px; background: var(--color-bg); border-radius: 9999px; overflow: hidden;">
 <div style="width: 0%; height: 100%; background: #f59e0b;"></div>
 </div>
 <span style="width: 40px; text-align: right; color: var(--color-text-subtle);">0 (0%)</span>
 </div>

 <div style="display: flex; align-items: center; gap: 12px; font-size: 12px;">
 <span style="width: 45px; font-weight: 600;">2 Stars</span>
 <div style="flex: 1; height: 8px; background: var(--color-bg); border-radius: 9999px; overflow: hidden;">
 <div style="width: 0%; height: 100%; background: #f97316;"></div>
 </div>
 <span style="width: 40px; text-align: right; color: var(--color-text-subtle);">0 (0%)</span>
 </div>

 <div style="display: flex; align-items: center; gap: 12px; font-size: 12px;">
 <span style="width: 45px; font-weight: 600;">1 Star</span>
 <div style="flex: 1; height: 8px; background: var(--color-bg); border-radius: 9999px; overflow: hidden;">
 <div style="width: 0%; height: 100%; background: var(--color-danger);"></div>
 </div>
 <span style="width: 40px; text-align: right; color: var(--color-text-subtle);">0 (0%)</span>
 </div>
 </div>
 </div>
 </div>

 <!-- Filter Controls -->
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 12px;">
 <div style="display: flex; gap: 8px;">
 <button class="btn btn-sm btn-primary">All Reviews (18)</button>
 <button class="btn btn-sm btn-outline">Awaiting Reply (1)</button>
 <button class="btn btn-sm btn-outline">Replied (17)</button>
 </div>

 <select class="form-control" style="font-size: var(--font-size-xs); width: 180px;">
 <option>Newest First</option>
 <option>Highest Rated</option>
 <option>Lowest Rated</option>
 </select>
 </div>

 <!-- Reviews Feed -->
 <div style="display: flex; flex-direction: column; gap: var(--space-4);">

 <!-- Review 1: Awaiting Reply -->
 <div class="card" id="review-card-101" style="border-left: 4px solid var(--color-warning);">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3);">
 <div style="display: flex; align-items: center; gap: 12px;">
 <div class="user-avatar" style="background: #e0f2fe; color: #0284c7; width: 42px; height: 42px;">KM</div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-weight: 700; color: var(--color-text-heading);">Kasun Mendis</span>
 <span class="badge badge-confirmed"> Verified Attendee</span>
 </div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Session: Advanced Smash & Footwork Drills · 20 Sep 2026</div>
 </div>
 </div>

 <div style="text-align: right;">
 <div style="color: #f59e0b; font-size: 14px;"></div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">2 days ago</div>
 </div>
 </div>

 <p style="font-size: var(--font-size-sm); color: var(--color-text-body); line-height: 1.6; margin-bottom: var(--space-4);">
 "Coach Dilshan is exceptional! The footwork split-step drills corrected my recovery time instantly. Very patient and gave individual corrections to each student despite being a group session. Looking forward to the next one!"
 </p>

 <!-- Reply Form (Single response permitted) -->
 <form id="reply-box-101" method="POST" action="<?= url('/coach/reviews/2/response') ?>" style="background: var(--color-bg); border-radius: var(--radius-lg); padding: var(--space-4);">
 <?= csrf_field() ?>
 <label for="reply-text-101" style="font-weight: 700; font-size: 12px; margin-bottom: 6px; color: var(--color-text-heading); display: block;">Post Official Coach Reply:</label>
 <textarea name="response_text" class="form-control" id="reply-text-101" rows="2" maxlength="1000" required style="font-size: 12px; margin-bottom: 8px;"></textarea>
 <div style="display: flex; justify-content: space-between; align-items: center;">
 <span style="font-size: 11px; color: var(--color-text-subtle);">Only one reply is allowed per review.</span>
 <button type="submit" class="btn btn-sm btn-primary">Post Reply</button>
 </div>
 </form>
 </div>
 </div>

 <!-- Review 2: Already Replied -->
 <div class="card" id="review-card-102">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3);">
 <div style="display: flex; align-items: center; gap: 12px;">
 <div class="user-avatar" style="background: #ede9fe; color: #7c3aed; width: 42px; height: 42px;">TW</div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-weight: 700; color: var(--color-text-heading);">Tharindu Wickrama</span>
 <span class="badge badge-confirmed"> Verified Attendee</span>
 </div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Session: Power Smash Conditioning · 14 Sep 2026</div>
 </div>
 </div>

 <div style="text-align: right;">
 <div style="color: #f59e0b; font-size: 14px;"></div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">8 days ago</div>
 </div>
 </div>

 <p style="font-size: var(--font-size-sm); color: var(--color-text-body); line-height: 1.6; margin-bottom: var(--space-4);">
 "Loved the intensive smash rhythm exercises. Venue court grip at CR&FC was pristine as well. Will definitely recommend Coach Dilshan to anyone playing amateur tournaments."
 </p>

 <!-- Indented Coach Response -->
 <div style="background: #e6f8f0; border-left: 3px solid var(--color-primary-active); border-radius: var(--radius-md); padding: var(--space-3) var(--space-4); margin-top: var(--space-2);">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
 <div style="font-weight: 700; font-size: 12px; color: var(--color-primary-active);">Coach Dilshan (Official Response)</div>
 <div style="font-size: 10px; color: var(--color-text-subtle);">15 Sep 2026</div>
 </div>
 <p style="font-size: 12px; color: var(--color-text-body); margin-bottom: 0; line-height: 1.5;">
 "Thank you Tharindu! Your wrist snap was already looking much crisper by the end of the second set of drills. Keep practicing those warm-up swings before matches!"
 </p>
 </div>
 </div>
 </div>

 <!-- Review 3: 4 Stars -->
 <div class="card" id="review-card-103">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3);">
 <div style="display: flex; align-items: center; gap: 12px;">
 <div class="user-avatar" style="background: #fef3c7; color: #d97706; width: 42px; height: 42px;">ND</div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-weight: 700; color: var(--color-text-heading);">Naveen Dias</span>
 <span class="badge badge-confirmed"> Verified Attendee</span>
 </div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Session: Beginners Badminton Basics · 06 Sep 2026</div>
 </div>
 </div>

 <div style="text-align: right;">
 <div style="color: #f59e0b; font-size: 14px;"></div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">16 days ago</div>
 </div>
 </div>

 <p style="font-size: var(--font-size-sm); color: var(--color-text-body); line-height: 1.6; margin-bottom: var(--space-4);">
 "Great coaching session for fundamentals. The 1-hour time passed very quickly, wish it could be 1.5 hours to allow more free rally time at the end."
 </p>

 <!-- Indented Coach Response -->
 <div style="background: #e6f8f0; border-left: 3px solid var(--color-primary-active); border-radius: var(--radius-md); padding: var(--space-3) var(--space-4); margin-top: var(--space-2);">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
 <div style="font-weight: 700; font-size: 12px; color: var(--color-primary-active);">Coach Dilshan (Official Response)</div>
 <div style="font-size: 10px; color: var(--color-text-subtle);">07 Sep 2026</div>
 </div>
 <p style="font-size: 12px; color: var(--color-text-body); margin-bottom: 0; line-height: 1.5;">
 "Thanks for the valuable suggestion Naveen! I will add more supervised match rallies to the next sessions."
 </p>
 </div>
 </div>
 </div>

 </div>

 
 


