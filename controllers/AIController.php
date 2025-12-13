<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/AIHelper.php';

class AIController {
    
    /**
     * Test AI analysis on custom text
     */
    public function test() {
        $result = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $testText = trim($_POST['test_text'] ?? '');
            
            if (!empty($testText)) {
                $aiHelper = new AIHelper();
                $result = $aiHelper->analyze($testText);
            }
        }
        
        include __DIR__ . '/../views/ai/test.php';
    }
}

