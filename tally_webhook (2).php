<?php
header("Content-Type: application/json");

// DB credentials
$host = 'sql113.infinityfree.com';
$db = 'if0_38753245_entalk';
$user = 'if0_38753245';
$pass = 's1cxY7ZecqpPD';
$port = 3306;

$input = file_get_contents("php://input");
$data = json_decode($input, true);

echo "<pre>RAW INPUT:\n$input\n\nPARSED:\n";
print_r($data);
echo "</pre>";

$question_id = $data['question_id'] ?? null;
$rating = $data['rating'] ?? null;

if ($question_id !== null && $rating !== null) {
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE TABLE IF NOT EXISTS question_feedback (
            id INT AUTO_INCREMENT PRIMARY KEY,
            question_id INT NOT NULL,
            rating INT NOT NULL,
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $stmt = $pdo->prepare("INSERT INTO question_feedback (question_id, rating) VALUES (:qid, :rating)");
        $stmt->execute([':qid' => $question_id, ':rating' => $rating]);

        echo "\n✅ Feedback recorded successfully.";
    } catch (PDOException $e) {
        echo "\n❌ DB error: " . $e->getMessage();
    }
} else {
    echo "\n❌ Invalid or incomplete data received.";
}
