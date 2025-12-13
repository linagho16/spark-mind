<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../Controller/StaticsController.php';

$statsController = new StatisticsController();
$stats = $statsController->getAllStatistics();
$aiInsights = $stats ? $statsController->getAIInsights($stats) : "No data available.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Statistics & AI Insights</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 30px;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            transition: all 0.2s;
        }

        .back-link:hover {
            background: rgba(255,255,255,0.3);
            transform: translateX(-5px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .stat-card .value {
            color: #667eea;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-card .change {
            color: #999;
            font-size: 0.9rem;
        }

        .ai-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .ai-section h2 {
            color: #667eea;
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ai-content {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 25px;
            border-radius: 15px;
            color: #333;
            font-size: 1rem;
            line-height: 1.8;
            white-space: pre-line;
            border-left: 4px solid #667eea;
        }

        .top-feedbacks-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .top-feedbacks-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .top-feedbacks-card h2 {
            color: #667eea;
            font-size: 1.5rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feedback-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            transition: all 0.2s;
        }

        .feedback-item:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .feedback-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .feedback-stats {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
        }

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .stat-badge.likes {
            background: #fee;
            color: #e0245e;
        }

        .stat-badge.comments {
            background: #e3f2fd;
            color: #1976d2;
        }

        .feedback-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .feedback-meta {
            font-size: 0.85rem;
            color: #999;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .top-feedbacks-section {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Feedbacks</a>

        <div class="header">
            <h1>📊 Feedback Statistics & AI Insights</h1>
            <p>Comprehensive analytics and AI-powered feedback analysis</p>
        </div>

        <?php if ($stats): ?>
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Feedbacks</h3>
                    <div class="value"><?php echo $stats['total_feedbacks']; ?></div>
                    <div class="change">All time</div>
                </div>
                
                <div class="stat-card">
                    <h3>Total Likes</h3>
                    <div class="value"><?php echo $stats['total_likes']; ?></div>
                    <div class="change">❤️ Hearts received</div>
                </div>

                <div class="stat-card">
                    <h3>Total Comments</h3>
                    <div class="value"><?php echo $stats['total_comments']; ?></div>
                    <div class="change">💬 Comments made</div>
                </div>
                
                <div class="stat-card">
                    <h3>Avg Likes/Feedback</h3>
                    <div class="value"><?php echo $stats['average_likes_per_feedback']; ?></div>
                    <div class="change">Per feedback average</div>
                </div>

                <div class="stat-card">
                    <h3>Avg Comments/Feedback</h3>
                    <div class="value"><?php echo $stats['average_comments_per_feedback']; ?></div>
                    <div class="change">Per feedback average</div>
                </div>
                
                <div class="stat-card">
                    <h3>Engagement Rate</h3>
                    <div class="value"><?php echo $stats['engagement_rate']; ?>%</div>
                    <div class="change">Overall engagement</div>
                </div>
            </div>

            <!-- AI Insights Section -->
            <div class="ai-section">
                <h2>
                    <span>🤖</span>
                    AI-Powered Feedback Summary & Insights
                </h2>
                <div class="ai-content">
                    <?php echo htmlspecialchars($aiInsights); ?>
                </div>
            </div>

            <!-- Top Feedbacks Sections -->
            <div class="top-feedbacks-section">
                <!-- Most Liked Feedbacks -->
                <?php if (!empty($stats['most_liked_feedbacks'])): ?>
                <div class="top-feedbacks-card">
                    <h2>❤️ Most Liked Feedbacks</h2>
                    <?php foreach($stats['most_liked_feedbacks'] as $idx => $feedback): ?>
                        <div class="feedback-item">
                            <div class="feedback-item-header">
                                <strong>#<?php echo $idx + 1; ?></strong>
                                <div class="feedback-stats">
                                    <span class="stat-badge likes">❤️ <?php echo $feedback['likes']; ?> likes</span>
                                    <span class="stat-badge comments">💬 <?php echo $feedback['comments']; ?> comments</span>
                                </div>
                            </div>
                            <div class="feedback-content">
                                <?php echo nl2br(htmlspecialchars($feedback['description'])); ?>
                            </div>
                            <div class="feedback-meta">
                                By <?php echo htmlspecialchars($feedback['author']); ?> · <?php echo date('M d, Y', strtotime($feedback['date'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Most Commented Feedbacks -->
                <?php if (!empty($stats['most_commented_feedbacks'])): ?>
                <div class="top-feedbacks-card">
                    <h2>💬 Most Commented Feedbacks</h2>
                    <?php foreach($stats['most_commented_feedbacks'] as $idx => $feedback): ?>
                        <div class="feedback-item">
                            <div class="feedback-item-header">
                                <strong>#<?php echo $idx + 1; ?></strong>
                                <div class="feedback-stats">
                                    <span class="stat-badge likes">❤️ <?php echo $feedback['likes']; ?> likes</span>
                                    <span class="stat-badge comments">💬 <?php echo $feedback['comments']; ?> comments</span>
                                </div>
                            </div>
                            <div class="feedback-content">
                                <?php echo nl2br(htmlspecialchars($feedback['description'])); ?>
                            </div>
                            <div class="feedback-meta">
                                By <?php echo htmlspecialchars($feedback['author']); ?> · <?php echo date('M d, Y', strtotime($feedback['date'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="stat-card" style="grid-column: 1 / -1;">
                <h3>No Data Available</h3>
                <p style="color: #999; margin-top: 10px;">Start receiving feedback to see your statistics!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
