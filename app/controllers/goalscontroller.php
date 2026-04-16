<?php
require_once BASE_PATH . '/app/models/goalmodel.php';

class GoalsController extends Controller {
    public function index(): void {
        $this->requireLogin();
        $goals = (new GoalModel())->getByUser($_SESSION['user_id']);
        $this->view('goals/index', [
            'goals'  => $goals,
            'total'  => count($goals),
            'done'   => count(array_filter($goals, fn($g)=>$g['badge']==='done')),
            'active' => count(array_filter($goals, fn($g)=>$g['badge']==='active')),
            'close'  => count(array_filter($goals, fn($g)=>$g['badge']==='close')),
        ]);
    }
    public function add(): void {
        $this->requireLogin();
        $d = json_decode(file_get_contents('php://input'), true);
        $title = trim($d['title'] ?? '');
        if (empty($title)) { $this->json(['success'=>false,'message'=>'Goal name is required.']); }
        $icons = ['Weight Loss'=>'⚖️','Strength'=>'🏋️','Cardio / Endurance'=>'🏃','Sessions per Week'=>'📅','Active Days'=>'🔥','Custom'=>'🎯'];
        $type  = $d['type'] ?? 'Custom';
        $id = (new GoalModel())->create($_SESSION['user_id'], $icons[$type]??'🎯', $title, $type, trim($d['target']??''), !empty($d['due'])?$d['due']:null);
        $this->json(['success'=>true,'id'=>$id]);
    }
    public function delete(string $id): void {
        $this->requireLogin();
        (new GoalModel())->delete((int)$id, $_SESSION['user_id']);
        $this->json(['success'=>true]);
    }
}