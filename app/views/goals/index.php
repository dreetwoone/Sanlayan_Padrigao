<?php $pageTitle='Goals'; $activePage='goals';
require_once BASE_PATH.'/app/views/layouts/header.php';
require_once BASE_PATH.'/app/views/layouts/sidebar.php';
$bl=['active'=>'In Progress','close'=>'Almost There!','done'=>'Completed']; ?>
    <main class="main-content flex-grow-1 goals-page">
        <header class="topbar d-flex justify-content-between align-items-center">
            <h5>Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></h5>
        </header>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3"><div class="goal-summary"><div><div class="num text-light"><?= $total ?></div><div class="lbl">Total Goals</div></div></div></div>
            <div class="col-6 col-md-3"><div class="goal-summary"><div><div class="num text-info"><?= $done ?></div><div class="lbl">Completed</div></div></div></div>
            <div class="col-6 col-md-3"><div class="goal-summary"><div><div class="num text-success"><?= $active ?></div><div class="lbl">In Progress</div></div></div></div>
            <div class="col-6 col-md-3"><div class="goal-summary"><div><div class="num text-warning"><?= $close ?></div><div class="lbl">Almost There</div></div></div></div>
        </div>
        <p class="section-heading">Active Goals</p>
        <div class="row g-3 mb-4" id="goalGrid">
            <?php foreach($goals as $g): $due=$g['due_date']?date('M j, Y',strtotime($g['due_date'])):'No deadline'; ?>
            <div class="col-md-4" id="goal-<?= $g['id'] ?>">
                <div class="goal-card" style="--accent-color:<?= htmlspecialchars($g['accent']) ?>">
                    <div class="goal-header"><div><div class="goal-icon"><?= $g['icon'] ?></div><div class="goal-title"><?= htmlspecialchars($g['title']) ?></div><div class="goal-sub"><?= htmlspecialchars($g['sub']) ?></div></div><span class="goal-badge badge-<?= $g['badge'] ?>"><?= $bl[$g['badge']]??$g['badge'] ?></span></div>
                    <div class="goal-nums"><div class="goal-current"><?= htmlspecialchars($g['current']) ?></div><div class="goal-target">/ <?= htmlspecialchars($g['target']) ?></div><div class="goal-pct"><?= $g['pct'] ?>%</div></div>
                    <div class="progress-track"><div class="progress-fill" style="width:<?= $g['pct'] ?>%"></div></div>
                    <div class="goal-footer"><span class="goal-due"><i class="bi bi-calendar3 me-1"></i>Due <?= $due ?></span><button class="goal-edit" onclick="delGoal(<?= $g['id'] ?>)"><i class="bi bi-trash3"></i></button></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if(empty($goals)): ?><div class="col-12"><p style="color:var(--text-muted);font-size:13px">No goals yet. Add one below!</p></div><?php endif; ?>
        </div>
        <p class="section-heading mt-4">Add New Goal</p>
        <div class="add-goal-card">
            <div class="row g-3">
                <div class="col-md-4"><div class="form-label-sm">Goal Type</div><select class="input-dark" id="goalType"><option>Weight Loss</option><option>Strength</option><option>Cardio / Endurance</option><option>Sessions per Week</option><option>Active Days</option><option>Custom</option></select></div>
                <div class="col-md-4"><div class="form-label-sm">Goal Name</div><input class="input-dark" id="goalName" type="text" placeholder="e.g. Run 5K in under 25 min"></div>
                <div class="col-md-2"><div class="form-label-sm">Target</div><input class="input-dark" id="goalTarget" type="text" placeholder="e.g. 80 kg"></div>
                <div class="col-md-2"><div class="form-label-sm">Due Date</div><input class="input-dark" id="goalDue" type="date"></div>
                <div class="col-12 d-flex justify-content-end"><button class="btn-profit" onclick="addGoal()"><i class="bi bi-plus-circle me-2"></i>Add Goal</button></div>
            </div>
        </div>
    </main>
<?php require_once BASE_PATH.'/app/views/layouts/footer.php'; ?>
<script>
const B='<?= BASE_URL ?>';
async function addGoal(){
    const name=document.getElementById('goalName').value.trim();
    if(!name){alert('Please enter a goal name.');return;}
    const r=await fetch(B+'/goals/add',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({title:name,type:document.getElementById('goalType').value,target:document.getElementById('goalTarget').value,due:document.getElementById('goalDue').value})});
    const d=await r.json();
    if(d.success)location.reload(); else alert(d.message);
}
async function delGoal(id){
    if(!confirm('Delete this goal?'))return;
    const r=await fetch(B+'/goals/delete/'+id,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({})});
    const d=await r.json();
    if(d.success){const c=document.getElementById('goal-'+id);if(c)c.remove();}
}
</script>