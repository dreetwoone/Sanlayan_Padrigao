<?php $pageTitle='Buddies'; $activePage='buddies';
require_once BASE_PATH.'/app/views/layouts/header.php';
require_once BASE_PATH.'/app/views/layouts/sidebar.php'; ?>
    <main class="main-content flex-grow-1">
        <header class="topbar d-flex justify-content-between align-items-center">
            <h5>Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></h5>
        </header>
        <p class="section-heading">Buddy List</p>
        <?php if(empty($buddies)): ?>
            <p style="color:var(--text-muted);font-size:13px">No buddies found yet.</p>
        <?php else: ?>
        <div class="row g-3">
            <?php foreach($buddies as $b): $conn = in_array($b['id'],$myBuddyIds); ?>
            <div class="col-md-4">
                <div class="card-dark buddy-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="profile-img d-flex align-items-center justify-content-center" style="background:var(--bg-3); overflow:hidden;">
    <?php if (!empty($b['avatar']) && strpos($b['avatar'], '.') !== false): ?>
    <img src="app/uploads/<?= htmlspecialchars($b['avatar']) ?>"
         alt="Profile" 
         style="width: 100%; height: 100%; object-fit: cover;">
<?php else: ?>
    <span style="font-size:28px;"><?= $b['avatar'] ?></span>
<?php endif; ?>
</div>
                        <div class="recommended-buddy">
                            <h4><?= htmlspecialchars($b['name']) ?></h4>
                            <span class="badge-tag <?= htmlspecialchars($b['tag_color']) ?>"><?= htmlspecialchars($b['tag']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
<?php require_once BASE_PATH.'/app/views/layouts/footer.php'; ?>
<script>
async function addBuddy(el, id) {
    el.className='bi bi-hourglass text-success'; el.style.opacity='1';
    try {
        const r = await fetch('<?= BASE_URL ?>/buddies/add',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({buddy_id:id})});
        const d = await r.json();
        if(d.success){el.className='bi bi-check-circle-fill text-success';el.removeAttribute('onclick');}
        else{el.className='bi bi-check-circle text-success';el.style.opacity='.6';}
    } catch(e){el.className='bi bi-plus-circle text-success';el.style.opacity='.5';}
}
</script>