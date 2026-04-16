<?php $pageTitle='Admin Panel'; $activePage='admin';
require_once BASE_PATH.'/app/views/layouts/header.php'; ?>
<nav class="sidebar admin-sidebar">
    <a class="dashboardTitle" href="<?= BASE_URL ?>/admin">⚙️ ADMIN</a>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="#" onclick="showTab('overview',this);return false"><i class="bi bi-grid"></i> Overview</a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="showTab('users',this);return false"><i class="bi bi-people"></i> Users</a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="showTab('bookings',this);return false"><i class="bi bi-calendar-check"></i> Bookings</a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="showTab('sessions',this);return false"><i class="bi bi-activity"></i> Sessions</a></li>
        <li class="nav-item mt-3"><a class="nav-link" href="<?= BASE_URL ?>/dashboard"><i class="bi bi-arrow-left-circle"></i> Back to App</a></li>
    </ul>
    <a href="<?= BASE_URL ?>/auth/logout"><button class="logout-btn">Logout</button></a>
</nav>
<main class="main-content flex-grow-1">
    <header class="topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3"><h5 class="mb-0" id="pageTitle">Overview</h5><span class="pill pill-admin">Admin</span></div>
        <div class="d-flex align-items-center gap-3"><i class="bi bi-bell" role="button"></i><span style="font-size:13px;color:var(--text-muted)"><?= htmlspecialchars($_SESSION['user_name']) ?></span></div>
    </header>

    <section id="tab-overview">
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3"><div class="stat-admin" style="--accent-color:var(--green)"><div class="sa-label">Total Users</div><div class="sa-value"><?= number_format($totalUsers) ?></div></div></div>
            <div class="col-6 col-md-3"><div class="stat-admin" style="--accent-color:var(--cyan)"><div class="sa-label">Active Today</div><div class="sa-value"><?= $activeToday ?></div></div></div>
            <div class="col-6 col-md-3"><div class="stat-admin" style="--accent-color:var(--orange)"><div class="sa-label">Bookings Today</div><div class="sa-value"><?= $bookingsToday ?></div></div></div>
            <div class="col-6 col-md-3"><div class="stat-admin" style="--accent-color:#4f9eff"><div class="sa-label">Avg. Session</div><div class="sa-value"><?= $avgDuration ?> <span class="value-unit">min</span></div></div></div>
        </div>
        <div class="row g-3">
            <div class="col-md-7"><div class="panel-card"><div class="panel-card-header"><h6 class="panel-card-title">Recent Users</h6></div><div class="panel-card-body p-0"><table class="table-dark-custom w-100"><thead><tr><th>Name</th><th>Email</th><th>Joined</th><th>Status</th></tr></thead><tbody>
            <?php foreach(array_slice($allUsers,0,5) as $u): ?>
            <tr><td style="font-size:13px;font-weight:500"><?= htmlspecialchars($u['name']) ?></td><td style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($u['email']) ?></td><td style="font-size:12px;color:var(--text-muted)"><?= date('M j, Y',strtotime($u['created_at'])) ?></td><td><span class="pill pill-<?= $u['role']==='admin'?'admin':$u['status'] ?>"><?= $u['role']==='admin'?'admin':$u['status'] ?></span></td></tr>
            <?php endforeach; ?>
            </tbody></table></div></div></div>
            <div class="col-md-5"><div class="panel-card h-100"><div class="panel-card-header"><h6 class="panel-card-title">Workout Popularity</h6></div><div class="panel-card-body">
            <?php $maxWk=max(array_column($workoutStats,'booking_count')??[1]); $cls=['var(--green)','var(--cyan)','var(--orange)','#4f9eff','#b794f4','var(--text-muted)'];
            foreach($workoutStats as $i=>$wk): $pct=$maxWk>0?round(($wk['booking_count']/$maxWk)*100):0; ?>
            <div class="d-flex align-items-center gap-3 mb-3"><div style="width:120px;font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($wk['name']) ?></div><div style="flex:1;height:6px;background:var(--bg-3);border-radius:4px;overflow:hidden"><div style="width:<?= $pct ?>%;height:100%;background:<?= $cls[$i%6] ?>;border-radius:4px"></div></div><div style="width:24px;font-size:12px;font-weight:600;text-align:right"><?= $wk['booking_count'] ?></div></div>
            <?php endforeach; ?>
            </div></div></div>
        </div>
    </section>

    <section id="tab-users" class="hidden">
        <div class="d-flex justify-content-between align-items-center mb-3"><span style="font-size:13px;color:var(--text-muted)"><?= count($allUsers) ?> users</span><button class="btn-profit" onclick="document.getElementById('addUserModal').classList.remove('hidden')"><i class="bi bi-person-plus me-1"></i>Add User</button></div>
        <div class="filter-bar"><input class="filter-input" id="userSearch" type="text" placeholder="Search name or email..." oninput="filterTable('userTbody','userSearch')"></div>
        <div class="panel-card"><div class="panel-card-body p-0"><table class="table-dark-custom w-100"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead><tbody id="userTbody">
        <?php foreach($allUsers as $u): ?>
        <tr id="user-row-<?= $u['id'] ?>">
            <td style="font-size:13px;font-weight:500"><?= htmlspecialchars($u['name']) ?></td>
            <td style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($u['email']) ?></td>
            <td><select class="filter-select" style="font-size:11px;padding:4px 8px" onchange="changeRole(<?= $u['id'] ?>,this.value)"><option value="member" <?= $u['role']==='member'?'selected':'' ?>>member</option><option value="trainer" <?= $u['role']==='trainer'?'selected':'' ?>>trainer</option><option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>admin</option></select></td>
            <td style="font-size:12px;color:var(--text-muted)"><?= date('M j, Y',strtotime($u['created_at'])) ?></td>
            <td><span class="pill pill-<?= $u['role']==='admin'?'admin':$u['status'] ?>" id="status-pill-<?= $u['id'] ?>"><?= $u['role']==='admin'?'admin':$u['status'] ?></span></td>
            <td><?php if($u['role']!=='admin'): ?><div class="d-flex gap-1"><button class="action-btn success" onclick="toggleStatus(<?= $u['id'] ?>)" title="Toggle ban"><i class="bi bi-toggle-on"></i></button><button class="action-btn danger" onclick="deleteUser(<?= $u['id'] ?>)" title="Delete"><i class="bi bi-trash3"></i></button></div><?php else: ?><span style="font-size:11px;color:var(--text-muted)">protected</span><?php endif; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody></table></div></div>
    </section>

    <section id="tab-bookings" class="hidden">
        <div class="filter-bar"><input class="filter-input" id="bookingSearch" type="text" placeholder="Search user or trainer..." oninput="filterTable('bookingTbody','bookingSearch')"></div>
        <div class="panel-card"><div class="panel-card-body p-0"><table class="table-dark-custom w-100"><thead><tr><th>User</th><th>Trainer</th><th>Workout</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th></tr></thead><tbody id="bookingTbody">
        <?php foreach($allBookings as $b): ?>
        <tr id="booking-row-<?= $b['id'] ?>">
            <td style="font-size:13px;font-weight:500"><?= htmlspecialchars($b['user_name']) ?></td>
            <td style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($b['buddy_name']) ?></td>
            <td style="font-size:12px"><?= htmlspecialchars($b['workout_name']) ?></td>
            <td style="font-size:12px;color:var(--text-muted)"><?= date('M j, Y',strtotime($b['date'])) ?></td>
            <td style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($b['time']) ?></td>
            <td><span class="pill pill-<?= $b['status']==='confirmed'?'active':($b['status']==='cancelled'?'banned':'pending') ?>" id="booking-status-<?= $b['id'] ?>"><?= $b['status'] ?></span></td>
            <td><div class="d-flex gap-1"><button class="action-bgtn success" onclick="updateBooking(<?= $b['id'] ?>,'confirmed')" title="Confirm"><i class="bi bi-check-lg"></i></button><button class="action-btn danger" onclick="updateBooking(<?= $b['id'] ?>,'cancelled')" title="Cancel"><i class="bi bi-x-lg"></i></button></div></td>
        </tr>
        <?php endforeach; ?>
        </tbody></table></div></div>
    </section>

    <section id="tab-sessions" class="hidden">
        <div class="panel-card"><div class="panel-card-body p-0"><table class="table-dark-custom w-100"><thead><tr><th>User</th><th>Workout</th><th>Duration</th><th>Calories</th><th>Trainer</th><th>Date</th></tr></thead><tbody>
        <?php foreach($allSessions as $s): ?>
        <tr><td style="font-size:13px;font-weight:500"><?= htmlspecialchars($s['user_name']) ?></td><td style="font-size:12px"><?= htmlspecialchars($s['workout_name']) ?></td><td style="font-size:12px;color:var(--cyan)"><?= $s['duration'] ?> min</td><td style="font-family:'Rajdhani',sans-serif;font-size:16px;font-weight:700;color:var(--orange)"><?= $s['calories'] ?></td><td style="font-size:12px;color:var(--text-muted)"><?= $s['buddy_name']?htmlspecialchars($s['buddy_name']):'—' ?></td><td style="font-size:12px;color:var(--text-muted)"><?= date('M j, Y',strtotime($s['session_date'])) ?></td></tr>
        <?php endforeach; ?>
        </tbody></table></div></div>
    </section>
