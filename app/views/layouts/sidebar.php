<?php
function navActive(string $page, string $active): string {
    return $page === $active ? 'active' : '';
}
?>
<nav class="sidebar">
    <a class="dashboardTitle" href="<?= BASE_URL ?>/dashboard">🏋️ PROFIT</a>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= navActive('dashboard',$activePage) ?>" href="<?= BASE_URL ?>/dashboard"><i class="bi bi-house"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?= navActive('buddies',$activePage) ?>"   href="<?= BASE_URL ?>/buddies"><i class="bi bi-people"></i> Buddies</a></li>
        <li class="nav-item"><a class="nav-link <?= navActive('progress',$activePage) ?>"  href="<?= BASE_URL ?>/progress"><i class="bi bi-graph-up"></i> Progress</a></li>
        <li class="nav-item"><a class="nav-link <?= navActive('goals',$activePage) ?>"     href="<?= BASE_URL ?>/goals"><i class="bi bi-bullseye"></i> Goals</a></li>
        <li class="nav-item"><a class="nav-link <?= navActive('book',$activePage) ?>"      href="<?= BASE_URL ?>/book"><i class="bi bi-calendar-plus"></i> Book Session</a></li>
        <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
        <li class="nav-item mt-3"><a class="nav-link <?= navActive('admin',$activePage) ?>" href="<?= BASE_URL ?>/admin"><i class="bi bi-gear"></i> Admin Panel</a></li>
        <?php endif; ?>
    </ul>
    <a href="<?= BASE_URL ?>/auth/logout"><button class="logout-btn">Logout</button></a>
</nav>