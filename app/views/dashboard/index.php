<?php $pageTitle='Dashboard'; $activePage='dashboard';
require_once BASE_PATH.'/app/views/layouts/header.php';
require_once BASE_PATH.'/app/views/layouts/sidebar.php'; ?>
    <main class="main-content flex-grow-1">
        <header class="topbar d-flex justify-content-between align-items-center">
            <h5>Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></h5>
        </header>
        <div class="row g-3">
            <div class="col-md-4"><div class="card-dark text-center"><h6>Workout Sessions</h6><h2><?= $sessionCount ?></h2></div></div>
            <div class="col-md-4"><div class="card-dark text-center"><h6>Calories Burned</h6><h2><?= number_format($totalCalories) ?></h2></div></div>
            <div class="col-md-4"><div class="card-dark text-center"><h6>Active Days</h6><h2><?= $activeDays ?></h2></div></div>
        </div>
        <p class="section-heading mt-4">Recommended Buddies</p>
        <div class="row g-3">
            <?php foreach ($buddies as $buddy): ?>
            <div class="col-md-4">
                <div class="card-dark buddy-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="profile-img d-flex align-items-center justify-content-center" style="background:var(--bg-3); overflow:hidden;">
    <?php if (!empty($buddy['avatar']) && strpos($buddy['avatar'], '.') !== false): ?>
        <img src="<?= BASE_URL ?>/app/uploads/<?= htmlspecialchars($buddy['avatar']) ?>" 
             alt="Profile" 
             style="width: 100%; height: 100%; object-fit: cover;">
    <?php else: ?>
        <span style="font-size:28px;"><?= $buddy['avatar'] ?></span>
    <?php endif; ?>
</div>
                        <div class="recommended-buddy">
                            <h4><?= htmlspecialchars($buddy['name']) ?></h4>
                            <span class="badge-tag <?= htmlspecialchars($buddy['tag_color']) ?>"><?= htmlspecialchars($buddy['tag']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($buddies)): ?><div class="col-12"><p style="color:var(--text-muted);font-size:13px">No buddies yet.</p></div><?php endif; ?>
        </div>
    </main>
<?php require_once BASE_PATH.'/app/views/layouts/footer.php'; ?>