<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'], $_POST['priority'])) {
    $taskName = $_POST['task'];
    $priority = $_POST['priority'];

    $_SESSION['tasks'][] = [
        'task' => $taskName,
        'priority' => $priority,
    ];
}

if (isset($_GET['delete'])) {
    $taskId = $_GET['delete'];
    unset($_SESSION['tasks'][$taskId]);
    $_SESSION['tasks'] = array_values($_SESSION['tasks']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_task'], $_POST['edit_priority'])) {
    $taskId = $_POST['task_id'];
    $updatedTask = $_POST['edit_task'];
    $updatedPriority = $_POST['edit_priority'];

    $_SESSION['tasks'][$taskId] = [
        'task' => $updatedTask,
        'priority' => $updatedPriority,
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List Sultan Faaiz</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto bg-white p-6 rounded-md shadow-md">
        <h2 class="text-2xl font-bold mb-4">Todo List</h2>

        <form action="" method="POST" class="mb-6 w-full">
            <div class="mb-4">
                <label for="task" class="block text-sm font-semibold text-gray-700">Task / Tugas</label>
                <input type="text" name="task" id="task" class="mt-1 p-2 w-full rounded-md shadow-md" required>
            </div>
            <div class="mb-4">
                <label for="priority" class="block text-sm font-semibold text-gray-700">Priority / Prioritas</label>
                <select name="priority" id="priority" class="mt-1 p-2 w-full rounded-md shadow-md" required>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-lg w-full">Add Task</button>
        </form>

        <ul>
            <?php if (!empty($_SESSION['tasks'])): ?>
                <?php foreach ($_SESSION['tasks'] as $key => $task): ?>
                    <li class="bg-gray-200 p-4 rounded-md mb-2 flex justify-between items-center">
                        <div>
                            <strong><?= isset($task['task']) ? htmlspecialchars($task['task']) : 'Unknown Task' ?></strong>
                            <span class="text-sm text-gray-600">
                                (Priority: <?= isset($task['priority']) ? htmlspecialchars($task['priority']) : 'Unknown Priority' ?>)
                            </span>
                        </div>
                        <div>
                            <a href="?delete=<?= $key ?>" class="text-red-500 mr-2">Delete</a>
                            <button class="text-blue-500" onclick="editTask(<?= $key ?>)">Edit</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-gray-400">No tasks available.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-md max-w-md w-full">
            <h3 class="text-lg font-bold mb-4">Edit Task</h3>
            <form id="editForm" method="POST">
                <input type="hidden" name="task_id" id="edit_task_id">
                <div class="mb-4">
                    <label for="edit_task" class="block text-sm font-medium text-gray-700">Task</label>
                    <input type="text" name="edit_task" id="edit_task" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="edit_priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select name="edit_priority" id="edit_priority" class="mt-1 p-2 w-full border rounded-md">
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded-md mr-2" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editTask(taskId) {
            const tasks = <?= json_encode($_SESSION['tasks']) ?>;
            const task = tasks[taskId];

            document.getElementById('edit_task_id').value = taskId;
            document.getElementById('edit_task').value = task.task ?? '';
            document.getElementById('edit_priority').value = task.priority ?? 'Medium';

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
