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
    :root{
      --orange:#ec7546;
      --turquoise:#1f8c87;
      --violet:#7d5aa6;
      --bg:#FBEDD7;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(125,90,166,0.25), transparent 55%),
            radial-gradient(circle at bottom right, rgba(236,117,70,0.20), transparent 55%),
            var(--bg);
        font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        color: #1A464F;
    }

    /* ✅ Layout avec sidebar */
    .layout{
        min-height:100vh;
        display:flex;
    }

    /* ✅ Sidebar */
    .sidebar{
      width:260px;
      background:linear-gradient(#ede8deff 50%, #f7f1eb 100%);
      border-right:1px solid rgba(0,0,0,.06);
      padding:18px 14px;
      display:flex;
      flex-direction:column;
      gap:12px;
      position:sticky;
      top:0;
      height:100vh;
    }

    .sidebar .brand{
      display:flex;
      align-items:center;
      gap:10px;
      text-decoration:none;
      padding:10px 10px;
      border-radius:14px;
      color:#1A464F;
      margin-bottom: 10px;
    }

    .sidebar .brand-name{
      font-family:'Playfair Display', serif;
      font-weight:800;
      font-size:18px;
      color:#1A464F;
      text-transform: lowercase;
    }

    /* ✅ Titres sidebar : MENU PRINCIPAL */
    .menu-title {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.08em;
      color: #7a6f66;
      padding: 10px 12px 4px;
      text-transform: uppercase;
      margin-top: 8px;
    }

    .menu{
      display:flex;
      flex-direction:column;
      gap:6px;
      margin-top:6px;
    }

    .menu-item{
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border-radius:12px;
      text-decoration:none;
      color:#1A464F;
      font-weight:600;
      font-size: 14px;
    }

    .menu-item:hover{
      background:#f5e2c4ff;
    }

    .menu-item.active{
      background:#1A464F !important;
      color:#ddad56ff !important;
    }

    .sidebar-foot{
      margin-top:auto;
      padding-top:10px;
      border-top:1px solid rgba(0,0,0,.06);
    }

    .sidebar-foot .link{
      display:block;
      padding:10px 12px;
      border-radius:12px;
      text-decoration:none;
      color:#1A464F;
      font-weight:600;
      font-size: 14px;
    }

    .sidebar-foot .link:hover{
      background:#f5e2c4ff;
    }

    /* ✅ Main */
    .main{
      flex:1;
      min-width:0;
      padding: 0;
      overflow-y: auto;
    }

    /* ✅ Top Navigation */
    .top-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      background: rgba(251, 237, 215, 0.96);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 24px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.03);
      animation: navFade 0.7s ease-out;
    }

    .top-nav::after{
      content:"";
      position:absolute;
      inset:auto 40px -2px 40px;
      height:2px;
      background:linear-gradient(90deg,#7d5aa6,#ec7546,#1f8c87);
      opacity:.35;
      border-radius:999px;
    }

    .brand-block { 
      display:flex; 
      align-items:center; 
      gap:10px; 
    }

    .logo-img {
      width: 40px; 
      height: 40px; 
      border-radius: 50%;
      object-fit: cover;
      box-shadow:0 6px 14px rgba(79, 73, 73, 0.18);
      animation: logoPop 0.6s ease-out;
    }

    .brand-text { 
      display:flex; 
      flex-direction:column; 
    }

    .brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 22px;
      color: #1A464F;
      letter-spacing: 1px;
      text-transform:uppercase;
      animation: titleGlow 2.8s ease-in-out infinite alternate;
    }

    .brand-tagline { 
      font-size: 12px; 
      color: #1A464F; 
      opacity: 0.8; 
    }

    .header-actions { 
      display:flex; 
      align-items:center; 
      gap:10px; 
    }

    @keyframes navFade { 
      from {opacity:0; transform:translateY(-16px);} 
      to {opacity:1; transform:translateY(0);} 
    }

    @keyframes logoPop{ 
      from{transform:scale(0.8) translateY(-6px); opacity:0;} 
      to{transform:scale(1) translateY(0); opacity:1;} 
    }

    @keyframes titleGlow{ 
      from{text-shadow:0 0 0 rgba(125,90,166,0.0);} 
      to{text-shadow:0 4px 16px rgba(125,90,166,0.55);} 
    }

    /* ✅ Main Content */
    .space-main { 
      padding: 10px 20px 60px; 
    }

    /* ✅ Page Title */
    .page-title {
        text-align: center;
        margin: 22px auto 14px auto;
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        color: #1A464F;
        position:relative;
        animation: titleFade 1s ease-out;
    }

    .page-title::after{
      content:"";
      position:absolute;
      left:50%;
      transform:translateX(-50%);
      bottom:-8px;
      width:90px;
      height:3px;
      border-radius:999px;
      background:linear-gradient(90deg,#7d5aa6,#ec7546,#1f8c87);
      opacity:.6;
    }

    @keyframes titleFade{ 
      from{opacity:0; transform:translateY(-8px);} 
      to{opacity:1; transform:translateY(0);} 
    }

    /* ✅ Filters Section */
    .filters-section {
        background: rgba(255, 247, 239, 0.95);
        border-radius: 24px;
        padding: 24px 22px;
        margin: 30px auto 30px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        max-width: 1100px;
        animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .filters-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        color: #1A464F;
        font-size: 18px;
        font-weight: 600;
    }

    .filters-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #1A464F;
        font-size: 14px;
    }

    .filter-select {
        padding: 12px 16px;
        border: 2px solid rgba(26, 70, 79, 0.1);
        border-radius: 12px;
        font-size: 14px;
        background: white;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
        color: #1A464F;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--violet);
        box-shadow: 0 0 0 3px rgba(125, 90, 166, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 999px;
        border: none;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--violet), #b58bf0);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--orange), #ffb38f);
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* ✅ Active Filters */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: linear-gradient(135deg, var(--turquoise), #7eddd5);
        color: white;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .filter-tag button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
        padding: 0;
        margin-left: 4px;
    }

    /* ✅ Results Count */
    .results-count {
        text-align: center;
        padding: 16px;
        background: rgba(255, 247, 239, 0.95);
        border-radius: 18px;
        margin: 20px auto;
        max-width: 1100px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        color: #1A464F;
        font-size: 16px;
    }

    .results-count strong {
        color: var(--violet);
        font-size: 20px;
    }

    /* ✅ Groups Grid */
    .groups-section {
        background: rgba(255, 247, 239, 0.95);
        border-radius: 24px;
        padding: 24px 22px 26px;
        margin: 30px auto;
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        max-width: 1100px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .section-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        color: #1A464F;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    /* ✅ Group Cards */
    .content-card {
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .content-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .card-icon {
        font-size: 32px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(125, 90, 166, 0.1), rgba(181, 140, 240, 0.15));
    }

    .card-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        color: #1A464F;
        margin: 0;
        flex: 1;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background: linear-gradient(135deg, var(--turquoise), #7eddd5);
        color: white;
    }

    .status-en_attente {
        background: linear-gradient(135deg, var(--orange), #ffb38f);
        color: white;
    }

    .card-body {
        padding-top: 15px;
        border-top: 1px solid rgba(0,0,0,0.06);
    }

    .card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
        font-size: 13px;
        color: #7a6f66;
    }

    .card-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .card-description {
        font-size: 14px;
        color: #555;
        line-height: 1.5;
        margin-bottom: 20px;
        max-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .card-actions {
        display: flex;
        gap: 10px;
    }

    .card-actions .btn {
        flex: 1;
        padding: 10px 16px;
        font-size: 13px;
    }

    /* ✅ Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #7a6f66;
        background: rgba(255,255,255,0.8);
        border-radius: 18px;
        border: 2px dashed rgba(122, 111, 102, 0.3);
    }

    .empty-state p {
        font-size: 16px;
        margin-bottom: 20px;
    }

    /* ✅ Quick Actions */
    .quick-actions {
        background: rgba(255, 247, 239, 0.95);
        border-radius: 24px;
        padding: 24px 22px;
        margin: 30px auto;
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        max-width: 1100px;
    }

    .quick-actions h3 {
        color: #1A464F;
        margin-bottom: 20px;
        font-size: 20px;
        text-align: center;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 20px;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .action-btn:nth-child(1) {
        background: linear-gradient(135deg, var(--violet), #b58bf0);
    }

    .action-btn:nth-child(2) {
        background: linear-gradient(135deg, var(--orange), #ffb38f);
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* ✅ Footer */
    .footer {
        background: rgba(255, 247, 239, 0.95);
        border-top: 1px solid rgba(0,0,0,0.06);
        padding: 30px 24px;
        margin-top: 30px;
        text-align: center;
        max-width: 1100px;
        margin-left: auto;
        margin-right: auto;
        border-radius: 24px;
    }

    .footer p {
        margin-bottom: 20px;
        color: #1A464F;
        font-size: 16px;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        gap: 30px;
    }

    .footer-links a {
        color: #1A464F;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
    }

    .footer-links a:hover {
        text-decoration: underline;
    }

    /* ✅ Chatbot Styles */
    #chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .chatbot-toggle {
        background: linear-gradient(135deg, var(--violet), #b58bf0);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 12px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(125, 90, 166, 0.3);
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 14px;
    }

    .chatbot-toggle:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(125, 90, 166, 0.4);
    }

    .chatbot-window {
        position: absolute;
        bottom: 60px;
        right: 0;
        width: 400px;
        height: 500px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .chatbot-window.active {
        display: flex;
        animation: slideIn 0.3s ease;
    }

    .chatbot-header {
        background: linear-gradient(135deg, var(--violet), #b58bf0);
        color: white;
        padding: 15px 20px;
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
        font-size: 20px;
    }

    .chatbot-title h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .chatbot-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        width: 24px;
        height: 24px;
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

    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chatbot-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .chatbot-messages::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 3px;
    }

    .chatbot-welcome {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 10px;
    }

    .chatbot-welcome p {
        margin-bottom: 8px;
        color: #333;
        font-size: 14px;
    }

    .message {
        padding: 10px 15px;
        border-radius: 18px;
        line-height: 1.4;
        max-width: 80%;
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
        background: linear-gradient(135deg, var(--violet), #b58bf0);
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
        padding: 15px;
        border-top: 1px solid #e1e5e9;
        background: white;
        flex-shrink: 0;
    }

    .chatbot-input-container {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .chatbot-input {
        flex: 1;
        padding: 10px 14px;
        border: 2px solid #e1e5e9;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        resize: none;
        transition: border 0.3s ease;
        min-height: 40px;
        max-height: 100px;
        line-height: 1.4;
    }

    .chatbot-input:focus {
        outline: none;
        border-color: var(--violet);
        box-shadow: 0 0 0 3px rgba(125, 90, 166, 0.1);
    }

    .chatbot-send {
        background: linear-gradient(135deg, var(--violet), #b58bf0);
        color: white;
        border: none;
        border-radius: 12px;
        width: 40px;
        height: 40px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .chatbot-send:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(125, 90, 166, 0.3);
    }

    .chatbot-suggestions {
        padding-top: 10px;
    }

    .chatbot-suggestions p {
        margin-bottom: 8px;
        color: #666;
        font-size: 13px;
        font-weight: 600;
    }

    .suggestion-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .suggestion-btn {
        background: #f8f9fa;
        border: 1px solid #e1e5e9;
        border-radius: 16px;
        padding: 6px 12px;
        font-size: 11px;
        color: #333;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .suggestion-btn:hover {
        background: #e9ecef;
        border-color: var(--violet);
        color: var(--violet);
    }

    /* ✅ Mobile Toggle Button */
    .mobile-toggle {
        display: none;
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1001;
        background: #1A464F;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
    }

    /* ✅ Responsive Design */
    @media (max-width: 900px) {
        .sidebar {
            width: 220px;
        }
        
        .grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .layout {
            flex-direction: column;
        }
        
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            padding: 15px;
        }
        
        .main {
            padding: 0;
        }
        
        .mobile-toggle {
            display: block;
        }
        
        .sidebar.collapsed {
            display: none;
        }
        
        .grid {
            grid-template-columns: 1fr;
        }
        
        .filters-form {
            grid-template-columns: 1fr;
        }
        
        .filter-actions {
            flex-direction: column;
        }
        
        .card-actions {
            flex-direction: column;
        }
        
        .top-nav {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 15px;
        }
        
        .top-nav::after {
            inset: auto 20px -2px 20px;
        }
        
        .action-buttons {
            grid-template-columns: 1fr;
        }
        
        .chatbot-window {
            width: 90vw;
            height: 60vh;
            right: 5vw;
            bottom: 70px;
        }
        
        .chatbot-toggle .chatbot-label {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .space-main {
            padding: 10px 15px 40px;
        }
        
        .filters-section,
        .groups-section,
        .quick-actions {
            padding: 20px;
            border-radius: 18px;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }
        
        .btn {
            padding: 8px 12px;
            font-size: 12px;
        }
        
        .page-title {
            font-size: 22px;
        }
        
        .chatbot-window {
            width: 95vw;
            height: 70vh;
            right: 2.5vw;
        }
    }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>

    <!-- Layout Container -->
    <div class="layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <a href="index.php" class="brand">
                <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="Logo" class="logo-img">
                <div class="brand-name">SPARKMIND</div>
            </a>

            <div class="menu-title">MENU PRINCIPAL</div>
            <nav class="menu">
                <a href="/sparkmind_mvc_100percent/index.php?page=frontoffice" class="menu-item">
                    <span class="icon">🏠</span>
                    <span>Accueil</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=browse_dons" class="menu-item">
                    <span class="icon">🎁</span>
                    <span>Parcourir les Dons</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=browse_groupes" class="menu-item active">
                    <span class="icon">👥</span>
                    <span>Parcourir les Groupes</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=create_don" class="menu-item">
                    <span class="icon">➕</span>
                    <span>Faire un Don</span>
                </a>
                <a href="/sparkmind_mvc_100percent/index.php?page=create_groupe" class="menu-item">
                    <span class="icon">✨</span>
                    <span>Créer un Groupe</span>
                </a>
            </nav>

            <div class="sidebar-foot">
                <a href="/sparkmind_mvc_100percent/index.php?page=offer_support" class="link">
                    <span class="icon"></span>
                    <span>Retour</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="main">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="top-nav-left">
                    <div class="brand-block">
                        <img src="/sparkmind_mvc_100percent/images/logo.jpg" alt="Logo" class="logo-img">
                        <div class="brand-text">
                            <div class="brand-name">SPARKMIND</div>
                            <div class="brand-tagline">Plateforme de solidarité</div>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="create_groupe.php" class="btn" style="background: linear-gradient(135deg, var(--violet), #b58bf0); color: white; text-decoration: none; padding: 8px 18px; border-radius: 999px;">
                        <span>👥</span>
                        <span>Créer un groupe</span>
                    </a>
                </div>
            </div>

            <!-- Page Title -->
            <div class="page-title">
                Parcourir les Groupes Solidaires
            </div>

            <!-- Main Content -->
            <div class="space-main">
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
                
                <!-- Filters Section -->
                <section class="filters-section">
                    <div class="filters-title">
                        <span>🔍</span>
                        <span>Filtres de recherche</span>
                    </div>
                    <form method="GET" action="browse_groupes.php" class="filters-form">
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
                            <button type="submit" class="btn btn-primary">
                                <span>🔍</span>
                                <span>Appliquer les filtres</span>
                            </button>
                            <a href="browse_groupes.php" class="btn btn-secondary">
                                <span>🔄</span>
                                <span>Réinitialiser</span>
                            </a>
                        </div>
                    </form>
                </section>
                
                <!-- Results Count -->
                <div class="results-count">
                    <strong><?php echo count($groupes); ?></strong> groupes trouvés
                </div>
                
                <!-- Groups Grid -->
                <section class="groups-section">
                    <div class="section-header">
                        <h2><span>👥</span> Groupes disponibles</h2>
                    </div>
                    
                    <?php if (!empty($groupes)): ?>
                        <div class="grid">
                            <?php foreach ($groupes as $groupe): ?>
                            <div class="content-card">
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
                                        <a href="view_groupe.php?id=<?php echo $groupe['id']; ?>" class="btn btn-primary">
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
                                <a href="browse_groupes.php" class="btn btn-primary" style="display: inline-flex; width: auto;">
                                    <span>🔍</span>
                                    <span>Voir tous les groupes</span>
                                </a>
                                <a href="create_groupe.php" class="btn btn-secondary" style="display: inline-flex; width: auto;">
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
                        <a href="create_groupe.php" class="action-btn">
                            <span>👥</span>
                            <span>Créer votre propre groupe</span>
                        </a>
                        <a href="index.php" class="action-btn">
                            <span>🏠</span>
                            <span>Retour à l'accueil</span>
                        </a>
                    </div>
                </section>

                <!-- Footer -->
                <footer class="footer">
                    <p>© 2025 Aide Solidaire - Ensemble, créons des communautés fortes ! ❤️</p>
                    <div class="footer-links">
                        <a href="index.php">🏠 Accueil</a>
                        <a href="../Backoffice/dashboard.php">🔒 Espace Admin</a>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Chatbot Bubble -->
    <div id="chatbot-container">
        <button id="chatbot-toggle" class="chatbot-toggle">
            <span class="chatbot-icon-header">🤖</span>
            <span class="chatbot-label">Assistant IA</span>
        </button>
        
        <div id="chatbot-window" class="chatbot-window">
            <div class="chatbot-header">
                <div class="chatbot-title">
                    <span class="chatbot-icon-header">🤖</span>
                    <h3>Assistant Aide Solidaire</h3>
                </div>
                <button id="chatbot-close" class="chatbot-close">×</button>
            </div>
            
            <div class="chatbot-body">
                <div id="chatbot-messages" class="chatbot-messages">
                    <div class="chatbot-welcome">
                        <p>👋 Bonjour ! Je suis l'assistant intelligent d'Aide Solidaire.</p>
                        <p>Je peux vous aider avec :</p>
                        <ul style="list-style: none; padding-left: 0; margin: 10px 0;">
                            <li>📝 Informations sur les dons</li>
                            <li>👥 Questions sur les groupes</li>
                            <li>📍 Procédures et régions</li>
                            <li>❓ Toute question sur l'entraide</li>
                        </ul>
                        <p>Comment puis-je vous aider aujourd'hui ?</p>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) &&
                !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
            }
        });

        // Auto-close sidebar on mobile when clicking a link
        document.querySelectorAll('.menu-item, .link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    const sidebar = document.getElementById('sidebar');
                    sidebar.classList.add('collapsed');
                }
            });
        });

        // Remove filter functionality
        function removeFilter(filterName) {
            const url = new URL(window.location);
            url.searchParams.delete(filterName);
            window.location.href = url.toString();
        }

        // Chatbot functionality
        document.addEventListener('DOMContentLoaded', function() {
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
                setTimeout(scrollToBottom, 100);
            });
            
            chatbotClose.addEventListener('click', function() {
                chatbotWindow.classList.remove('active');
            });
            
            // Send message
            chatbotSend.addEventListener('click', sendMessage);
            
            // Send message on Enter key
            chatbotInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
            
            // Auto-resize textarea
            chatbotInput.addEventListener('input', function() {
                this.style.height = 'auto';
                const newHeight = Math.min(this.scrollHeight, 100);
                this.style.height = newHeight + 'px';
                this.style.overflowY = newHeight >= 80 ? 'auto' : 'hidden';
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
            async function sendMessage() {
                const message = chatbotInput.value.trim();
                
                if (!message) {
                    chatbotInput.focus();
                    return;
                }
                
                // Add user message
                addMessage(message, 'user');
                
                // Clear input
                chatbotInput.value = '';
                chatbotInput.style.height = 'auto';
                
                // Disable send button
                chatbotSend.disabled = true;
                chatbotSend.innerHTML = '<span>⏳</span>';
                
                try {
                    const response = await fetch('/aide_solitaire/controller/chatbot_api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ message: message })
                    });
                    
                    const data = await response.json();
                    
                    chatbotSend.disabled = false;
                    chatbotSend.innerHTML = '<span>📤</span>';
                    
                    if (data.success) {
                        addMessage(data.message, 'bot');
                    } else {
                        const fallback = getFallbackResponse(message);
                        addMessage(fallback, 'bot');
                    }
                    
                } catch (error) {
                    console.error('Chatbot error:', error);
                    chatbotSend.disabled = false;
                    chatbotSend.innerHTML = '<span>📤</span>';
                    
                    const fallback = getFallbackResponse(message);
                    addMessage(fallback, 'bot');
                }
            }
            
            // Add message to chat
            function addMessage(text, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${sender}-message`;
                messageDiv.innerHTML = formatMessage(text);
                chatbotMessages.appendChild(messageDiv);
                scrollToBottom();
                messageDiv.style.animation = 'messageAppear 0.3s ease';
            }
            
            // Format message text
            function formatMessage(text) {
                return text
                    .replace(/\n/g, '<br>')
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            }
            
            // Fallback responses
            function getFallbackResponse(question) {
                const lowerQuestion = question.toLowerCase();
                
                if (lowerQuestion.includes('groupe')) {
                    return "Pour créer un groupe solidaire :<br>1. Rendez-vous sur la page 'Créer un groupe'<br>2. Remplissez le formulaire avec vos informations<br>3. Décrivez les objectifs de votre groupe<br>4. Soumettez la demande pour validation";
                }
                
                if (lowerQuestion.includes('don')) {
                    return "Nous acceptons plusieurs types de dons :<br>• Vêtements et textiles<br>• Nourriture non périssable<br>• Matériel médical<br>• Équipement éducatif<br>• Soutien financier<br>• Services bénévoles";
                }
                
                return "Je comprends votre question mais je rencontre des difficultés techniques. Pour une assistance immédiate, veuillez contacter directement l'équipe de support.";
            }
            
            // Scroll to bottom
            function scrollToBottom() {
                const container = document.querySelector('.chatbot-messages');
                if (container) {
                    requestAnimationFrame(() => {
                        container.scrollTop = container.scrollHeight;
                    });
                }
            }
            
            // Animate cards on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('.content-card').forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.transitionDelay = `${index * 0.1}s`;
                observer.observe(card);
            });
        });
    </script>
</body>
</html>