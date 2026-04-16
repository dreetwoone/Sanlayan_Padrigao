<?php
require_once BASE_PATH . '/app/models/buddymodel.php';

class BuddiesController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $m = new BuddyModel();
        $this->view('buddies/index', [
            'buddies'    => $m->getAll(),
            'myBuddyIds' => $m->getConnectedIds($_SESSION['user_id']),
        ]);
    }
    public function add(): void {
        $this->requireLogin();
        $data    = json_decode(file_get_contents('php://input'), true);
        $buddyId = (int)($data['buddy_id'] ?? 0);
        $m       = new BuddyModel();
        if ($buddyId <= 0)                                    { $this->json(['success'=>false,'message'=>'Invalid buddy.']); }
        if ($m->isConnected($_SESSION['user_id'], $buddyId)) { $this->json(['success'=>false,'message'=>'Already connected!']); }
        $m->connect($_SESSION['user_id'], $buddyId);
        $this->json(['success'=>true]);
    }
}