</main>

<div class="modal-overlay hidden" id="addUserModal">
    <div class="modal-box">
        <div class="d-flex justify-content-between align-items-center mb-4"><h5 class="modal-title mb-0">Add New User</h5><button class="action-btn" onclick="document.getElementById('addUserModal').classList.add('hidden')"><i class="bi bi-x-lg"></i></button></div>
        <div class="row g-3">
            <div class="col-12"><div class="form-label-sm">Full Name</div><input class="input-dark" id="newName" type="text" placeholder="e.g. Paul Santos"></div>
            <div class="col-12"><div class="form-label-sm">Email</div><input class="input-dark" id="newEmail" type="email" placeholder="e.g. paul@profit.app"></div>
            <div class="col-md-6"><div class="form-label-sm">Role</div><select class="input-dark" id="newRole"><option value="member">Member</option><option value="trainer">Trainer</option><option value="admin">Admin</option></select></div>
            <div class="col-md-6"><div class="form-label-sm">Status</div><select class="input-dark" id="newStatus"><option value="active">Active</option><option value="pending">Pending</option></select></div>
            <div class="col-12 d-flex justify-content-end gap-2 mt-2"><button class="btn-ghost" onclick="document.getElementById('addUserModal').classList.add('hidden')">Cancel</button><button class="btn-profit" onclick="addUser()"><i class="bi bi-person-plus me-1"></i>Add User</button></div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH.'/app/views/layouts/footer.php'; ?>
