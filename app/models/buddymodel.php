<?php
class BuddyModel extends Model {

    public function getAll(): array {
        return $this->db->query("SELECT * FROM buddies")->fetchAll();
    }

    public function getLimit(int $n): array {
        return $this->db->query("SELECT * FROM buddies LIMIT {$n}")->fetchAll();
    }

    public function getConnectedIds(int $userId): array {
        $s = $this->db->prepare("SELECT buddy_id FROM user_buddies WHERE user_id=?");
        $s->execute([$userId]);
        return array_column($s->fetchAll(), 'buddy_id');
    }

    public function isConnected(int $userId, int $buddyId): bool {
        $s = $this->db->prepare("SELECT COUNT(*) FROM user_buddies WHERE user_id=? AND buddy_id=?");
        $s->execute([$userId, $buddyId]);
        return (bool)$s->fetchColumn();
    }

    public function connect(int $userId, int $buddyId): void {
        $this->db->prepare("INSERT INTO user_buddies (user_id,buddy_id) VALUES (?,?)")
                 ->execute([$userId, $buddyId]);
    }
}