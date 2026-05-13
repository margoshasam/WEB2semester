<?php
require_once(__DIR__ . '/Database.php');

class GroupsTable
{
    // Получить все группы
    public static function getAllGroups()
    {
        $query = Database::prepare('SELECT * FROM `groups` ORDER BY id');
        $query->execute();
        return $query->fetchAll();
    }
    
    // Получить группу по ID
    public static function getGroupById($id)
    {
        $query = Database::prepare('SELECT * FROM `groups` WHERE id = :id');
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch();
    }
    
    // Найти первый свободный ID
    public static function getFirstFreeId()
    {
        // Получаем все существующие ID
        $query = Database::prepare('SELECT id FROM `groups` ORDER BY id');
        $query->execute();
        $existing_ids = $query->fetchAll(PDO::FETCH_COLUMN);
        
        // Ищем первый свободный ID
        $expected_id = 1;
        foreach ($existing_ids as $existing_id) {
            if ($existing_id > $expected_id) {
                // Нашли пропуск
                return $expected_id;
            }
            $expected_id++;
        }
        
        // Если нет пропусков, возвращаем следующий по порядку
        return $expected_id;
    }
    
    // Добавить группу
    public static function addGroup($data)
    {
        // Получаем первый свободный ID
        $new_id = self::getFirstFreeId();
        
        $query = Database::prepare('INSERT INTO `groups` (id, name_group) VALUES (:id, :name_group)');
        $query->bindValue(":id", $new_id, PDO::PARAM_INT);
        $query->bindValue(":name_group", $data['name_group']);
        
        if ($query->execute()) {
            return ['success' => true, 'data' => ['id' => $new_id], 'error' => null];
        } else {
            return ['success' => false, 'data' => null, 'error' => 'Ошибка при добавлении группы'];
        }
    }
    
    // Обновить группу
    public static function updateGroup($id, $data)
    {
        $query = Database::prepare('UPDATE `groups` SET name_group = :name_group WHERE id = :id');
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->bindValue(":name_group", $data['name_group']);
        
        if ($query->execute()) {
            return ['success' => true, 'data' => null, 'error' => null];
        } else {
            return ['success' => false, 'data' => null, 'error' => 'Ошибка при обновлении группы'];
        }
    }
    
    // Удалить группу
    public static function deleteGroup($id)
    {
        // Проверяем, есть ли дети в этой группе
        $query = Database::prepare('SELECT COUNT(*) as count FROM children WHERE id_group = :id');
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();
        
        if ($result['count'] > 0) {
            return ['success' => false, 'data' => null, 'error' => 'Нельзя удалить группу, в которой есть дети'];
        }
        
        $query = Database::prepare('DELETE FROM `groups` WHERE id = :id');
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        
        if ($query->execute()) {
            return ['success' => true, 'data' => null, 'error' => null];
        } else {
            return ['success' => false, 'data' => null, 'error' => 'Ошибка при удалении группы'];
        }
    }
    
    // Проверить существование группы
    public static function groupExists($id_group)
    {
        $query = Database::prepare('SELECT 1 FROM `groups` WHERE id = :id_group');
        $query->bindValue(":id_group", $id_group, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch() !== false;
    }
    
    // Переиндексировать ID групп
    public static function reindexIds()
    {
        $query = Database::prepare('SELECT * FROM `groups` ORDER BY id');
        $query->execute();
        $groups = $query->fetchAll();
        
        // Транзакция
        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        
        try {
            // Очищаем таблицу
            $query = Database::prepare('DELETE FROM `groups`');
            $query->execute();
            
            // Вставляем заново с новыми ID
            $new_id = 1;
            foreach ($groups as $group) {
                $query = Database::prepare('INSERT INTO `groups` (id, name_group) VALUES (:id, :name_group)');
                $query->bindValue(":id", $new_id, PDO::PARAM_INT);
                $query->bindValue(":name_group", $group['name_group']);
                $query->execute();
                $new_id++;
            }
            
            $pdo->commit();
            return ['success' => true, 'data' => null, 'error' => null];
        } catch (Exception $e) {
            $pdo->rollBack();
            return ['success' => false, 'data' => null, 'error' => 'Ошибка при переиндексации: ' . $e->getMessage()];
        }
    }
}
?>