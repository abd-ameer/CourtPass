<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Court Management</span>
 </div>
 <h1 class="page-title">Courts & Pitch Inventory</h1>
 <div class="page-subtitle">Configure courts, surface specs, hourly rates, and active states.</div>
 </div>

 <a href="<?= url('/owner/edit-court') ?>" class="btn btn-primary">
 + Add New Court
 </a>
 </div>

 <div class="card">
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Court Name</th>
 <th>Sport Discipline</th>
 <th>Surface / Spec</th>
 <th>Hourly Rate (LKR)</th>
 <th>Operating Window</th>
 <th>Status</th>
 <th style="text-align: right;">Actions</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td><strong>Turf Court 1 (Floodlit)</strong></td>
 <td><span class="badge badge-confirmed"> Futsal</span></td>
 <td>Synthetic Grass (Shock pad)</td>
 <td><strong>LKR 5,000</strong></td>
 <td>06:00 AM - 11:00 PM</td>
 <td><span class="badge badge-active">Active</span></td>
 <td style="text-align: right;">
 <a href="<?= url('/owner/edit-court') ?>?id=c1" class="btn btn-sm btn-outline">Edit</a>
 <a href="<?= url('/owner/slot-management') ?>?court=c1" class="btn btn-sm btn-primary">Slots</a>
 </td>
 </tr>
 <tr>
 <td><strong>Turf Court 2 (Indoor)</strong></td>
 <td><span class="badge badge-confirmed"> Futsal</span></td>
 <td>Indoor Synthetic Turf</td>
 <td><strong>LKR 4,500</strong></td>
 <td>06:00 AM - 11:00 PM</td>
 <td><span class="badge badge-active">Active</span></td>
 <td style="text-align: right;">
 <a href="<?= url('/owner/edit-court') ?>?id=c2" class="btn btn-sm btn-outline">Edit</a>
 <a href="<?= url('/owner/slot-management') ?>?court=c2" class="btn btn-sm btn-primary">Slots</a>
 </td>
 </tr>
 <tr>
 <td><strong>Wooden Badminton Court A</strong></td>
 <td><span class="badge badge-confirmed"> Badminton</span></td>
 <td>Teak Hardwood Flooring</td>
 <td><strong>LKR 2,500</strong></td>
 <td>06:00 AM - 10:00 PM</td>
 <td><span class="badge badge-active">Active</span></td>
 <td style="text-align: right;">
 <a href="<?= url('/owner/edit-court') ?>?id=c3" class="btn btn-sm btn-outline">Edit</a>
 <a href="<?= url('/owner/slot-management') ?>?court=c3" class="btn btn-sm btn-primary">Slots</a>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
