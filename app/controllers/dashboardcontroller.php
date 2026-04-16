<?php
require_once BASE_PATH . '/app/models/sessionmodel.php';
require_once BASE_PATH . '/app/models/buddymodel.php';

class DashboardController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $uid = $_SESSION['user_id'];
        $sm  = new SessionModel();
        $this->view('dashboard/index', [
            'sessionCount'  => $sm->countByUser($uid),
            'totalCalories' => $sm->totalCaloriesByUser($uid),
            'activeDays'    => $sm->activeDaysByUser($uid),
            'buddies'       => (new BuddyModel())->getLimit(3),
        ]);
    }
}