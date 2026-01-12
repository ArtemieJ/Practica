<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
requireAuth(); 


$flash_ok = '';
$flash_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_message') {
    $id      = (int)($_POST['id'] ?? 0);
    $name    = trim($_POST['name'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($id <= 0) {
        $flash_err = "ID invalid.";
    } elseif ($name === '' || $message === '') {
        $flash_err = "Completează numele și mesajul.";
    } else {
        $stmt = $conn->prepare("UPDATE messages SET name = ?, message = ? WHERE id = ?");
        if (!$stmt) {
            $flash_err = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("ssi", $name, $message, $id);
            if (!$stmt->execute()) {
                $flash_err = "Update failed: " . $conn->error;
            } else {
                $flash_ok = "Mesajul #{$id} a fost actualizat.";
            }
            $stmt->close();
        }
    }
}

$edit_id = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;

$result = $conn->query("SELECT id, name, email, message, created_at FROM messages ORDER BY id DESC");
if (!$result) {
    die("DB error: " . $conn->error);
}
?>
<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin - Mesaje</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-900">
  <div class="max-w-6xl mx-auto px-4 py-10">

    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold tracking-tight">Mesaje trimise</h1>
      <div class="text-sm text-slate-500">Admin Panel</div>
      <div class="mt-4 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold hover:bg-slate-50 transition w-full"><a href="index.php">Acasa</a></div>
    </div>
    <div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-2xl font-semibold">Mesaje trimise</h1>
    <p class="text-sm text-slate-500">Logat ca: <?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
  </div>
  <a href="logout.php" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold hover:bg-slate-50">
    Logout
  </a>
</div>


    <?php if ($flash_ok): ?>
      <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
        <?= htmlspecialchars($flash_ok) ?>
      </div>
    <?php endif; ?>

    <?php if ($flash_err): ?>
      <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
        <?= htmlspecialchars($flash_err) ?>
      </div>
    <?php endif; ?>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100">
        <p class="text-sm text-slate-600">
          Aici poți vedea și edita mesajele utilizatorilor (nume + conținut).
        </p>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 text-slate-600">
            <tr>
              <th class="text-left font-semibold px-6 py-3">ID</th>
              <th class="text-left font-semibold px-6 py-3">Nume</th>
              <th class="text-left font-semibold px-6 py-3">Email</th>
              <th class="text-left font-semibold px-6 py-3">Mesaj</th>
              <th class="text-left font-semibold px-6 py-3">Data</th>
              <th class="text-left font-semibold px-6 py-3">Acțiuni</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">
          <?php while ($row = $result->fetch_assoc()): ?>
            <?php
              $id = (int)$row['id'];
              $isEditing = ($edit_id > 0 && $edit_id === $id);
            ?>
            <tr class="hover:bg-slate-50/60">
              <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                #<?= $id ?>
              </td>

              <?php if ($isEditing): ?>
                <td class="px-6 py-4" colspan="4">
                  <form method="post" action="admin.php" class="space-y-4">
                    <input type="hidden" name="action" value="update_message">
                    <input type="hidden" name="id" value="<?= $id ?>">

                    <div>
                      <label class="block text-xs font-semibold text-slate-500 mb-1">Nume</label>
                      <input
                        type="text"
                        name="name"
                        value="<?= htmlspecialchars($row['name'] ?? '') ?>"
                        required
                        class="w-full rounded-xl border border-slate-200 px-4 py-2 outline-none focus:ring-2 focus:ring-slate-200"
                      >
                    </div>

                    <div class="text-xs text-slate-500">
                      Email (read-only):
                      <span class="font-semibold text-slate-700"><?= htmlspecialchars($row['email'] ?? '') ?></span>
                    </div>

                    <div>
                      <label class="block text-xs font-semibold text-slate-500 mb-1">Mesaj</label>
                      <textarea
                        name="message"
                        required
                        class="w-full min-h-[130px] rounded-xl border border-slate-200 px-4 py-2 outline-none focus:ring-2 focus:ring-slate-200"
                      ><?= htmlspecialchars($row['message'] ?? '') ?></textarea>
                    </div>

                    <div class="flex gap-3">
                      <button
                        type="submit"
                        class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-white font-semibold hover:bg-slate-800 transition"
                      >
                        Salvează
                      </button>

                      <a
                        href="admin.php"
                        class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-700 font-semibold hover:bg-slate-50 transition"
                      >
                        Anulează
                      </a>
                    </div>
                  </form>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 border border-amber-200">
                    Editare
                  </span>
                </td>

              <?php else: ?>
                <td class="px-6 py-4 text-slate-800">
                  <?= htmlspecialchars($row['name'] ?? '') ?>
                </td>

                <td class="px-6 py-4 text-slate-700">
                  <?= htmlspecialchars($row['email'] ?? '') ?>
                </td>

                <td class="px-6 py-4 text-slate-800">
                  <div class="max-w-xl whitespace-pre-wrap break-words">
                    <?= htmlspecialchars($row['message'] ?? '') ?>
                  </div>
                </td>

                <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                  <?= htmlspecialchars($row['created_at'] ?? '') ?>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <a
                    href="admin.php?edit_id=<?= $id ?>"
                    class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-800 font-semibold hover:bg-slate-50 transition"
                  >
                    Editează
                  </a>
                </td>
              <?php endif; ?>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <p class="mt-6 text-xs text-slate-500">
      Notă: Email-ul rămâne read-only. Se editează doar Nume + Mesaj.
    </p>
  </div>
</body>
</html>
