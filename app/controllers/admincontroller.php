<?php
require_once BASE_PATH . '/app/models/userModel.php';
require_once BASE_PATH . '/app/models/bookingModel.php';
require_once BASE_PATH . '/app/models/sessionModel.php';
require_once BASE_PATH . '/app/models/workoutModel.php';

class AdminController extends Controller {

    private UserModel $users;
    private BookingModel $bookings;
    private SessionModel $sessions;
    private WorkoutModel $workouts;

    public function __construct() {
        $this->users    = new UserModel();
        $this->bookings = new BookingModel();
        $this->sessions = new SessionModel();
        $this->workouts = new WorkoutModel();
    }

    public function index(): void {
        $this->requireAdmin();
        $this->view('admin/index', [
            'totalUsers'    => $this->users->count(),
            'activeToday'   => $this->sessions->countActiveToday(),
            'bookingsToday' => $this->bookings->countToday(),
            'avgDuration'   => $this->sessions->avgDurationAll(),
            'allUsers'      => $this->users->getAll(),
            'allBookings'   => $this->bookings->getAll(),
            'allSessions'   => $this->sessions->getAll(20),
            'workoutStats'  => $this->workouts->getPopularity(),
        ]);
    }

    public function togglestatus(): void {
        $this->requireAdmin();
        $d = json_decode(file_get_contents('php://input'), true);
        $uid = (int)($d['id'] ?? 0);
        if ($uid === $_SESSION['user_id']) { $this->json(['success'=>false,'message'=>"You can't ban yourself."]); }
        $this->json(['success'=>true,'new_status'=>$this->users->toggleStatus($uid)]);
    }

    public function changerole(): void {
        $this->requireAdmin();
        $d    = json_decode(file_get_contents('php://input'), true);
        $uid  = (int)($d['id'] ?? 0);
        $role = $d['role'] ?? '';
        if ($uid === $_SESSION['user_id'])                    { $this->json(['success'=>false,'message'=>"Can't change your own role."]); }
        if (!in_array($role, ['member','trainer','admin']))   { $this->json(['success'=>false,'message'=>'Invalid role.']); }
        $this->users->updateRole($uid, $role);
        $this->json(['success'=>true,'new_role'=>$role]);
    }

    public function deleteuser(): void {
        $this->requireAdmin();
        $d = json_decode(file_get_contents('php://input'), true);
        $uid = (int)($d['id'] ?? 0);
        if ($uid === $_SESSION['user_id']) { $this->json(['success'=>false,'message'=>"Can't delete yourself."]); }
        $this->users->delete($uid);
        $this->json(['success'=>true]);
    }

    public function adduser(): void {
        $this->requireAdmin();
        $d    = json_decode(file_get_contents('php://input'), true);
        $name = trim($d['name'] ?? ''); $email = trim($d['email'] ?? '');
        if (empty($name) || empty($email))    { $this->json(['success'=>false,'message'=>'Name and email required.']); }
        if ($this->users->nameExists($name))  { $this->json(['success'=>false,'message'=>'Username already exists.']); }
        $hashed = password_hash($d['password'] ?? 'changeme123', PASSWORD_DEFAULT);
        $id = $this->users->create($name, $email, $hashed);
        $this->users->updateRole($id, $d['role'] ?? 'member');
        $this->users->updateStatus($id, $d['status'] ?? 'active');
        $this->json(['success'=>true,'id'=>$id]);
    }

    public function updatebooking(): void {
        $this->requireAdmin();
        $d = json_decode(file_get_contents('php://input'), true);
        $this->bookings->updateStatus((int)($d['id']??0), $d['status']??'pending');
        $this->json(['success'=>true]);
    }
}