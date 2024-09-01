<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';

    if (isset($_POST['new_note'])) {
        $created_at = date('Y-m-d H:i:s'); // Get the current date and time
        $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $content, $created_at);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['edit_note'])) {
        $note_id = $_POST['note_id'];
        $stmt = $conn->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $title, $content, $note_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_note'])) {
        $note_id = $_POST['note_id'];
        $stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $note_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit();
    }
}

$notes = [];
$result = $conn->query("SELECT * FROM notes WHERE user_id = $user_id ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}
$result->close();
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Notepad</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <style>
        /* Popup container - can be anything you want */
        
    </style>
    <script>
        function editNote(id, title, content) {
            document.getElementById('note_id').value = id;
            document.getElementById('title').value = title;
            document.getElementById('content').value = content;
            document.getElementById('edit_note').style.display = 'block';
            document.getElementById('new_note').style.display = 'none';
        }

        function clearForm() {
            document.getElementById('note_id').value = '';
            document.getElementById('title').value = '';
            document.getElementById('content').value = '';
            document.getElementById('edit_note').style.display = 'none';
            document.getElementById('new_note').style.display = 'block';
        }

        function showPopup(title, content, date) {
            var popup = document.getElementById('notePopup');
            var popupContent = document.getElementById('popupContent');
            popupContent.innerHTML = "<h3>" + title + "</h3><p>" + content + "</p><p><em>" + date + "</em></p>";
            popup.style.display = 'block';
        }

        function closePopup() {
            var popup = document.getElementById('notePopup');
            popup.style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Notepad</h2>
        <form method="POST" action="" id="note_form">
            <input type="hidden" name="note_id" id="note_id">
            <input type="text" name="title" id="title" placeholder="Title" required><br><br>
            <textarea name="content" id="content" placeholder="Write your note here..." required></textarea><br><br>
            <button type="button" onclick="clearForm()">Clear</button><br><br>
            <button type="submit" name="new_note" id="new_note" style="display: block; background-color:green;">Save Note</button>
            <button type="submit" name="edit_note" id="edit_note" style="display: none;">Update Note</button>
        </form>
        <h3>Your Notes</h3>
        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <p><strong><?php echo htmlspecialchars($note['title']); ?>:</strong> <?php echo htmlspecialchars(substr($note['content'], 0, 20)); ?>...</p>
                    <p><em><?php echo $note['created_at']; ?></em></p>
                    <button onclick="showPopup('<?php echo addslashes(htmlspecialchars($note['title'])); ?>', '<?php echo addslashes(nl2br(htmlspecialchars($note['content']))); ?>', '<?php echo $note['created_at']; ?>')">Read</button>
                    <button class="edit-button" onclick="editNote(<?php echo $note['id']; ?>, '<?php echo addslashes(htmlspecialchars($note['title'])); ?>', '<?php echo addslashes($note['content']); ?>')">Edit</button>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                        <button type="submit" name="delete_note">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div id="notePopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <div id="popupContent"></div>
        </div>
    </div>
    <div class="footer">
        Made by Oussama Chikh | <a href="https://github.com/oussxh" target="_blank">GitHub</a>
    </div>
</body>
</html>
