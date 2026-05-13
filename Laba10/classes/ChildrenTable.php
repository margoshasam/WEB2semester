<?php

require_once(__DIR__ . '/Database.php');

class ChildrenTable
{
    // Получить всех детей
    public static function getAllChildren()
    {
        $query = Database::prepare('SELECT * FROM children ORDER BY id DESC');
        $query->execute();
        return $query->fetchAll();
    }
    
    // Получить детей по группам
    public static function getChildrenByGroups($group_id)
    {
        $query = Database::prepare('SELECT * FROM children WHERE id_group = :group_id ORDER BY id DESC');
        $query->bindValue(":group_id", $group_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll();
    }
    
    // Получить ребёнка по ID
    public static function getChildrenById($id)
    {
        $query = Database::prepare('SELECT * FROM children WHERE id = :id');
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch();
    }
    
    // Найти первый свободный ID для детей
    public static function getFirstFreeId()
    {
        // Получаем все существующие ID детей
        $query = Database::prepare('SELECT id FROM children ORDER BY id');
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
    
    // Добавить ребёнка
    public static function addChild($data)
    {
        // Получаем первый свободный ID
        $new_id = self::getFirstFreeId();
        
        $query = Database::prepare(
            'INSERT INTO children (id, img_path, name, id_group, bio, year_of_birth) ' .
            'VALUES (:id, :img_path, :name, :id_group, :bio, :year_of_birth)'
        );
        
        $query->bindValue(":id", $new_id, PDO::PARAM_INT);
        $query->bindValue(":img_path", $data['img_path']);
        $query->bindValue(":name", $data['name']);
        $query->bindValue(":id_group", $data['id_group'], PDO::PARAM_INT);
        $query->bindValue(":bio", $data['bio']);
        $query->bindValue(":year_of_birth", $data['year_of_birth'], PDO::PARAM_INT);

        if ($query->execute()) {
            return ['success' => true, 'data' => ['id' => $new_id], 'error' => null];
        } else {
            return ['success' => false, 'data' => null, 'error' => 'Ошибка при добавлении ребёнка'];
        }
    } 
    
    // Обновить ребёнка
    public static function updateChild($id, $data)
    {
        $old_child = self::getChildrenById($id);
        $img_path = $old_child['img_path'];
        
        // Путь к папке загрузок
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/Laba10/uploads/";
        
        // Если передан новый файл изображения
        if (!empty($data['img_path'])) {
            // Удаляем старое изображение, если оно существует
            if ($old_child['img_path'] && file_exists($uploadDir . $old_child['img_path'])) {
                unlink($uploadDir . $old_child['img_path']); 
            }
            $img_path = $data['img_path'];
        }
        
        $query = Database::prepare(
            'UPDATE children SET 
                img_path = :img_path,
                name = :name,
                id_group = :id_group,
                bio = :bio,
                year_of_birth = :year_of_birth
            WHERE id = :id'
        );
        
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->bindValue(":img_path", $img_path);
        $query->bindValue(":name", $data['name']);
        $query->bindValue(":id_group", $data['id_group'], PDO::PARAM_INT);
        $query->bindValue(":bio", $data['bio']);
        $query->bindValue(":year_of_birth", $data['year_of_birth'], PDO::PARAM_INT);

        if ($query->execute()) {
            return ['success' => true, 'data' => null, 'error' => null];
        } else {
            return ['success' => false, 'data' => null, 'error' => 'Ошибка при обновлении ребёнка'];
        }
    }
    
    // Удалить ребёнка
    public static function deleteChild($id)
    {
        $child = self::getChildrenById($id);
        
        // Путь к папке загрузок
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/LR3_2semestr/uploads/";
        
        // Удаляем изображение, если оно существует
        if ($child && $child['img_path']) {
            if (file_exists($uploadDir . $child['img_path'])) {
                unlink($uploadDir . $child['img_path']);
            }
        }
        
        $query = Database::prepare('DELETE FROM children WHERE id = :id');
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        
        if ($query->execute()) {
            return ['success' => true, 'data' => null, 'error' => null];
        } else {
            return ['success' => false, 'data' => null, 'error' => 'Ошибка при удалении ребёнка'];
        }
    }
    
    // Переиндексировать ID детей (если нужно принудительно)
    public static function reindexIds()
    {
        // Получаем всех детей в порядке возрастания ID
        $query = Database::prepare('SELECT * FROM children ORDER BY id');
        $query->execute();
        $children = $query->fetchAll();
        
        // Начинаем транзакцию
        $pdo = Database::connection();
        $pdo->beginTransaction();
        
        try {
            // Очищаем таблицу
            $query = Database::prepare('DELETE FROM children');
            $query->execute();
            
            // Вставляем заново с новыми ID
            $new_id = 1;
            foreach ($children as $child) {
                $query = Database::prepare(
                    'INSERT INTO children (id, img_path, name, id_group, bio, year_of_birth) ' .
                    'VALUES (:id, :img_path, :name, :id_group, :bio, :year_of_birth)'
                );
                
                $query->bindValue(":id", $new_id, PDO::PARAM_INT);
                $query->bindValue(":img_path", $child['img_path']);
                $query->bindValue(":name", $child['name']);
                $query->bindValue(":id_group", $child['id_group'], PDO::PARAM_INT);
                $query->bindValue(":bio", $child['bio']);
                $query->bindValue(":year_of_birth", $child['year_of_birth'], PDO::PARAM_INT);
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