<?php
class GoalModel extends Model {

    public function getByUser(int $userId): array {
        $s = $this->db->prepare("SELECT * FROM goals WHERE user_id=? ORDER BY created_at DESC");
        $s->execute([$userId]);
        return $s->fetchAll();
    }

    public function create(int $userId, string $icon, string $title, string $sub, string $target, ?string $due): int {
        $this->db->prepare("
            INSERT INTO goals (user_id,icon,title,sub,current,target,pct,due_date,badge,accent)
            VALUES (?,?,?,?,'0',?,0,?,'active','var(--green)')
        ")->execute([$userId, $icon, $title, $sub, $target, $due]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $goalId, int $userId): void {
        $this->db->prepare("DELETE FROM goals WHERE id=? AND user_id=?")
                 ->execute([$goalId, $userId]);
    }
}