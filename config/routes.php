<?php
/**
 * All routes live here, grouped by module owner.
 * Pages return views. Routes under /api/ return JSON (for fetch()).
 * Add your routes only inside your own section to keep merges clean.
 *
 * Third argument = roles allowed: ['customer'], ['owner'], ['coach'], ['admin'], ['*'] = any logged-in user.
 *
 * @var Router $router
 */

// ---------- Shared ----------
$router->get('/', [HomeController::class, 'index']);
$router->get('/api/health', [SystemController::class, 'health']);

// ---------- Member A: Auth core, Booking Engine, Reliability ----------

// ---------- Member B: Owner sign-up, Venues, Courts, Payments, Resale, Flash, User management ----------

// ---------- Member C: Coach Operations ----------

// ---------- Member D: Customer sign-up, Discovery, Check-in, Community, Insights ----------
