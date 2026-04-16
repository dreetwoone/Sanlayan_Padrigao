<?php
require_once BASE_PATH . '/app/models/sessionmodel.php';

class ProgressController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $m = new SessionModel(); $uid = $_SESSION['user_id'];
        $this->view('progress/index', [
            'totalSessions' => $m->countByUser($uid),
            'totalCalories' => $m->totalCaloriesByUser($uid),
            'streak'        => $m->activeDaysByUser($uid),
            'avgDuration'   => $m->avgDurationByUser($uid),
            'weeklyData'    => $m->weeklyByUser($uid),
            'records'       => $m->recordsByUser($uid),
            'activityLog'   => $m->recentByUser($uid),
        ]);
    }
}