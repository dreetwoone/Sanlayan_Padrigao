<?php $pageTitle='Book Session'; $activePage='book';
require_once BASE_PATH.'/app/views/layouts/header.php';
require_once BASE_PATH.'/app/views/layouts/sidebar.php'; ?>
    <main class="main-content flex-grow-1">
        <header class="topbar d-flex justify-content-between align-items-center">
            <h5>Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></h5>
        </header>
        <div class="steps-bar">
            <div class="step-tab active" id="st1"><div class="step-num" id="sn1">1</div>Trainer</div>
            <div class="step-tab" id="st2"><div class="step-num" id="sn2">2</div>Workout</div>
            <div class="step-tab" id="st3"><div class="step-num" id="sn3">3</div>Date &amp; Time</div>
            <div class="step-tab" id="st4"><div class="step-num" id="sn4">4</div>Confirm</div>
        </div>
        <div id="panel1">
            <p class="section-heading">Choose a Trainer / Buddy</p>
            <div class="row g-3 mb-4">
                <?php foreach($buddies as $b): ?>
                <div class="col-md-4"><div class="trainer-card" onclick="pickTrainer(this,<?= $b['id'] ?>,'<?= htmlspecialchars($b['name'],ENT_QUOTES) ?>')"><div class="t-avatar"><div class="profile-img d-flex align-items-center justify-content-center" 
     style="background:var(--bg-3); width:50px; height:50px; border-radius:50%; overflow:hidden; flex-shrink:0;">
    
    <?php if (!empty($b['avatar']) && strpos($b['avatar'], '.') !== false): ?>
        <img src="<?= BASE_URL ?>/app/uploads/<?= htmlspecialchars($b['avatar']) ?>" 
             style="width: 100%; height: 100%; object-fit: cover;">
    <?php else: ?>
        <span style="font-size:20px;"><?= $b['avatar'] ?></span>
    <?php endif; ?>

</div></div><div><div class="t-name"><?= htmlspecialchars($b['name']) ?></div><div class="t-tag <?= htmlspecialchars($b['tag_color']) ?>"><?= htmlspecialchars($b['tag']) ?></div></div><div class="t-check"><i class="bi bi-check"></i></div></div></div>
                <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-end"><button class="btn-profit" onclick="goStep(2)">Next <i class="bi bi-arrow-right ms-1"></i></button></div>
        </div>
        <div id="panel2" class="hidden">
            <p class="section-heading">Choose Workout Type</p>
            <div class="row g-3 mb-4">
                <?php foreach($workouts as $w): ?>
                <div class="col-6 col-md-2"><div class="workout-card" onclick="pickWorkout(this,<?= $w['id'] ?>,'<?= htmlspecialchars($w['name'],ENT_QUOTES) ?>','<?= htmlspecialchars($w['duration'],ENT_QUOTES) ?>')"><div class="w-icon"><?= $w['icon'] ?></div><div class="w-name"><?= htmlspecialchars($w['name']) ?></div><div class="w-dur"><?= htmlspecialchars($w['duration']) ?></div></div></div>
                <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-end gap-2"><button class="btn-ghost" onclick="goStep(1)"><i class="bi bi-arrow-left me-1"></i>Back</button><button class="btn-profit" onclick="goStep(3)">Next <i class="bi bi-arrow-right ms-1"></i></button></div>
        </div>
        <div id="panel3" class="hidden">
            <p class="section-heading">Pick a Date</p>
            <div class="cal-strip" id="calStrip"></div>
            <p class="section-heading">Pick a Time</p>
            <div class="time-grid mb-4">
                <div class="time-slot busy">6:00 AM</div>
                <div class="time-slot" onclick="pickTime(this,'7:00 AM')">7:00 AM</div>
                <div class="time-slot" onclick="pickTime(this,'8:00 AM')">8:00 AM</div>
                <div class="time-slot busy">9:00 AM</div>
                <div class="time-slot" onclick="pickTime(this,'10:00 AM')">10:00 AM</div>
                <div class="time-slot" onclick="pickTime(this,'11:00 AM')">11:00 AM</div>
                <div class="time-slot busy">12:00 PM</div>
                <div class="time-slot" onclick="pickTime(this,'1:00 PM')">1:00 PM</div>
                <div class="time-slot" onclick="pickTime(this,'2:00 PM')">2:00 PM</div>
                <div class="time-slot" onclick="pickTime(this,'4:00 PM')">4:00 PM</div>
                <div class="time-slot" onclick="pickTime(this,'5:00 PM')">5:00 PM</div>
                <div class="time-slot" onclick="pickTime(this,'6:00 PM')">6:00 PM</div>
            </div>
            <p class="section-heading">Notes <span class="section-heading-opt">(optional)</span></p>
            <textarea class="notes-input mb-4" id="notesInput" rows="3" placeholder="e.g. Focus on upper body..."></textarea>
            <div class="d-flex justify-content-end gap-2"><button class="btn-ghost" onclick="goStep(2)"><i class="bi bi-arrow-left me-1"></i>Back</button><button class="btn-profit" onclick="goStep(4)">Review <i class="bi bi-arrow-right ms-1"></i></button></div>
        </div>
        <div id="panel4" class="hidden">
            <p class="section-heading">Review Your Booking</p>
            <div class="book-card mb-4">
                <div class="summary-row"><span class="s-label">Trainer / Buddy</span><span class="s-value" id="s-trainer">—</span></div>
                <div class="summary-row"><span class="s-label">Workout Type</span><span class="s-value" id="s-workout">—</span></div>
                <div class="summary-row"><span class="s-label">Duration</span><span class="s-value" id="s-dur">—</span></div>
                <div class="summary-row"><span class="s-label">Date</span><span class="s-value" id="s-date">—</span></div>
                <div class="summary-row"><span class="s-label">Time</span><span class="s-value" id="s-time">—</span></div>
                <div class="summary-row hidden" id="s-notes-row"><span class="s-label">Notes</span><span class="s-value s-notes-val" id="s-notes">—</span></div>
            </div>
            <div class="d-flex justify-content-end gap-2"><button class="btn-ghost" onclick="goStep(3)"><i class="bi bi-arrow-left me-1"></i>Back</button><button class="btn-profit" id="confirmBtn" onclick="saveBooking()"><i class="bi bi-check-circle me-1"></i>Confirm Booking</button></div>
        </div>
        <div id="panelDone" class="hidden">
            <div class="confirm-box"><div class="confirm-icon">✅</div><div class="confirm-title">Session Booked!</div><div class="confirm-sub">Your appointment has been saved. See you at the gym!</div><div class="confirm-pill" id="confirmPill"></div><br><button class="btn-ghost" onclick="resetForm()"><i class="bi bi-plus-circle me-1"></i>Book Another</button></div>
        </div>
    </main>