<script>
const B='<?= BASE_URL ?>';
const tabs=['overview','users','bookings','sessions'];
const titles={overview:'Overview',users:'User Management',bookings:'Bookings',sessions:'Sessions'};
function showTab(t,el){tabs.forEach(x=>document.getElementById('tab-'+x).classList.add('hidden'));document.getElementById('tab-'+t).classList.remove('hidden');document.getElementById('pageTitle').textContent=titles[t];document.querySelectorAll('.sidebar .nav-link').forEach(l=>l.classList.remove('active'));if(el)el.classList.add('active');}
function filterTable(tid,iid){const q=document.getElementById(iid).value.toLowerCase();document.querySelectorAll('#'+tid+' tr').forEach(r=>r.style.display=r.textContent.toLowerCase().includes(q)?'':'none');}
async function post(url,body){const r=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});return r.json();}
async function toggleStatus(id){const r=await post(B+'/admin/togglestatus',{id});if(r.success){const p=document.getElementById('status-pill-'+id);p.textContent=r.new_status;p.className='pill pill-'+r.new_status;}}
async function changeRole(id,role){if(!confirm('Change this user to '+role+'?')){location.reload();return;}const r=await post(B+'/admin/changerole',{id,role});if(!r.success){alert(r.message);location.reload();}}
async function deleteUser(id){if(!confirm('Delete this user? Cannot be undone.'))return;const r=await post(B+'/admin/deleteuser',{id});if(r.success){const row=document.getElementById('user-row-'+id);if(row)row.remove();}else alert(r.message);}
async function updateBooking(id,status){const r=await post(B+'/admin/updatebooking',{id,status});if(r.success){const p=document.getElementById('booking-status-'+id);p.textContent=status;p.className='pill '+(status==='confirmed'?'pill-active':'pill-banned');}}
async function addUser(){const name=document.getElementById('newName').value.trim(),email=document.getElementById('newEmail').value.trim();if(!name||!email){alert('Name and email required.');return;}const r=await post(B+'/admin/adduser',{name,email,role:document.getElementById('newRole').value,status:document.getElementById('newStatus').value});if(r.success){document.getElementById('addUserModal').classList.add('hidden');location.reload();}else alert(r.message);}
</script>