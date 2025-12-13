<?php 
require_once __DIR__.'/../../Controller/FeedbackController.php';
require_once __DIR__.'/../../Controller/ReactionController.php';
require_once __DIR__.'/../../Controller/commentcontroller.php';
$feedbackC = new FeedbackController();
$reactionC = new ReactionController();
$commentC = new CommentController();
$list = $feedbackC->getAllFeedbacks();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h1 {
            font-size: 2rem;
            color: #1e3c72;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 40px 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            width: 250px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
        }

        .btn-refresh {
            background: #f0f0f0;
            color: #333;
            padding: 10px 15px;
        }

        .btn-refresh:hover {
            background: #e0e0e0;
        }

        .feedbacks-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.5rem;
            color: #1e3c72;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr {
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 6px;
            margin: 0 3px;
        }

        .btn-view {
            background: #2a5298;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-view:hover {
            background: #1e3c72;
        }

        .btn-edit {
            background: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background: #218838;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .expandable-row {
            background: #f8f9fa !important;
        }

        .expandable-row td {
            padding: 20px;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .sub-table th {
            background: #e9ecef;
            color: #333;
            padding: 10px;
            font-size: 0.85rem;
        }

        .sub-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .description-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .actions-cell {
            white-space: nowrap;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .no-data-icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
            }

            .search-box input {
                width: 100%;
            }

            .table-wrapper {
                overflow-x: scroll;
            }

            th, td {
                padding: 10px 8px;
                font-size: 0.85rem;
            }

            .description-cell {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>📋 Feedback Management</h1>
                <p>Manage and monitor all community feedbacks</p>
            </div>
            <div class="header-actions">
                <div class="search-box">
                    <input type="text" placeholder="Search feedbacks..." id="search-input" onkeyup="filterFeedbacks()" />
                    <span class="search-icon">🔍</span>
                </div>
                <button class="btn btn-refresh" onclick="location.reload()" title="Refresh">🔄</button>
                <a href="addFeedback.php" class="btn btn-primary">+ Add Feedback</a>
            </div>
        </div>

        <div class="feedbacks-container">
            <div class="section-title">
                <h2>All Feedbacks</h2>
                <span style="color: #666; font-size: 0.9rem; font-weight: normal;">Total: <?php echo count($list); ?></span>
            </div>

            <?php if (count($list) > 0): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Author</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Likes</th>
                            <th>Comments</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="feedbacks-table">
                        <?php foreach($list as $feedback){ 
                            $reactionCount = $reactionC->getReactionCount($feedback['id'], 'heart');
                            $reactions = $reactionC->getAllReactionsForFeedback($feedback['id'], 'heart');
                            $commentCount = $commentC->getCommentCount($feedback['id']);
                            $comments = $commentC->getCommentsForFeedback($feedback['id']);
                        ?>
                            <tr class="feedback-row">
                                <td style="font-family: monospace; font-size: 0.85rem; color: #666;">
                                    <?php echo substr($feedback['id'], 0, 20) . '...'; ?>
                                </td>
                                <td class="description-cell" title="<?php echo htmlspecialchars($feedback['description']); ?>">
                                    <?php echo htmlspecialchars(substr($feedback['description'], 0, 80)) . (strlen($feedback['description']) > 80 ? '...' : ''); ?>
                                </td>
                                <td><?php echo htmlspecialchars($feedback['username']); ?></td>
                                <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($feedback['created_at'])); ?></td>
                                <td>
                                    <strong><?php echo $reactionCount; ?></strong>
                                    <?php if ($reactionCount > 0) { ?>
                                        <button class="btn btn-small btn-view" onclick="toggleReactions('<?php echo $feedback['id']; ?>')">View</button>
                                    <?php } ?>
                                </td>
                                <td>
                                    <strong><?php echo $commentCount; ?></strong>
                                    <?php if ($commentCount > 0) { ?>
                                        <button class="btn btn-small btn-view" onclick="toggleComments('<?php echo $feedback['id']; ?>')">View</button>
                                    <?php } ?>
                                </td>
                                <td>
                                    <span class="badge badge-success">Active</span>
                                </td>
                                <td class="actions-cell">
                                    <a href="updateFeedback.php?id=<?php echo $feedback['id']; ?>" class="btn btn-small btn-edit">Update</a>
                                    <a href="deleteFeedback.php?id=<?php echo $feedback['id']; ?>" 
                                       class="btn btn-small btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</a>
                                </td>
                            </tr>
                            <?php if ($reactionCount > 0) { ?>
                            <tr id="reactions-<?php echo $feedback['id']; ?>" class="expandable-row" style="display: none;">
                                <td colspan="9">
                                    <div style="margin-bottom: 15px;">
                                        <strong style="color: #1e3c72;">Users who liked this feedback (<?php echo $reactionCount; ?>):</strong>
                                    </div>
                                    <table class="sub-table">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($reactions as $reaction) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($reaction['username']); ?></td>
                                                <td><?php echo date('M d, Y g:i A', strtotime($reaction['created_at'])); ?></td>
                                                <td>
                                                    <a href="deleteReaction.php?id=<?php echo $reaction['id']; ?>&feedback_id=<?php echo $feedback['id']; ?>" 
                                                       class="btn btn-small btn-delete"
                                                       onclick="return confirm('Are you sure you want to delete this reaction?')">Delete</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if ($commentCount > 0) { ?>
                            <tr id="comments-<?php echo $feedback['id']; ?>" class="expandable-row" style="display: none;">
                                <td colspan="9">
                                    <div style="margin-bottom: 15px;">
                                        <strong style="color: #1e3c72;">Comments on this feedback (<?php echo $commentCount; ?>):</strong>
                                    </div>
                                    <table class="sub-table">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Content</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($comments as $comment) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($comment['username']); ?></td>
                                                <td><?php echo htmlspecialchars($comment['content']); ?></td>
                                                <td><?php echo date('M d, Y g:i A', strtotime($comment['created_at'])); ?></td>
                                                <td>
                                                    <form method="POST" action="updateComment.php" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                                                        <button type="submit" class="btn btn-small btn-edit">Update</button>
                                                    </form>
                                                    <a href="deleteComment.php?id=<?php echo $comment['id']; ?>&feedback_id=<?php echo $feedback['id']; ?>" 
                                                       class="btn btn-small btn-delete"
                                                       onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="no-data">
                <div class="no-data-icon">📭</div>
                <h3>No Feedbacks Yet</h3>
                <p>Start by adding a new feedback</p>
                <a href="addFeedback.php" class="btn btn-primary" style="margin-top: 20px;">+ Add First Feedback</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleReactions(feedbackId) {
            const row = document.getElementById('reactions-' + feedbackId);
            if (row) {
                row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
            }
        }

        function toggleComments(feedbackId) {
            const row = document.getElementById('comments-' + feedbackId);
            if (row) {
                row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
            }
        }

        function filterFeedbacks() {
            const input = document.getElementById('search-input');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('feedbacks-table');
            const rows = table.getElementsByClassName('feedback-row');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent || row.innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
