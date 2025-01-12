
<?php
session_start();

// Include the MessageModel
require_once '../models/MessageModel.php';

// Create an instance of the MessageModel
$messageModel = new MessageModel();

// Retrieve messages
$messages = $messageModel->get_messages($_SESSION['id']);
$messages = array_reverse($messages); // Reverse the array to show the latest messages first

if (isset($_POST['send_msg'])) {

    $userId = $_SESSION['id']; // Get the user ID from session
    $messageContent = $_POST['reply_message']; // Get the message content
    $sender=$_POST['sender_id'];


    $messageModel = new MessageModel();
    $date = date('Y-m-d H:i:s');
    $result = $messageModel->Send_Message1($userId, $sender, $messageContent,$date);

    if ($result) {
        $flightStatusMessage = "Message sent successfully!";
        $modalType = 'success';  // Success modal
    } else {
        $flightStatusMessage = "Failed to send message!";
        $modalType = 'danger';  // Error modal
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/notifications.css">
</head>

<body>

<button class="btn btn-secondary" onclick="history.back()">
    <i class="fas fa-arrow-left"></i>
</button>

<div class="notifications-container">
    <h1>Notifications</h1>
    <?php if (!empty($messages)): ?>
        <ul class="notifications-list">
            <?php foreach ($messages as $message): ?>
                <li class="notification-item" onclick="toggleDetails(<?php echo $message['id']; ?>)">
                    <div class="notification-content">
                        <p><?php echo htmlspecialchars($message['content']); ?></p>
                    </div>
                    <div class="notification-details" id="details-<?php echo $message['id']; ?>">
                        <p><strong>Sender ID:</strong> <?php echo htmlspecialchars($message['sender_id']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($message['date']); ?></p>
                        <button class="btn-reply" data-bs-toggle="modal" data-bs-target="#replyModal" onclick="setMessageId(<?php echo $message['id']; ?>, <?php echo $message['sender_id']; ?>)">Reply</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No notifications available.</p>
    <?php endif; ?>
</div>

<!-- Modal for reply -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Reply to Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="notifications.php">
                    <input type="hidden" id="replyMessageId" name="send_msg" value="1">
                    <input type="hidden" id="senderId" name="sender_id" value="">
                    <div class="mb-3">
                        <label for="replyContent" class="form-label">Your Reply</label>
                        <textarea class="form-control" id="replyContent" name="reply_message" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle the visibility of the message details
    function toggleDetails(messageId) {
        const details = document.getElementById('details-' + messageId);
        if (details.style.display === 'none' || details.style.display === '') {
            details.style.display = 'block';
        } else {
            details.style.display = 'none';
        }
    }

    // Set the message ID and sender ID for the reply modal
    function setMessageId(messageId, senderId) {
        document.getElementById('replyMessageId').value = messageId;
        document.getElementById('senderId').value = senderId;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>