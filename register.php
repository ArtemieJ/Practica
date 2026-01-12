<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $pass === '') {
        $err = "Completează toate câmpurile.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Email invalid.";
    } elseif (strlen($pass) < 6) {
        $err = "Parola trebuie să aibă minim 6 caractere.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) {
            $err = "Există deja un cont cu acest email.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hash);

            if ($stmt->execute()) {
                $newId = $stmt->insert_id;
                $stmt->close();

                loginUser($newId, $name, $email);
                header("Location: admin.php");
                exit;
            } else {
                $err = "Eroare la înregistrare: " . $conn->error;
                $stmt->close();
            }
        }
    }
}
?>
<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Înregistrare</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
      <h1 class="text-xl font-semibold">Înregistrare</h1>
      <p class="text-sm text-slate-500 mt-1">Creează un cont ca să poți intra în Admin Panel.</p>

      <?php if ($err): ?>
        <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 text-sm">
          <?= htmlspecialchars($err) ?>
        </div>
      <?php endif; ?>

      <form method="post" class="mt-5 space-y-4">
        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Nume</label>
          <input name="name" type="text" required
                 class="w-full rounded-xl border border-slate-200 px-4 py-2 focus:ring-2 focus:ring-slate-200 outline-none"
                 value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Email</label>
          <input name="email" type="email" required
                 class="w-full rounded-xl border border-slate-200 px-4 py-2 focus:ring-2 focus:ring-slate-200 outline-none"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-500 mb-1">Parolă</label>
          <input name="password" type="password" required
                 class="w-full rounded-xl border border-slate-200 px-4 py-2 focus:ring-2 focus:ring-slate-200 outline-none">
        </div>

        <button class="w-full rounded-xl bg-slate-900 text-white font-semibold py-2 hover:bg-slate-800 transition">
          Creează cont
        </button>
      </form>
      <a href="index.php"
   class="mt-4 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold hover:bg-slate-50 transition w-full">
  ← Înapoi la Index
</a>

      <div class="mt-4 text-sm text-slate-600">
        Ai deja cont?
        <a class="text-slate-900 font-semibold hover:underline" href="login.php">Autentificare</a>
      </div>
    </div>
  </div>
</body>
</html>