<?php require_once BASE_PATH.'/app/views/layouts/footer.php'; ?>
<script>
const B='<?= BASE_URL ?>';
var sel={buddy_id:null,workout_id:null,trainer:'',workout:'',dur:'',date:'',rawDate:'',time:''};
function goStep(n){for(var i=1;i<=4;i++){document.getElementById('panel'+i).classList.add('hidden');var t=document.getElementById('st'+i),s=document.getElementById('sn'+i);t.classList.remove('active','done');if(i<n){t.classList.add('done');s.innerHTML='<i class="bi bi-check"></i>';}else if(i===n){t.classList.add('active');s.textContent=i;}else{s.textContent=i;}}document.getElementById('panelDone').classList.add('hidden');document.getElementById('panel'+n).classList.remove('hidden');if(n===4)fillSummary();}
function pickTrainer(el,id,name){document.querySelectorAll('.trainer-card').forEach(c=>c.classList.remove('selected'));el.classList.add('selected');sel.buddy_id=id;sel.trainer=name;}
function pickWorkout(el,id,name,dur){document.querySelectorAll('.workout-card').forEach(c=>c.classList.remove('selected'));el.classList.add('selected');sel.workout_id=id;sel.workout=name;sel.dur=dur;}
function pickTime(el,t){document.querySelectorAll('.time-slot:not(.busy)').forEach(c=>c.classList.remove('selected'));el.classList.add('selected');sel.time=t;}
(function(){const strip=document.getElementById('calStrip'),days=['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],months=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],today=new Date();
for(let i=0;i<12;i++){const d=new Date(today);d.setDate(today.getDate()+i);const el=document.createElement('div');el.className='cal-day'+(i===0?' today':'');el.innerHTML=`<div class="cal-dname">${days[d.getDay()]}</div><div class="cal-date">${d.getDate()}</div>${i%3===1?'<div class="cal-dot"></div>':''}`;const lbl=`${days[d.getDay()]}, ${months[d.getMonth()]} ${d.getDate()}`,raw=`${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;el.onclick=()=>{document.querySelectorAll('.cal-day').forEach(c=>c.classList.remove('selected'));el.classList.add('selected');sel.date=lbl;sel.rawDate=raw;};strip.appendChild(el);}})();
function fillSummary(){['trainer','workout','dur','date','time'].forEach(k=>document.getElementById('s-'+k).textContent=sel[k]||'—');const n=document.getElementById('notesInput').value.trim();if(n){document.getElementById('s-notes-row').classList.remove('hidden');document.getElementById('s-notes').textContent=n;}}
async function saveBooking(){const btn=document.getElementById('confirmBtn');btn.disabled=true;btn.textContent='Saving...';
try{const r=await fetch(B+'/book/save',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({buddy_id:sel.buddy_id,workout_id:sel.workout_id,date:sel.rawDate,time:sel.time,notes:document.getElementById('notesInput').value.trim()})});const d=await r.json();
if(d.success){for(let i=1;i<=4;i++){document.getElementById('panel'+i).classList.add('hidden');document.getElementById('st'+i).classList.add('done');document.getElementById('sn'+i).innerHTML='<i class="bi bi-check"></i>';}document.getElementById('panelDone').classList.remove('hidden');document.getElementById('confirmPill').textContent=sel.workout+' with '+sel.trainer+' · '+sel.date+' at '+sel.time;}else{alert('Error: '+d.message);btn.disabled=false;btn.innerHTML='<i class="bi bi-check-circle me-1"></i>Confirm Booking';}}catch(e){alert('Network error.');btn.disabled=false;btn.innerHTML='<i class="bi bi-check-circle me-1"></i>Confirm Booking';}}
function resetForm(){sel={buddy_id:null,workout_id:null,trainer:'',workout:'',dur:'',date:'',rawDate:'',time:''};document.querySelectorAll('.trainer-card,.workout-card,.cal-day,.time-slot').forEach(c=>c.classList.remove('selected'));document.getElementById('notesInput').value='';document.getElementById('s-notes-row').classList.add('hidden');document.getElementById('panelDone').classList.add('hidden');document.getElementById('confirmBtn').disabled=false;document.getElementById('confirmBtn').innerHTML='<i class="bi bi-check-circle me-1"></i>Confirm Booking';goStep(1);document.getElementById('panel1').classList.remove('hidden');}
</script>