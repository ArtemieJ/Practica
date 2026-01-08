<?php
require_once __DIR__ . '/db.php';

$result = $conn->query("SELECT id, name, email, message, created_at FROM messages ORDER BY created_at DESC");
$messages = $result->fetch_all(MYSQLI_ASSOC);

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin • Mesaje</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">

  <header class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 py-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-extrabold">Admin • Mesaje</h1>
        <p class="text-sm text-gray-600">Total: <?php echo count($messages); ?></p>
      </div>
      <a href="index.php" class="px-4 py-2 rounded-xl border border-gray-300 font-semibold hover:bg-white">
        ← Înapoi
      </a>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 py-10">
    <?php if (count($messages) === 0): ?>
      <div class="bg-white border border-gray-200 rounded-2xl p-6 text-gray-600">
        Nu există mesaje încă.
      </div>
    <?php else: ?>
      <div class="grid gap-4">
        <?php foreach ($messages as $m): ?>
          <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
              <div>
                <p class="font-bold text-lg"><?php echo e($m['name']); ?></p>
                <p class="text-sm text-gray-600"><?php echo e($m['email']); ?></p>
              </div>
              <p class="text-sm text-gray-500">
                <?php echo e($m['created_at']); ?> • #<?php echo (int)$m['id']; ?>
              </p>
            </div>
            <p class="mt-4 text-gray-700 whitespace-pre-line"><?php echo e($m['message']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

</body>
</html>
