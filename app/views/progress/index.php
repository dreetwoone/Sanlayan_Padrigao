<?php $pageTitle='Progress'; $activePage='progress';
require_once BASE_PATH.'/app/views/layouts/header.php';
require_once BASE_PATH.'/app/views/layouts/sidebar.php';
function friendlyDate(string $d): string {
    $diff=(new DateTime('today'))->diff(new DateTime($d))->days;
    if($diff===0)return'Today'; if($diff===1)return'Yesterday';
    return(new DateTime($d))->format('M j');
} ?>
    <main class="main-content flex-grow-1">
        <header class="topbar d-flex justify-content-between align-items-center">
            <h5>Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></h5>
        </header>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3"><div class="stat-mini"><div class="label">Total Sessions</div><div class="value"><?= $totalSessions ?></div><div class="delta text-success"><i class="bi bi-activity"></i> all time</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-mini"><div class="label">Calories Burned</div><div class="value"><?= number_format($totalCalories) ?></div><div class="delta text-success"><i class="bi bi-fire"></i> total</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-mini"><div class="label">Active Streak</div><div class="value"><?= $streak ?> <span class="value-unit">days</span></div><div class="delta text-warning"><i class="bi bi-lightning-fill"></i> keep it up!</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-mini"><div class="label">Avg. Duration</div><div class="value"><?= $avgDuration ?> <span class="value-unit">min</span></div><div class="delta text-success"><i class="bi bi-clock"></i> per session</div></div></div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-7">
                <div class="chart-card h-100">
                    <div class="chart-title">Sessions — Last 7 Weeks</div>
                    <?php if(empty($weeklyData)): ?><p style="color:var(--text-muted);font-size:13px;margin:0">No sessions yet. <a href="<?= BASE_URL ?>/book" style="color:var(--green)">Book one!</a></p>
                    <?php else: ?><div class="bar-chart" id="barChart" data-weeks='<?= json_encode($weeklyData) ?>'></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-5">
                <div class="chart-card h-100">
                    <div class="chart-title">Workout Records</div>
                    <?php if(empty($records)): ?><p style="color:var(--text-muted);font-size:13px;margin:0">Complete sessions to see records.</p>
                    <?php else:
                        $maxD=max(array_column($records,'best_duration'));
                        $cols=['var(--green)','var(--cyan)','var(--orange)','#4f9eff','#b794f4'];
                        foreach($records as $i=>$pr): $pct=$maxD>0?round(($pr['best_duration']/$maxD)*100):0; ?>
                        <div class="pr-row"><div class="pr-name"><?= htmlspecialchars($pr['name']) ?></div><div class="pr-right"><div class="pr-bar-wrap"><div class="pr-bar-fill" style="width:<?= $pct ?>%;background:<?= $cols[$i%5] ?>"></div></div><div class="pr-val"><?= $pr['best_duration'] ?> min</div></div></div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
        <div class="chart-card">
            <div class="chart-title">Recent Activity</div>
            <?php if(empty($activityLog)): ?><p style="color:var(--text-muted);font-size:13px;margin:0">No sessions logged yet.</p>
            <?php else:
                $bgMap=['Strength Training'=>'rgba(12,209,62,.12)','Cardio'=>'rgba(0,212,170,.12)','HIIT'=>'rgba(255,107,43,.12)','Cycling'=>'rgba(79,158,255,.12)','Yoga & Stretch'=>'rgba(122,122,122,.12)','Boxing'=>'rgba(255,107,43,.12)'];
                foreach($activityLog as $log):
                    $bg=$bgMap[$log['workout_name']]??'rgba(122,122,122,.12)';
                    $meta=friendlyDate($log['session_date']).' · '.$log['duration'].' min'.($log['buddy_name']?' · with '.htmlspecialchars($log['buddy_name']):''); ?>
                <div class="log-row">
                    <div class="log-icon" style="background:<?= $bg ?>"><?= $log['workout_icon'] ?></div>
                    <div class="log-info"><div class="log-title"><?= htmlspecialchars($log['workout_name']) ?></div><div class="log-meta"><?= $meta ?></div></div>
                    <div class="log-stat"><?= number_format($log['calories']) ?> kcal</div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </main>
<?php require_once BASE_PATH.'/app/views/layouts/footer.php'; ?>
<script>
const el=document.getElementById('barChart');
if(el){const weeks=JSON.parse(el.dataset.weeks),maxV=Math.max(...weeks.map(w=>parseInt(w.total)))||1;
el.innerHTML=weeks.map((w,i)=>`<div class="bar-col"><div class="bar-val">${w.total}</div><div class="bar-wrap" style="height:110px"><div class="bar green" style="height:${Math.round(w.total/maxV*100)}%"></div></div><div class="bar-label">W${i+1}</div></div>`).join('');}
</script>