<?php
// view/frontoffice/browse_groupes.php - Browse all groups with Chatbot
session_start();
require_once __DIR__ . '/../../model/groupemodel.php';

try {
    $model = new GroupeModel();
    
    // Get filters from URL
    $filters = ['statut' => 'frontoffice']; // Show both actif and en_attente
    
    if (isset($_GET['type']) && !empty($_GET['type'])) {
        $filters['type'] = $_GET['type'];
    }
    
    if (isset($_GET['region']) && !empty($_GET['region'])) {
        $filters['region'] = $_GET['region'];
    }
    
    // Get groups with filters
    $groupes = $model->getGroupesWithFilters($filters);
    
    // Get unique types and regions for filters
    $allGroupes = $model->getAllGroupes();
    $types = array_unique(array_column($allGroupes, 'type'));
    $regions = array_unique(array_column($allGroupes, 'region'));
    
} catch (Exception $e) {
    $error = "Erreur: " . $e->getMessage();
    $groupes = [];
    $types = [];
    $regions = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parcourir les Groupes - Aide Solidaire</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #FBEDD7;
            color: #333;
            line-height: 1.6;
        }

        /* Header - Dashboard Style */
        .header {
            background: linear-gradient(135deg, #fbdcc1 0%, #ec9d78 15%, #b095c6 55%, #7dc9c4 90%);
            color: white;
            padding: 3rem 2rem 4rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
            border-radius: 0 0 20px 20px;
        }

        .header h1 {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .pigeon-bg {
            position: absolute;
            bottom: 20px;
            right: 5%;
            font-size: 8rem;
            opacity: 0.15;
            z-index: 1;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
        }

        /* Back Link - Dashboard Style */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            color: #7d5aa6;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .back-link:hover {
            background: rgba(125, 90, 166, 0.1);
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        /* Filters Container - Dashboard Style */
        .filters-container {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .filters-title {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filters-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.8rem;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: #333;
            font-size: 1rem;
        }

        .filter-select {
            padding: 1rem 1.2rem;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%237d5aa6' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.2rem center;
            background-size: 12px;
            padding-right: 3rem;
        }

        .filter-select:focus {
            outline: none;
            border-color: #7d5aa6;
            box-shadow: 0 0 0 3px rgba(125, 90, 166, 0.15);
            transform: translateY(-1px);
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .filter-btn {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(125, 90, 166, 0.3);
        }

        .reset-btn {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        /* Results Count */
        .results-count {
            text-align: center;
            margin: 2rem 0;
            color: #666;
            font-size: 1.2rem;
            padding: 1rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .results-count strong {
            color: #7d5aa6;
            font-size: 1.5rem;
        }

        /* Main Content Sections */
        .section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 2.5rem 0;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.2rem;
            border-bottom: 2px solid #f1f3f5;
        }

        .section-title h2 {
            color: #333;
            font-size: 1.6rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Grid Layout */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        /* Cards - Dashboard Style */
        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f3f5;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }

        .card-header {
            padding: 2rem 2rem 0;
        }

        .card-icon {
            font-size: 3.5rem;
            margin-bottom: 1.2rem;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: linear-gradient(135deg, rgba(125, 90, 166, 0.1), rgba(181, 140, 224, 0.15));
        }

        .card-title {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 0.8rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem 2rem 2rem;
        }

        .card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
            color: #666;
        }

        .card-meta span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.4rem 0.8rem;
            background: #f8f9fa;
            border-radius: 20px;
        }

        .card-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .card-actions {
            display: flex;
            gap: 0.8rem;
        }

        .btn {
            flex: 1;
            padding: 0.9rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #1f8c87, #7eddd5);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border: 1px solid #b1dfbb;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            border: 1px solid #ffecb5;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
            background: #f8f9fa;
            border-radius: 15px;
            border: 2px dashed #e1e5e9;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 2.5rem 0;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .quick-actions h3 {
            color: #333;
            margin-bottom: 1.8rem;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.2rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            padding: 1.2rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .action-btn:nth-child(1) {
            background: linear-gradient(135deg, #ec9d78, #fbdcc1);
        }

        .action-btn:nth-child(2) {
            background: linear-gradient(135deg, #ec7546, #f4a261);
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            text-align: center;
            padding: 2.5rem;
            margin-top: 3rem;
        }

        .footer p {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        /* Active filters indicator */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .filter-tag button {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
        }

        /* Chatbot Styles - CORRIGÉES */
        #chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .chatbot-toggle {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(125, 90, 166, 0.3);
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .chatbot-toggle:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(125, 90, 166, 0.4);
        }

        .chatbot-icon {
            font-size: 24px;
        }

        .chatbot-label {
            font-size: 14px;
        }

        .chatbot-window {
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 400px;
            height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .chatbot-window.active {
            display: flex;
            animation: slideIn 0.3s ease;
        }

        .chatbot-header {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .chatbot-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chatbot-icon-header {
            font-size: 24px;
        }

        .chatbot-title h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .chatbot-close {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .chatbot-close:hover {
            background: rgba(255,255,255,0.2);
        }

        .chatbot-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 0;
        }

        .chatbot-messages-container {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .chatbot-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .chatbot-messages::-webkit-scrollbar {
            width: 8px;
        }

        .chatbot-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .chatbot-messages::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 4px;
        }

        .chatbot-messages::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .chatbot-welcome {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .chatbot-welcome p {
            margin-bottom: 10px;
            color: #333;
        }

        .chatbot-welcome ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .chatbot-welcome li {
            margin-bottom: 5px;
            color: #666;
        }

        .message {
            margin-bottom: 15px;
            max-width: 85%;
            padding: 12px 16px;
            border-radius: 18px;
            line-height: 1.4;
            word-wrap: break-word;
            animation: messageAppear 0.3s ease;
        }

        @keyframes messageAppear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-message {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 5px;
        }

        .bot-message {
            background: #f1f3f5;
            color: #333;
            margin-right: auto;
            border-bottom-left-radius: 5px;
        }

        .chatbot-input-area {
            padding: 20px;
            border-top: 1px solid #e1e5e9;
            background: white;
            flex-shrink: 0;
        }

        .chatbot-input-container {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .chatbot-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            resize: none;
            transition: border 0.3s ease;
            min-height: 50px;
            max-height: 120px;
            line-height: 1.4;
        }

        .chatbot-input:focus {
            outline: none;
            border-color: #7d5aa6;
            box-shadow: 0 0 0 3px rgba(125, 90, 166, 0.1);
        }

        .chatbot-send {
            background: linear-gradient(135deg, #7d5aa6, #b58ce0);
            color: white;
            border: none;
            border-radius: 12px;
            width: 50px;
            height: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .chatbot-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(125, 90, 166, 0.3);
        }

        .chatbot-send:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .chatbot-suggestions {
            padding-top: 15px;
        }

        .chatbot-suggestions p {
            margin-bottom: 10px;
            color: #666;
            font-size: 14px;
            font-weight: 600;
        }

        .suggestion-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .suggestion-btn {
            background: #f8f9fa;
            border: 1px solid #e1e5e9;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 12px;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .suggestion-btn:hover {
            background: #e9ecef;
            border-color: #7d5aa6;
            color: #7d5aa6;
        }

        .typing-indicator {
            display: inline-flex;
            align-items: center;
            padding: 12px 16px;
            background: #f1f3f5;
            border-radius: 18px;
            border-bottom-left-radius: 5px;
            margin-bottom: 15px;
            align-self: flex-start;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: #666;
            border-radius: 50%;
            margin: 0 2px;
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-5px);
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                max-width: 100%;
                padding: 0 1.5rem 2rem;
            }
            
            .grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 2rem 1rem 3rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .filters-form {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .filter-actions {
                flex-direction: column;
            }
            
            .filter-btn,
            .reset-btn {
                width: 100%;
                justify-content: center;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .card-actions {
                flex-direction: column;
            }
            
            .section,
            .filters-container,
            .quick-actions {
                padding: 1.8rem;
            }
            
            .action-buttons {
                grid-template-columns: 1fr;
            }
            
            /* Chatbot responsive */
            #chatbot-container {
                bottom: 10px;
                right: 10px;
            }
            
            .chatbot-window {
                width: 90vw;
                height: 70vh;
                right: -50%;
                transform: translateX(50%);
            }
            
            .chatbot-toggle .chatbot-label {
                display: none;
            }
            
            .chatbot-messages {
                padding: 15px;
            }
            
            .chatbot-input-area {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 1rem 2rem;
            }
            
            .card-header {
                padding: 1.5rem 1.5rem 0;
            }
            
            .card-body {
                padding: 1.5rem 1.5rem 1.5rem;
            }
            
            .card-meta {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .btn {
                padding: 0.8rem;
                font-size: 0.85rem;
            }
            
            .action-btn {
                padding: 1rem;
                font-size: 0.9rem;
            }
            
            .results-count {
                font-size: 1rem;
                padding: 0.8rem;
            }
            
            .footer {
                padding: 2rem 1rem;
            }
            
            .chatbot-window {
                width: 95vw;
                height: 80vh;
                bottom: 60px;
                right: 2.5vw;
            }
            
            .message {
                max-width: 90%;
            }
            
            .suggestion-buttons {
                gap: 8px;
            }
            
            .suggestion-btn {
                padding: 6px 12px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1>👥 Parcourir les Groupes</h1>
        <p>Rejoignez des initiatives solidaires dans votre région</p>
        <div class="pigeon-bg">🕊️</div>
    </header>

    <main class="container">
        <!-- Back Button -->
        <a href="/aide_solitaire/view/frontoffice/index.php" class="back-link">
            <span>←</span>
            <span>Retour à l'accueil</span>
        </a>
        
        <!-- Active Filters -->
        <?php if (isset($_GET['type']) || isset($_GET['region'])): ?>
        <div class="active-filters">
            <?php if (isset($_GET['type']) && !empty($_GET['type'])): ?>
            <span class="filter-tag">
                <span>🏷️ Type: <?php echo htmlspecialchars($_GET['type']); ?></span>
                <button onclick="removeFilter('type')">×</button>
            </span>
            <?php endif; ?>
            
            <?php if (isset($_GET['region']) && !empty($_GET['region'])): ?>
            <span class="filter-tag">
                <span>📍 Région: <?php echo htmlspecialchars($_GET['region']); ?></span>
                <button onclick="removeFilter('region')">×</button>
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Filters -->
        <div class="filters-container">
            <div class="filters-title">
                <span>🔍</span>
                <span>Filtres de recherche</span>
            </div>
            <form method="GET" action="/aide_solitaire/view/frontoffice/browse_groupes.php" class="filters-form">
                <input type="hidden" name="action" value="list">
                <input type="hidden" name="context" value="frontoffice">
                
                <div class="filter-group">
                    <label class="filter-label">Type de groupe</label>
                    <select name="type" class="filter-select">
                        <option value="">Tous les types</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>" 
                                <?php echo isset($_GET['type']) && $_GET['type'] == $type ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Région</label>
                    <select name="region" class="filter-select">
                        <option value="">Toutes les régions</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?php echo htmlspecialchars($region); ?>" 
                                <?php echo isset($_GET['region']) && $_GET['region'] == $region ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($region); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="filter-btn">
                        <span>🔍</span>
                        <span>Appliquer les filtres</span>
                    </button>
                    <a href="/aide_solitaire/view/frontoffice/browse_groupes.php?action=list&context=frontoffice" class="reset-btn">
                        <span>🔄</span>
                        <span>Réinitialiser</span>
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Results Count -->
        <div class="results-count">
            <strong><?php echo count($groupes); ?></strong> groupes trouvés
        </div>
        
        <!-- Groups Grid -->
        <section class="section">
            <div class="section-title">
                <h2><span>👥</span> Groupes disponibles</h2>
            </div>
            
            <?php if (!empty($groupes)): ?>
                <div class="grid">
                    <?php foreach ($groupes as $groupe): ?>
                    <div class="card" data-group-id="<?php echo $groupe['id']; ?>">
                        <div class="card-header">
                            <div class="card-icon">
                                <?php 
                                $icons = [
                                    'Santé' => '🏥',
                                    'Éducation' => '📚',
                                    'Seniors' => '👵',
                                    'Jeunesse' => '👦',
                                    'Culture' => '🎨',
                                    'Urgence' => '🚨',
                                    'Animaux' => '🐾',
                                    'Environnement' => '🌿',
                                    'Religieux' => '🌙',
                                    'Social' => '🤝'
                                ];
                                echo $icons[$groupe['type']] ?? '👥';
                                ?>
                            </div>
                            <h3 class="card-title"><?php echo htmlspecialchars($groupe['nom']); ?></h3>
                            <span class="status-badge status-<?php echo $groupe['statut']; ?>">
                                <?php 
                                $statusText = [
                                    'actif' => 'Actif',
                                    'en_attente' => 'En attente',
                                    'inactif' => 'Inactif'
                                ];
                                echo $statusText[$groupe['statut']] ?? $groupe['statut'];
                                ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span>📍 <?php echo htmlspecialchars($groupe['region']); ?></span>
                                <span>👤 <?php echo htmlspecialchars($groupe['responsable']); ?></span>
                                <?php if (isset($groupe['created_at'])): ?>
                                <span>📅 <?php echo date('d/m/Y', strtotime($groupe['created_at'])); ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="card-description"><?php echo nl2br(htmlspecialchars($groupe['description'] ?? 'Pas de description')); ?></p>
                            <div class="card-actions">
                                <a href="/aide_solitaire/view/Frontoffice/view_groupe.php?action=view&id=<?php echo $groupe['id']; ?>&context=frontoffice" class="btn btn-primary">
                                    <span>🔍</span>
                                    <span>Voir détails</span>
                                </a>
                                <a href="mailto:<?php echo htmlspecialchars($groupe['email']); ?>" class="btn btn-secondary">
                                    <span>📧</span>
                                    <span>Contacter</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>👥 Aucun groupe ne correspond à vos critères.</p>
                    <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: center;">
                        <a href="/aide_solitaire/controller/groupeC.php?action=list&context=frontoffice" class="btn btn-primary" style="display: inline-flex; width: auto;">
                            <span>🔍</span>
                            <span>Voir tous les groupes</span>
                        </a>
                        <a href="/aide_solitaire/view/Frontoffice/create_groupe.php?action=create&context=frontoffice" class="btn btn-secondary" style="display: inline-flex; width: auto;">
                            <span>➕</span>
                            <span>Créer un groupe</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>
        
        <!-- Quick Actions -->
        <section class="quick-actions">
            <h3>Vous ne trouvez pas le groupe idéal ?</h3>
            <div class="action-buttons">
                <a href="/aide_solitaire/view/frontoffice/create_groupe.php?action=create&context=frontoffice" class="action-btn">
                    <span>👥</span>
                    <span>Créer votre propre groupe</span>
                </a>
                <a href="/aide_solitaire/view/frontoffice/index.php" class="action-btn">
                    <span>🏠</span>
                    <span>Retour à l'accueil</span>
                </a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>© 2025 Aide Solidaire - Ensemble, créons des communautés fortes ! ❤️</p>
    </footer>

    <!-- Chatbot Bubble -->
    <div id="chatbot-container">
        <!-- Chatbot Bubble Button -->
        <button id="chatbot-toggle" class="chatbot-toggle">
            <span class="chatbot-icon">🤖</span>
            <span class="chatbot-label">Assistant IA</span>
        </button>
        
        <!-- Chatbot Window -->
        <div id="chatbot-window" class="chatbot-window">
            <div class="chatbot-header">
                <div class="chatbot-title">
                    <span class="chatbot-icon-header">🤖</span>
                    <h3>Assistant Aide Solidaire</h3>
                </div>
                <button id="chatbot-close" class="chatbot-close">×</button>
            </div>
            
            <div class="chatbot-body">
                <div class="chatbot-messages-container">
                    <div id="chatbot-messages" class="chatbot-messages">
                        <!-- Messages will appear here -->
                        <div class="chatbot-welcome">
                            <p>👋 Bonjour ! Je suis l'assistant intelligent d'Aide Solidaire.</p>
                            <p>Je peux vous aider avec :</p>
                            <ul>
                                <li>📝 Informations sur les dons</li>
                                <li>👥 Questions sur les groupes</li>
                                <li>📍 Procédures et régions</li>
                                <li>❓ Toute question sur l'entraide</li>
                            </ul>
                            <p>Comment puis-je vous aider aujourd'hui ?</p>
                        </div>
                    </div>
                </div>
                
                <div class="chatbot-input-area">
                    <div class="chatbot-input-container">
                        <textarea 
                            id="chatbot-input" 
                            class="chatbot-input" 
                            placeholder="Posez votre question ici..."
                            rows="1"
                        ></textarea>
                        <button id="chatbot-send" class="chatbot-send">
                            <span>📤</span>
                        </button>
                    </div>
                    
                    <div class="chatbot-suggestions">
                        <p>Questions fréquentes :</p>
                        <div class="suggestion-buttons">
                            <button class="suggestion-btn" data-question="Quels types de dons acceptez-vous ?">
                                Types de dons
                            </button>
                            <button class="suggestion-btn" data-question="Comment créer un groupe solidaire ?">
                                Créer un groupe
                            </button>
                            <button class="suggestion-btn" data-question="Quels avantages fiscaux pour les dons ?">
                                Avantages fiscaux
                            </button>
                            <button class="suggestion-btn" data-question="Comment devenir bénévole ?">
                                Devenir bénévole
                            </button>
                            <button class="suggestion-btn" data-question="Où sont vos points de collecte ?">
                                Points de collecte
                            </button>
                            <button class="suggestion-btn" data-question="Comment financer notre groupe ?">
                                Financement groupe
                            </button>
                            <button class="suggestion-btn" data-question="Urgence médicale : que faire ?">
                                Urgence médicale
                            </button>
                            <button class="suggestion-btn" data-question="Comment assurer la confiance dans les dons ?">
                                Sécurité et confiance
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Main JavaScript functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Remove filter functionality
            window.removeFilter = function(filterName) {
                const url = new URL(window.location);
                url.searchParams.delete(filterName);
                window.location.href = url.toString();
            };

            // Animate cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animation = 'slideIn 0.5s ease forwards';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe cards when page loads
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                observer.observe(card);
            });

            // ========== CHATBOT FUNCTIONALITY ==========
            
            // Elements
            const chatbotToggle = document.getElementById('chatbot-toggle');
            const chatbotWindow = document.getElementById('chatbot-window');
            const chatbotClose = document.getElementById('chatbot-close');
            const chatbotInput = document.getElementById('chatbot-input');
            const chatbotSend = document.getElementById('chatbot-send');
            const chatbotMessages = document.getElementById('chatbot-messages');
            const suggestionButtons = document.querySelectorAll('.suggestion-btn');
            
            // Toggle chatbot window
            chatbotToggle.addEventListener('click', function() {
                chatbotWindow.classList.add('active');
                chatbotInput.focus();
                // Scroll to bottom when opening
                setTimeout(scrollToBottom, 100);
            });
            
            chatbotClose.addEventListener('click', function() {
                chatbotWindow.classList.remove('active');
            });
            
            // Close chatbot when clicking outside (on mobile)
            document.addEventListener('click', function(event) {
                if (!chatbotWindow.contains(event.target) && 
                    !chatbotToggle.contains(event.target) && 
                    chatbotWindow.classList.contains('active')) {
                    chatbotWindow.classList.remove('active');
                }
            });
            
            // Send message on button click
            chatbotSend.addEventListener('click', sendMessage);
            
            // Send message on Enter key (with Shift for new line)
            chatbotInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
            
            // Auto-resize textarea
            chatbotInput.addEventListener('input', function() {
                this.style.height = 'auto';
                const newHeight = Math.min(this.scrollHeight, 120);
                this.style.height = newHeight + 'px';
                this.style.overflowY = newHeight >= 100 ? 'auto' : 'hidden';
                // Ajuster la position du chat si besoin
                setTimeout(scrollToBottom, 10);
            });
            
            // Suggestion buttons
            suggestionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const question = this.getAttribute('data-question');
                    chatbotInput.value = question;
                    chatbotInput.dispatchEvent(new Event('input'));
                    setTimeout(() => sendMessage(), 100);
                });
            });
            
            // Send message function
            // Send message function - VERSION CORRIGÉE
async function sendMessage() {
    const message = chatbotInput.value.trim();
    
    if (!message) {
        chatbotInput.focus();
        return;
    }
    
    // Add user message to chat
    addMessage(message, 'user');
    
    // Clear input and reset height
    chatbotInput.value = '';
    chatbotInput.style.height = 'auto';
    
    // Disable send button during request
    chatbotSend.disabled = true;
    chatbotSend.innerHTML = '<span>⏳</span>';
    
    // Show typing indicator
    showTypingIndicator();
    
    try {
        // Get current group context
        const groupContext = getCurrentGroupContext();
        
        // Prepare API request data
        const requestData = {
            message: message,
            context: groupContext
        };
        
        console.log('Sending to chatbot API:', requestData);
        
        // URL CORRECTE - vérifiez le chemin
        const response = await fetch('/aide_solitaire/controller/chatbot_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData)
        });
        
        // Vérifier si la réponse est du JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error(`Réponse non-JSON: ${contentType}`);
        }
        
        // Remove typing indicator
        removeTypingIndicator();
        
        const data = await response.json();
        
        if (data.success) {
            addMessage(data.message, 'bot');
        } else {
            // Fallback to local responses
            const fallbackResponse = getFallbackResponse(message);
            addMessage(fallbackResponse, 'bot');
        }
        
    } catch (error) {
        console.error('Chatbot error:', error);
        removeTypingIndicator();
        
        // Use local fallback on error
        const fallbackResponse = getFallbackResponse(message);
        addMessage(fallbackResponse, 'bot');
        
    } finally {
        // Re-enable send button
        chatbotSend.disabled = false;
        chatbotSend.innerHTML = '<span>📤</span>';
        chatbotInput.focus();
    }
}
            
            // Add message to chat
            function addMessage(text, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${sender}-message`;
                
                // Format text with line breaks and simple markdown
                const formattedText = formatMessage(text);
                messageDiv.innerHTML = formattedText;
                
                chatbotMessages.appendChild(messageDiv);
                
                // Scroll to bottom with smooth animation
                scrollToBottom();
                
                // Add animation
                messageDiv.style.animation = 'messageAppear 0.3s ease';
            }
            
            // Format message text
            function formatMessage(text) {
                return text
                    .replace(/\n/g, '<br>')
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.*?)\*/g, '<em>$1</em>')
                    .replace(/`(.*?)`/g, '<code>$1</code>')
                    .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" style="color: #7d5aa6; text-decoration: underline;">$1</a>');
            }
            
            // Show typing indicator
            function showTypingIndicator() {
                const typingDiv = document.createElement('div');
                typingDiv.className = 'typing-indicator';
                typingDiv.id = 'typing-indicator';
                typingDiv.innerHTML = `
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                `;
                chatbotMessages.appendChild(typingDiv);
                scrollToBottom();
            }
            
            // Remove typing indicator
            function removeTypingIndicator() {
                const typingIndicator = document.getElementById('typing-indicator');
                if (typingIndicator) {
                    typingIndicator.remove();
                }
            }
            
            // Get current group context
            function getCurrentGroupContext() {
                const urlParams = new URLSearchParams(window.location.search);
                const groupId = urlParams.get('id');
                
                // Try to get group info from current page
                const activeCard = document.querySelector('.card:hover');
                if (activeCard) {
                    const groupId = activeCard.getAttribute('data-group-id');
                    const groupName = activeCard.querySelector('.card-title')?.textContent || '';
                    const groupType = activeCard.querySelector('.card-meta span:nth-child(1)')?.textContent?.replace('📍 ', '') || '';
                    
                    return {
                        groupId: groupId,
                        groupName: groupName,
                        groupType: groupType,
                        page: 'browse_groupes'
                    };
                }
                
                return {
                    groupId: groupId,
                    page: urlParams.has('id') ? 'group_view' : 'browse_groupes'
                };
            }
            
            // Fallback responses when API is unavailable
            function getFallbackResponse(question) {
                const lowerQuestion = question.toLowerCase();
                
                // Common questions and answers
                const responses = {
                    'bonjour': '👋 Bonjour ! Comment puis-je vous aider aujourd\'hui ?',
                    'bonsoir': '🌙 Bonsoir ! Je suis là pour vous aider.',
                    'merci': '😊 De rien ! N\'hésitez pas si vous avez d\'autres questions.',
                    'aide': 'Je peux vous aider avec :<br>• Types de dons acceptés<br>• Création de groupes<br>• Procédures de dons<br>• Informations régionales<br>Que souhaitez-vous savoir ?',
                    'don': 'Nous acceptons plusieurs types de dons :<br>• 👕 Vêtements<br>• 🍎 Nourriture<br>• 💊 Médicaments<br>• 🛠️ Équipement<br>• 💰 Argent<br>• 🛠️ Services<br>• Autre<br>Pour plus de détails, visitez notre page de création de don.',
                    'groupe': 'Pour créer un groupe :<br>1. Cliquez sur "Créer votre propre groupe"<br>2. Remplissez le formulaire<br>3. Attendez la validation<br>4. Commencez à collecter des dons !',
                    'région': 'Nous couvrons toutes les régions de Tunisie :<br>• Tunis, Sfax, Sousse<br>• Kairouan, Bizerte<br>• Gabès, Ariana<br>• Gafsa, Monastir, Autre',
                    'argent': 'Pour les dons financiers :<br>1. Choisissez "Argent" comme type de don<br>2. Entrez le montant<br>3. Procédez au paiement sécurisé<br>4. Recevez une confirmation',
                    'contact': 'Pour nous contacter :<br>📧 Email : contact@aidesolidaire.tn<br>📞 Téléphone : +216 XX XXX XXX<br>📍 Adresse : Tunis, Tunisie'
                };
                
                // Check for keywords in question
                for (const [keyword, response] of Object.entries(responses)) {
                    if (lowerQuestion.includes(keyword)) {
                        return response;
                    }
                }
                
                // Default response
                return "Je comprends votre question mais je rencontre des difficultés techniques. Pour une assistance immédiate, veuillez :<br>1. 📧 Contacter directement le groupe<br>2. 📞 Appeler notre support au +216 XX XXX XXX<br>3. 🔄 Réessayer dans quelques minutes";
            }
            
            // Scroll to bottom of messages - FONCTION AMÉLIORÉE
            function scrollToBottom() {
                const container = document.querySelector('.chatbot-messages');
                if (container) {
                    // Utiliser requestAnimationFrame pour un scroll fluide
                    requestAnimationFrame(() => {
                        container.scrollTop = container.scrollHeight;
                    });
                }
            }
            
            // Initialize chat input height
            chatbotInput.style.height = 'auto';
            
            // Load previous chat from localStorage
            loadChatHistory();
            
            // Save chat to localStorage when leaving
            window.addEventListener('beforeunload', saveChatHistory);
            
            // Ajuster le scroll quand la fenêtre est redimensionnée
            window.addEventListener('resize', function() {
                setTimeout(scrollToBottom, 100);
            });
        });
        
        // Load chat history from localStorage
        function loadChatHistory() {
            try {
                const savedChat = localStorage.getItem('aide_solidaire_chat');
                if (savedChat) {
                    const chatMessages = JSON.parse(savedChat);
                    const chatbotMessages = document.getElementById('chatbot-messages');
                    
                    // Clear welcome message if we have history
                    const welcomeDiv = document.querySelector('.chatbot-welcome');
                    if (welcomeDiv && chatMessages.length > 0) {
                        welcomeDiv.style.display = 'none';
                    }
                    
                    // Add saved messages
                    chatMessages.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `message ${msg.sender}-message`;
                        messageDiv.innerHTML = msg.content;
                        chatbotMessages.appendChild(messageDiv);
                    });
                    
                    scrollToBottom();
                }
            } catch (e) {
                console.log('Could not load chat history:', e);
            }
        }
        
        // Save chat history to localStorage
        function saveChatHistory() {
            try {
                const messages = document.querySelectorAll('#chatbot-messages .message');
                const chatHistory = [];
                
                messages.forEach(msg => {
                    // Skip welcome message
                    if (!msg.closest('.chatbot-welcome')) {
                        const sender = msg.classList.contains('user-message') ? 'user' : 'bot';
                        chatHistory.push({
                            sender: sender,
                            content: msg.innerHTML,
                            timestamp: new Date().toISOString()
                        });
                    }
                });
                
                // Keep only last 20 messages to avoid storage overflow
                const recentHistory = chatHistory.slice(-20);
                localStorage.setItem('aide_solidaire_chat', JSON.stringify(recentHistory));
            } catch (e) {
                console.log('Could not save chat history:', e);
            }
        }
    </script>
</body>
</html>