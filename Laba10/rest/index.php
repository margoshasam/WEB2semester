<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/Laba10/vendor/autoload.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Laba10/classes/ChildrenTable.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Laba10/classes/GroupsTable.php";

$app = new Silex\Application();

//CORS
$app->after(function ($request, $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
});

// OPTIONS запросы 
$app->options("{anything}", function () use ($app) {
    return $app->json(null, 200);
})->assert('anything', '.*');

// ========== ГРУППЫ ==========
$app->get('/groups/list.json', function () use ($app) {
    $groups = GroupsTable::getAllGroups();
    return $app->json($groups);
});

$app->get('/groups/item.json', function () use ($app) {
    $groups = GroupsTable::getAllGroups();
    return $app->json($groups);
});

$app->post('/groups/add-item', function () use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = GroupsTable::addGroup(['name_group' => $data['name_group']]);
    return $app->json($result);
});

$app->post('/groups/update-item', function () use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = GroupsTable::updateGroup($data['id'], ['name_group' => $data['name_group']]);
    return $app->json($result);
});

$app->post('/groups/delete-item', function () use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = GroupsTable::deleteGroup($data['id']);
    return $app->json($result);
});

// ========== ДЕТИ ==========
$app->get('/children/list.json', function () use ($app) {
    $children = ChildrenTable::getAllChildren();
    return $app->json($children);
});

$app->get('/children/item.json', function () use ($app) {
    $children = ChildrenTable::getAllChildren();
    return $app->json($children);
});

// Фильтр по группам
$app->get('/children/list-filtered-{groupId}.json', function ($groupId) use ($app) {
    $children = ChildrenTable::getChildrenByGroups($groupId);
    return $app->json($children);
});

// Добавление ребёнка
$app->post('/children/add-item', function () use ($app) {
    $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/Laba10/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $img_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $img_path = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $img_path);
    }

    $data = [
        'img_path' => $img_path,
        'name' => $_POST['name'],
        'id_group' => $_POST['id_group'],
        'bio' => $_POST['bio'],
        'year_of_birth' => $_POST['year_of_birth']
    ];

    $result = ChildrenTable::addChild($data);
    return $app->json($result);
});

// Редактирование ребёнка
$app->post('/children/update-item', function () use ($app) {
    $id = $_POST['id'];

    $oldChild = ChildrenTable::getChildrenById($id);
    $img_path = $oldChild['img_path'];

    // Обработка новой картинки
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/Laba10/uploads/";
        if ($oldChild['img_path'] && file_exists($uploadDir . $oldChild['img_path'])) {
            unlink($uploadDir . $oldChild['img_path']);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $img_path = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $img_path);
    }

    // Удаление старой картинки если стоит галочка
    if (isset($_POST['delete_old_image']) && $_POST['delete_old_image'] == '1') {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/Laba10/uploads/";
        if ($oldChild['img_path'] && file_exists($uploadDir . $oldChild['img_path'])) {
            unlink($uploadDir . $oldChild['img_path']);
        }
        $img_path = null;
    }

    $data = [
        'img_path' => $img_path,
        'name' => $_POST['name'],
        'id_group' => $_POST['id_group'],
        'bio' => $_POST['bio'],
        'year_of_birth' => $_POST['year_of_birth']
    ];

    $result = ChildrenTable::updateChild($id, $data);
    return $app->json($result);
});

// Удаление ребёнка
$app->post('/children/delete-item', function () use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $child = ChildrenTable::getChildrenById($data['id']);
    if ($child && $child['img_path']) {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . "/Laba10/uploads/";
        if (file_exists($uploadDir . $child['img_path'])) {
            unlink($uploadDir . $child['img_path']);
        }
    }
    
    $result = ChildrenTable::deleteChild($data['id']);
    return $app->json($result);
});

$app->run();