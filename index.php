<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
$success = isset($_GET['success']) ? true : false;
$error = isset($_GET['error']) ? true : false;
?>
<!doctype html>
<html lang="ro">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Landing Practică</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">

<header class="border-b border-slate-200 bg-white">
  <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
    <a href="index.php" class="font-semibold tracking-tight text-lg">
      My Site
    </a>

    <?php if (isLoggedIn()): ?>
      <div class="flex items-center gap-3">
        <div class="hidden sm:block text-sm text-slate-600">
          Logat ca:
          <span class="font-semibold text-slate-900">
            <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
          </span>
          <span class="text-slate-400">•</span>
          <span class="text-slate-600">
            <?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>
          </span>
        </div>

        <a href="admin.php"
           class="rounded-xl bg-slate-900 px-4 py-2 text-white font-semibold hover:bg-slate-800 transition">
          Admin Panel
        </a>

        <a href="logout.php"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold hover:bg-slate-50 transition">
          Logout
        </a>
      </div>
    <?php else: ?>
      <div class="flex items-center gap-2">
        <a href="login.php"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-semibold hover:bg-slate-50 transition">
          Login
        </a>
        <a href="register.php"
           class="rounded-xl bg-slate-900 px-4 py-2 text-white font-semibold hover:bg-slate-800 transition">
          Register
        </a>
      </div>
    <?php endif; ?>
  </div>
</header>


  <section class="max-w-6xl mx-auto px-4 py-14">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <p class="text-sm font-semibold text-blue-600">TailwindCSS + PHP + MySQL</p>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mt-2">
          Landing page simplă, rapidă și modernă
        </h1>
        <p class="mt-4 text-gray-600 leading-relaxed">
          Proiect de practică: pagină de prezentare + formular de contact care salvează mesajele în MySQL,
          și o pagină de administrare pentru a le vedea.
        </p>

        <div class="mt-6 flex gap-3">
          <a href="#contact" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
            Trimite un mesaj
          </a>
          <a href="#beneficii" class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-gray-300 font-semibold hover:bg-white">
            Vezi beneficiile
          </a>
        </div>

        <?php if ($success): ?>
          <div class="mt-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            Mesaj trimis cu succes ✅
          </div>
        <?php elseif ($error): ?>
          <div class="mt-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
            A apărut o eroare. Încearcă din nou ❌
          </div>
        <?php endif; ?>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 p-6">
          <p class="text-sm text-gray-600">Preview</p>
          <div class="mt-3 grid grid-cols-3 gap-3">
            <div class="h-16 rounded-lg bg-white border border-gray-200"></div>
            <div class="h-16 rounded-lg bg-white border border-gray-200"></div>
            <div class="h-16 rounded-lg bg-white border border-gray-200"></div>
            <div class="col-span-3 h-28 rounded-lg bg-white border border-gray-200"></div>
          </div>
        </div>
        <p class="mt-4 text-sm text-gray-600">
          Design curat + responsiv, cu Tailwind breakpoints.
        </p>
      </div>
    </div>
  </section>

  <section id="beneficii" class="max-w-6xl mx-auto px-4 pb-14">
    <h2 class="text-2xl md:text-3xl font-bold">3 beneficii</h2>
    <p class="mt-2 text-gray-600">Carduri simple, ușor de extins.</p>

    <div class="mt-8 grid md:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <h3 class="font-bold text-lg">Rapid</h3>
        <p class="mt-2 text-gray-600 text-sm">Tailwind prin CDN, fără build complicat.</p>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <h3 class="font-bold text-lg">Sigur</h3>
        <p class="mt-2 text-gray-600 text-sm">Inserare în DB cu prepared statements (anti SQLi).</p>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <h3 class="font-bold text-lg">Admin</h3>
        <p class="mt-2 text-gray-600 text-sm">Listă de mesaje stilizată, ușor de verificat.</p>
      </div>
    </div>
  </section>

  <section id="contact" class="max-w-6xl mx-auto px-4 pb-16">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
      <h2 class="text-2xl md:text-3xl font-bold">Formular de contact</h2>
      <p class="mt-2 text-gray-600">Completează câmpurile și trimite mesajul (POST → send.php).</p>

      <form class="mt-6 grid gap-4" method="POST" action="send.php">
        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="text-sm font-semibold">Nume</label>
            <input name="name" required maxlength="255"
              class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Ex: Ion Popescu" />
          </div>
          <div>
            <label class="text-sm font-semibold">Email</label>
            <input name="email" type="email" required maxlength="255"
              class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="exemplu@email.com" />
          </div>
        </div>

        <div>
          <label class="text-sm font-semibold">Mesaj</label>
          <textarea name="message" required rows="5"
            class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
            placeholder="Scrie mesajul tău..."></textarea>
        </div>

        <div class="flex items-center gap-3">
          <button
            class="px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700"
            type="submit">
            Trimite
          </button>
          <a href="admin.php" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
            Vezi mesajele în Admin →
          </a>
        </div>
      </form>
    </div>
  </section>


  <footer class="border-t border-gray-200 bg-white">
    <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-600 flex flex-col md:flex-row gap-2 md:items-center md:justify-between">
      <p>© <?php echo date('Y'); ?> landing-practica</p>
      <p>Practică Web • TailwindCSS • PHP • MySQL</p>
    </div>
  </footer>

  <script>
    const btn = document.getElementById('menuBtn');
    const menu = document.getElementById('mobileMenu');
    btn.addEventListener('click', () => menu.classList.toggle('hidden'));
  </script>
</body>
</html>
