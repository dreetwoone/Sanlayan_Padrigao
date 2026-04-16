<?php
require_once BASE_PATH . '/app/models/buddyModel.php';
require_once BASE_PATH . '/app/models/workoutModel.php';
require_once BASE_PATH . '/app/models/bookingModel.php';

class BookController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $this->view('book/index', [
            'buddies'  => (new BuddyModel())->getAll(),
            'workouts' => (new WorkoutModel())->getAll(),
        ]);
    }
    public function save(): void {
        $this->requireLogin();
        $d = json_decode(file_get_contents('php://input'), true);
        $buddyId   = (int)($d['buddy_id']   ?? 0);
        $workoutId = (int)($d['workout_id']  ?? 0);
        $date      = $d['date'] ?? '';
        $time      = $d['time'] ?? '';
        if (!$buddyId || !$workoutId || !$date || !$time) {
            $this->json(['success'=>false,'message'=>'All fields are required.']);
        }
        (new BookingModel())->create($_SESSION['user_id'], $buddyId, $workoutId, $date, $time, trim($d['notes']??''));
        $this->json(['success'=>true]);
    }
}