<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion d'Événements</title>
    <style>
        :root {
            --primary: #D4A574;
            --secondary: #8B7355;
            --success: #8FBC8F;
            --warning: #DAA520;
            --danger: #CD5C5C;
            --light: #FBEDD7;
            --dark: #654321;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: #FBEDD7;
            min-height: 100vh;
            color: #333;
        }
        
        .app-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        nav {
            text-align: center;
        }
        
        nav a {
            color: var(--secondary);
            text-decoration: none;
            margin: 0 20px;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        nav a:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }
        
        h1 {
            color: var(--secondary);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        h2 {
            color: var(--dark);
            margin-bottom: 1rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 165, 116, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(212, 165, 116, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning), #B8860B);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #8B0000);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success), #2E8B57);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: linear-gradient(135deg, var(--secondary), var(--dark));
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
        }
        
        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .alert-error {
            background: linear-gradient(135deg, var(--danger), #8B0000);
            color: white;
        }
        
        .alert-success {
            background: linear-gradient(135deg, var(--success), #2E8B57);
            color: white;
        }
        
        .event-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 5px solid var(--primary);
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .actions a {
            color: var(--primary);
            text-decoration: none;
            margin-right: 15px;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .actions a:hover {
            background: var(--primary);
            color: white;
        }
        
        .actions .delete {
            color: var(--danger);
        }
        
        .actions .delete:hover {
            background: var(--danger);
        }
        
        .no-events {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .header-actions {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .event-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .event-details p {
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .form-actions {
            text-align: center;
            margin-top: 2rem;
        }
        
        .form-actions .btn {
            margin: 0 10px;
        }

        /* NOUVEAUX STYLES POUR LA VALIDATION */
        .error-field {
            border-color: var(--danger) !important;
            box-shadow: 0 0 0 3px rgba(205, 92, 92, 0.1) !important;
        }

        .field-error {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .required::after {
            content: " *";
            color: var(--danger);
        }

        .no-events i {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <header>
            <nav>
                <a href="index.php?route=events.index">🎉 Accueil</a>
                <a href="index.php?route=events.create">➕ Créer un événement</a>
            </nav>
        </header>
        <main>