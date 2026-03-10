<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsetKu — Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:        #f0f4f8;
            --surface:   #ffffff;
            --sidebar:   #0f1923;
            --accent:    #0ea5e9;
            --accent2:   #06b6d4;
            --success:   #10b981;
            --warning:   #f59e0b;
            --danger:    #ef4444;
            --text:      #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 40;
        }
        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-logo .logo-badge {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-text { font-size: 18px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
        .logo-sub  { font-size: 10px; color: rgba(255,255,255,0.35); letter-spacing: 2px; text-transform: uppercase; }

        .nav-section { padding: 20px 16px 8px; }
        .nav-label { font-size: 9px; font-weight: 700; color: rgba(255,255,255,0.25); letter-spacing: 2px; text-transform: uppercase; padding: 0 8px; margin-bottom: 6px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            color: rgba(255,255,255,0.45); font-size: 13.5px; font-weight: 500;
            cursor: pointer; transition: all 0.2s; margin-bottom: 2px;
            text-decoration: none;
        }
        .nav-item:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.85); }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(14,165,233,0.2), rgba(6,182,212,0.1));
            color: #fff;
            border: 1px solid rgba(14,165,233,0.25);
        }
        .nav-item.active svg { color: var(--accent); }
        .nav-badge {
            margin-left: auto; background: var(--accent);
            color: #fff; font-size: 10px; font-weight: 700;
            padding: 1px 7px; border-radius: 20px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 12px;
            background: rgba(255,255,255,0.05);
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: #fff; font-size: 14px; flex-shrink: 0;
        }
        .user-name  { font-size: 13px; font-weight: 600; color: #fff; }
        .user-role  { font-size: 10px; color: rgba(255,255,255,0.35); }
        .logout-btn {
            margin-left: auto; flex-shrink: 0;
            width: 30px; height: 30px; border-radius: 8px;
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);
            display: flex; align-items: center; justify-content: center;
            color: #ef4444; cursor: pointer; transition: all 0.2s;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.2); }

        /* Main */
        .main { margin-left: 260px; padding: 32px; }

        /* Topbar */
        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 32px;
        }
        .page-title { font-size: 26px; font-weight: 800; color: var(--text); letter-spacing: -0.5px; }
        .page-sub   { font-size: 13px; color: var(--muted); margin-top: 2px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .btn-primary {
            display: flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff; font-size: 13px; font-weight: 600;
            padding: 10px 18px; border-radius: 10px; border: none;
            cursor: pointer; transition: opacity 0.2s; box-shadow: 0 4px 14px rgba(14,165,233,0.3);
        }
        .btn-primary:hover { opacity: 0.9; }
        .search-box {
            display: flex; align-items: center; gap: 8px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 10px; padding: 9px 14px;
            font-size: 13px; color: var(--muted);
        }

        /* Stat Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; padding: 22px;
            transition: all 0.25s; position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .stat-card.blue::before   { background: linear-gradient(90deg, var(--accent), var(--accent2)); }
        .stat-card.green::before  { background: linear-gradient(90deg, var(--success), #34d399); }
        .stat-card.yellow::before { background: linear-gradient(90deg, var(--warning), #fbbf24); }
        .stat-card.red::before    { background: linear-gradient(90deg, var(--danger), #f87171); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }

        .stat-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .stat-icon.blue   { background: rgba(14,165,233,0.1);  color: var(--accent); }
        .stat-icon.green  { background: rgba(16,185,129,0.1);  color: var(--success); }
        .stat-icon.yellow { background: rgba(245,158,11,0.1);  color: var(--warning); }
        .stat-icon.red    { background: rgba(239,68,68,0.1);   color: var(--danger); }

        .stat-label { font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--text); letter-spacing: -1px; margin: 4px 0 6px; font-family: 'JetBrains Mono', monospace; }
        .stat-change { font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 4px; }
        .stat-change.up   { color: var(--success); }
        .stat-change.down { color: var(--danger); }

        /* Content Grid */
        .content-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; }

        /* Table Card */
        .card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
        }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 24px; border-bottom: 1px solid var(--border);
        }
        .card-title { font-size: 15px; font-weight: 700; color: var(--text); }
        .card-sub   { font-size: 12px; color: var(--muted); margin-top: 1px; }
        .btn-link   { font-size: 12px; font-weight: 600; color: var(--accent); cursor: pointer; text-decoration: none; }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            font-size: 11px; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.8px;
            padding: 12px 24px; text-align: left;
            background: #f8fafc; border-bottom: 1px solid var(--border);
        }
        tbody td { padding: 14px 24px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #f8fafc; }

        .asset-name { font-weight: 600; color: var(--text); }
        .asset-code { font-size: 11px; color: var(--muted); font-family: 'JetBrains Mono', monospace; }

        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
        }
        .badge.aktif    { background: rgba(16,185,129,0.1);  color: var(--success); }
        .badge.perbaikan{ background: rgba(245,158,11,0.1);  color: var(--warning); }
        .badge.nonaktif { background: rgba(239,68,68,0.1);   color: var(--danger); }
        .badge.dipinjam { background: rgba(14,165,233,0.1);  color: var(--accent); }

        /* Right Panel */
        .right-panel { display: flex; flex-direction: column; gap: 20px; }

        /* Kondisi Aset */
        .kondisi-item { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
        .kondisi-bar-bg { flex: 1; height: 6px; background: var(--border); border-radius: 3px; overflow: hidden; }
        .kondisi-bar { height: 100%; border-radius: 3px; }
        .kondisi-label { font-size: 13px; font-weight: 500; color: var(--text); min-width: 80px; }
        .kondisi-count { font-size: 12px; font-weight: 700; color: var(--muted); min-width: 30px; text-align: right; font-family: 'JetBrains Mono'; }

        /* Aktivitas */
        .activity-item { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .activity-item:last-child { border-bottom: none; }
        .activity-dot {
            width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0;
        }
        .activity-text { font-size: 13px; color: var(--text); line-height: 1.5; }
        .activity-time { font-size: 11px; color: var(--muted); margin-top: 2px; }

        /* Loading */
        #loading {
            position: fixed; inset: 0; background: #f0f4f8;
            display: flex; align-items: center; justify-content: center; z-index: 100;
        }
        .spinner {
            width: 36px; height: 36px;
            border: 3px solid var(--border);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Animations */
        .fade-up { opacity: 0; transform: translateY(16px); animation: fadeUp 0.5s ease forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }
        .d1 { animation-delay: 0.05s; } .d2 { animation-delay: 0.1s; }
        .d3 { animation-delay: 0.15s; } .d4 { animation-delay: 0.2s; }
        .d5 { animation-delay: 0.25s; } .d6 { animation-delay: 0.3s; }

        /* Logout confirm modal */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.4);
            display: flex; align-items: center; justify-content: center; z-index: 200;
            backdrop-filter: blur(4px);
        }
        .modal {
            background: var(--surface); border-radius: 20px;
            padding: 32px; width: 360px; text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: rgba(239,68,68,0.1); color: var(--danger);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .modal h3 { font-size: 18px; font-weight: 800; margin-bottom: 8px; }
        .modal p  { font-size: 14px; color: var(--muted); margin-bottom: 24px; }
        .modal-btns { display: flex; gap: 10px; }
        .btn-cancel {
            flex: 1; padding: 11px; border-radius: 10px;
            border: 1px solid var(--border); background: var(--surface);
            font-size: 14px; font-weight: 600; cursor: pointer; color: var(--text);
        }
        .btn-logout-confirm {
            flex: 1; padding: 11px; border-radius: 10px;
            background: var(--danger); border: none;
            font-size: 14px; font-weight: 600; cursor: pointer; color: #fff;
        }
    </style>
</head>
<body>

<!-- Loading -->
<div id="loading">
    <div class="text-center">
        <div class="spinner mx-auto mb-3"></div>
        <p style="font-size:13px;color:var(--muted)">Memverifikasi sesi...</p>
    </div>
</div>

<!-- Logout Modal -->
<div id="logout-modal" class="modal-overlay" style="display:none">
    <div class="modal">
        <div class="modal-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
        </div>
        <h3>Keluar dari AsetKu?</h3>
        <p>Sesi kamu akan diakhiri dan kamu perlu login kembali untuk mengakses sistem.</p>
        <div class="modal-btns">
            <button class="btn-cancel" onclick="closeLogoutModal()">Batal</button>
            <button class="btn-logout-confirm" onclick="confirmLogout()">Ya, Keluar</button>
        </div>
    </div>
</div>

<!-- App -->
<div id="app" style="display:none">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div style="display:flex;align-items:center;gap:12px">
                <div class="logo-badge">
                    <svg width="18" height="18" fill="none" stroke="#fff" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <div class="logo-text">AsetKu</div>
                    <div class="logo-sub">Asset Management</div>
                </div>
            </div>
        </div>

        <div class="nav-section">
            <div class="nav-label">Menu Utama</div>
            <a href="#" class="nav-item active">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="#" class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Data Aset
                <span class="nav-badge">248</span>
            </a>
            <a href="#" class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Peminjaman
                <span class="nav-badge">12</span>
            </a>
            <a href="#" class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Perbaikan
                <span class="nav-badge">5</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-label">Laporan</div>
            <a href="#" class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Rekap Aset
            </a>
            <a href="#" class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Manajemen User
            </a>
        </div>

        <!-- User & Logout -->
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar" id="sidebar-avatar">?</div>
                <div style="overflow:hidden;flex:1">
                    <div class="user-name" id="sidebar-name">-</div>
                    <div class="user-role" id="sidebar-email">-</div>
                </div>
                <button class="logout-btn" onclick="showLogoutModal()" title="Keluar">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">

        <!-- Topbar -->
        <div class="topbar fade-up d1">
            <div>
                <div class="page-title">Dashboard</div>
                <div class="page-sub">Selamat datang kembali, <span id="topbar-name" style="font-weight:700;color:var(--accent)">-</span> 👋</div>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari aset...
                </div>
                <button class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Aset
                </button>
                <!-- Tombol logout di topbar juga -->
                <button onclick="showLogoutModal()" title="Keluar"
                    style="display:flex;align-items:center;gap:6px;padding:10px 14px;border-radius:10px;border:1px solid var(--border);background:var(--surface);font-size:13px;font-weight:600;color:var(--danger);cursor:pointer;transition:all 0.2s"
                    onmouseover="this.style.background='rgba(239,68,68,0.05)'"
                    onmouseout="this.style.background='var(--surface)'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="stats-grid">
            <div class="stat-card blue fade-up d1">
                <div class="stat-icon blue">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="stat-label">Total Aset</div>
                <div class="stat-value">248</div>
                <div class="stat-change up">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                    +8 aset baru bulan ini
                </div>
            </div>
            <div class="stat-card green fade-up d2">
                <div class="stat-icon green">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-label">Aset Aktif</div>
                <div class="stat-value">201</div>
                <div class="stat-change up">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                    81% dari total aset
                </div>
            </div>
            <div class="stat-card yellow fade-up d3">
                <div class="stat-icon yellow">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="stat-label">Perlu Perbaikan</div>
                <div class="stat-value">32</div>
                <div class="stat-change down">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    5 eskalasi minggu ini
                </div>
            </div>
            <div class="stat-card red fade-up d4">
                <div class="stat-icon red">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div class="stat-label">Aset Nonaktif</div>
                <div class="stat-value">15</div>
                <div class="stat-change down">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    Perlu evaluasi segera
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-grid">

            <!-- Tabel Aset Terbaru -->
            <div class="card fade-up d5">
                <div class="card-header">
                    <div>
                        <div class="card-title">Aset Terbaru</div>
                        <div class="card-sub">10 aset yang baru ditambahkan</div>
                    </div>
                    <a href="#" class="btn-link">Lihat Semua →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Aset</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="asset-name">Laptop Dell XPS 15</div>
                                <div class="asset-code">AST-2024-001</div>
                            </td>
                            <td style="color:var(--muted);font-size:13px">Elektronik</td>
                            <td style="color:var(--muted);font-size:13px">Ruang IT</td>
                            <td><span class="badge aktif">● Aktif</span></td>
                            <td style="font-family:'JetBrains Mono';font-size:13px;font-weight:600">Rp 18jt</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="asset-name">Proyektor Epson EB</div>
                                <div class="asset-code">AST-2024-002</div>
                            </td>
                            <td style="color:var(--muted);font-size:13px">Elektronik</td>
                            <td style="color:var(--muted);font-size:13px">Aula</td>
                            <td><span class="badge dipinjam">● Dipinjam</span></td>
                            <td style="font-family:'JetBrains Mono';font-size:13px;font-weight:600">Rp 12jt</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="asset-name">AC Split Daikin 2PK</div>
                                <div class="asset-code">AST-2024-003</div>
                            </td>
                            <td style="color:var(--muted);font-size:13px">Fasilitas</td>
                            <td style="color:var(--muted);font-size:13px">Ruang Rapat</td>
                            <td><span class="badge perbaikan">● Perbaikan</span></td>
                            <td style="font-family:'JetBrains Mono';font-size:13px;font-weight:600">Rp 7jt</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="asset-name">Meja Kerja Ergonomis</div>
                                <div class="asset-code">AST-2024-004</div>
                            </td>
                            <td style="color:var(--muted);font-size:13px">Furnitur</td>
                            <td style="color:var(--muted);font-size:13px">Lantai 2</td>
                            <td><span class="badge aktif">● Aktif</span></td>
                            <td style="font-family:'JetBrains Mono';font-size:13px;font-weight:600">Rp 3.5jt</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="asset-name">Server Rack HP</div>
                                <div class="asset-code">AST-2024-005</div>
                            </td>
                            <td style="color:var(--muted);font-size:13px">Infrastruktur</td>
                            <td style="color:var(--muted);font-size:13px">Server Room</td>
                            <td><span class="badge nonaktif">● Nonaktif</span></td>
                            <td style="font-family:'JetBrains Mono';font-size:13px;font-weight:600">Rp 45jt</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Right Panel -->
            <div class="right-panel">

                <!-- Kondisi Aset -->
                <div class="card fade-up d5">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Kondisi Aset</div>
                            <div class="card-sub">Distribusi status keseluruhan</div>
                        </div>
                    </div>
                    <div style="padding:20px 24px">
                        <div class="kondisi-item">
                            <span class="kondisi-label">Aktif</span>
                            <div class="kondisi-bar-bg">
                                <div class="kondisi-bar" style="width:81%;background:var(--success)"></div>
                            </div>
                            <span class="kondisi-count">201</span>
                        </div>
                        <div class="kondisi-item">
                            <span class="kondisi-label">Dipinjam</span>
                            <div class="kondisi-bar-bg">
                                <div class="kondisi-bar" style="width:5%;background:var(--accent)"></div>
                            </div>
                            <span class="kondisi-count">12</span>
                        </div>
                        <div class="kondisi-item">
                            <span class="kondisi-label">Perbaikan</span>
                            <div class="kondisi-bar-bg">
                                <div class="kondisi-bar" style="width:13%;background:var(--warning)"></div>
                            </div>
                            <span class="kondisi-count">32</span>
                        </div>
                        <div class="kondisi-item" style="margin-bottom:0">
                            <span class="kondisi-label">Nonaktif</span>
                            <div class="kondisi-bar-bg">
                                <div class="kondisi-bar" style="width:6%;background:var(--danger)"></div>
                            </div>
                            <span class="kondisi-count">15</span>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="card fade-up d6">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Aktivitas Terbaru</div>
                            <div class="card-sub">Log perubahan data aset</div>
                        </div>
                    </div>
                    <div style="padding:8px 24px 16px">
                        <div class="activity-item">
                            <div class="activity-dot" style="background:var(--success)"></div>
                            <div>
                                <div class="activity-text">Laptop Dell XPS 15 <strong>ditambahkan</strong></div>
                                <div class="activity-time">2 menit lalu · oleh Admin</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot" style="background:var(--accent)"></div>
                            <div>
                                <div class="activity-text">Proyektor Epson <strong>dipinjam</strong> oleh Divisi Pemasaran</div>
                                <div class="activity-time">1 jam lalu · oleh Budi</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot" style="background:var(--warning)"></div>
                            <div>
                                <div class="activity-text">AC Daikin dilaporkan <strong>perlu servis</strong></div>
                                <div class="activity-time">3 jam lalu · oleh Siti</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot" style="background:var(--danger)"></div>
                            <div>
                                <div class="activity-text">Server Rack HP diset <strong>nonaktif</strong></div>
                                <div class="activity-time">Kemarin · oleh Admin</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

<script>
    const BASE_URL = 'http://127.0.0.1:8000/api';

    function getInitial(name) {
        return name ? name.charAt(0).toUpperCase() : '?';
    }

    function populateUser(user) {
        const name  = user?.name  ?? '-';
        const email = user?.email ?? '-';
        document.getElementById('sidebar-avatar').textContent = getInitial(name);
        document.getElementById('sidebar-name').textContent   = name;
        document.getElementById('sidebar-email').textContent  = email;
        document.getElementById('topbar-name').textContent    = name;
    }

    function redirectToLogin() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.replace('/login');
    }

    // ── Modal Logout ──
    function showLogoutModal() {
        document.getElementById('logout-modal').style.display = 'flex';
    }
    function closeLogoutModal() {
        document.getElementById('logout-modal').style.display = 'none';
    }
    async function confirmLogout() {
        const token = localStorage.getItem('token');
        try {
            await fetch(`${BASE_URL}/auth/logout`, {
                method: 'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });
        } finally {
            redirectToLogin();
        }
    }

    // Tutup modal jika klik di luar
    document.getElementById('logout-modal').addEventListener('click', function(e) {
        if (e.target === this) closeLogoutModal();
    });

    // ── Init ──
    async function init() {
        const token = localStorage.getItem('token');
        if (!token) { redirectToLogin(); return; }

        try {
            const response = await fetch(`${BASE_URL}/auth/me`, {
                headers: {
                    'Content-Type':  'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.status === 200) {
                const data = await response.json();
                if (data.success) {
                    localStorage.setItem('user', JSON.stringify(data.user));
                    populateUser(data.user);
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('app').style.display = 'block';
                } else {
                    redirectToLogin();
                }
            } else {
                redirectToLogin();
            }
        } catch (err) {
            const localUser = JSON.parse(localStorage.getItem('user') || '{}');
            populateUser(localUser);
            document.getElementById('loading').style.display = 'none';
            document.getElementById('app').style.display = 'block';
        }
    }

    init();
</script>

</body>
</html>
